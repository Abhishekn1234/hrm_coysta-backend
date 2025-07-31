<?php

namespace App\Http\Controllers; // ✅ Only once
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\TaskTracking;
use App\Models\Project;
use App\Models\Backlog;
use App\Models\EmploymentHistory;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Events\BacklogApproved;
use App\Models\Task;
use App\Models\TaskLog;
use PDF;
class PeopleController extends Controller
{
   public function assignTask(Request $request, $id)
{
    $request->validate([
        'task_name' => 'required|string|max:255',
        'task_description' => 'nullable|string',
        'deadline' => 'required|date',
        'project_name' => 'nullable|string|max:255',
        'customer' => 'nullable|string|max:255',
        'project_value' => 'nullable|numeric',
        'project_status' => 'nullable|in:In Progress,Completed,Pending',
    ]);

    $user = Customer::findOrFail($id);

    // 🔍 Get project_id from project_name
    $project = \App\Models\Project::where('name', $request->project_name)->first();
    $projectId = $project ? $project->id : null;

    $task = $user->tasks()->create([
        'task_name' => $request->task_name,
        'task_description' => $request->task_description,
        'deadline' => $request->deadline,
        'assigned_by' => auth()->id() ?? null,
        'project_name' => $request->project_name,
        'project_id' => $projectId,
        'customer' => $request->customer,
        'project_value' => $request->project_value,
        'project_status' => $request->project_status,
    ]);

    return response()->json([
        'message' => 'Task with project assigned successfully',
        'task' => $task,
    ]);
}

// ✅ GET: Show all tasks assigned to a specific customer
public function customerTasks($id)
{
    $customer = Customer::with('tasks')->findOrFail($id);

    return response()->json([
        'customer' => $customer->display_name,
        'tasks' => $customer->tasks
    ]);
}
public function UserTasks($id)
{
    $customer = \App\Models\User::with('tasks')->findOrFail($id);

    return response()->json([
        'customer' => $customer->display_name,
        'tasks' => $customer->tasks
    ]);
}
public function storeTasks(Request $request)
{
    $validator = Validator::make($request->all(), [
        'project_id' => 'required|exists:projects,id',
        'user_id' => 'required|exists:users,id',
        'task_name' => 'required|string|max:255',
        'task_description' => 'nullable|string',
        'deadline' => 'required|date|after_or_equal:today',
    ]);

    if ($validator->fails()) {
        Log::warning('Task creation validation failed', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $project = Project::findOrFail($request->project_id);

    $currentDate = Carbon::now();
    $deadline = Carbon::parse($request->deadline);
    $duration = $currentDate->diffForHumans($deadline, true);

    $taskData = [
        'project_id' => $request->project_id,
        'project_name' => $project->project_name,
        'user_id' => $request->user_id,
        'assigned_by' => auth()->check() ? auth()->id() : null,
        'task_name' => $request->task_name,
        'task_description' => $request->task_description,
        'deadline' => $request->deadline,
        'project_status' => 'In Progress',
        'duration' => $duration,
    ];

    // Log task data before saving
    Log::info('Creating new task with data:', $taskData);

    $task = Task::create($taskData);

    Log::info('Task created successfully', ['task_id' => $task->id]);

    return response()->json(['data' => $task], 201);
}

    public function getCounts()
    {
        $total = \App\Models\User::where('user_type', 'STAFF')->count();

        $active = \App\Models\User::where('user_type', 'STAFF')
            ->where('status', 1) // assuming 1 means active
            ->count();

        $inactive = \App\Models\User::where('user_type', 'STAFF')
            ->where(function ($query) {
                $query->where('status', 0)->orWhereNull('status');
            })
            ->count();

        return response()->json([
            'total_staff' => $total,
            'active_staff' => $active,
            'inactive_staff' => $inactive
        ]);
    }

    // Get monthly count of staff (based on join_date)
    public function getMonthlyStaffCounts()
    {
        $monthlyCounts = \App\Models\User::select(
                DB::raw("DATE_FORMAT(join_date, '%Y-%m') as month"),
                DB::raw("COUNT(*) as count")
            )
            ->where('user_type', 'STAFF')
            ->whereNotNull('join_date')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($monthlyCounts);
    }

    public function getAllStaff()
{
    $staff = \App\Models\User::with([
       'leaveRequests',
        'departmentInfo',
        'attendances',
        'tasks'
    ])
    ->where('user_type', 'STAFF')
    ->get();

    return response()->json($staff);
}

// Get a specific staff user by ID
public function getStaffById($id)
{
    $staff = \App\Models\User::with([
        'leaveRequests',
        'departmentInfo',
        'attendances',
        'tasks'
    ])
    ->where('user_type', 'STAFF')
    ->find($id);

    if (!$staff) {
        return response()->json(['message' => 'Staff not found'], 404);
    }

    return response()->json($staff);
}



public function store(Request $request)
{
    try {
        // ✅ Validation for all expected fields
        $validated = $request->validate([
            // Basic Info
            'salutation' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'designation' => 'required|string|max:250',
            'status' => 'nullable|integer',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'join_date' => 'nullable|date',
            'nature_of_staff' => 'nullable|in:Probation,Permanent,Contract',
            'staff_type' => 'nullable|in:Monthly,Daily',
            'user_type' => 'required|in:STAFF',
            'experience' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:250',
            'organization' => 'nullable|string|max:255',

            // Contact Info
            'phone' => 'required|string|max:250',
            'work_phone' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:255',
            'personal_email' => 'nullable|string|email|max:255',
            'emergency_contact_1' => 'nullable|string|max:20',
            'emergency_contact_2' => 'nullable|string|max:20',
            'parent_mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',

            // Financial Info
            'daily_remuneration' => 'nullable|numeric',
            'rent_allowance_percent' => 'nullable|numeric',
            'casual_leaves' => 'nullable|integer',
            'esi_card_no' => 'nullable|string|max:100',
            'pf_no' => 'nullable|string|max:100',
            'covid_vaccinated' => 'nullable|boolean',

            // Optional Info
            'skills' => 'nullable|string',
            'bank_document_date' => 'nullable|date',
            'bank_document_title' => 'nullable|string|max:255',
            'bank_document_description' => 'nullable|string',

            // Previous Employment
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:191',
            'previous_company' => 'nullable|string|max:255',
            'previous_position' => 'nullable|string|max:255',
            'previous_responsibilities' => 'nullable|string',

            // Login
            'login_enabled' => 'nullable|boolean',
        ]);

        // ✅ Create the user object
        $user = new User($validated);

        // Set default user_type and full name
        $user->user_type = 'STAFF';
        $user->name = $user->first_name . ' ' . $user->last_name;

        // ✅ Handle login toggle
        $user->isLogin = $request->input('login_enabled', 0);

        // ✅ Handle file uploads
        $uploadFields = [
            'resume',
            'aadhar_front',
            'aadhar_back',
            'driving_license_front',
            'driving_license_back',
            'photo',
            'passport_size_photo',
            'pan_card',
            'passport_front',
            'passport_back',
            'pf_document',
            'esi_document',
            'employment_contract',
            'bank_document_file',
        ];

        foreach ($uploadFields as $field) {
            $user->$field = $this->storeFile($request, $field);
        }

        // ✅ Save user
        $user->save();

        return response()->json([
            'message' => 'Staff added successfully',
            'data' => $user
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed during staff store', ['errors' => $e->errors()]);
        return response()->json([
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        \Log::error('Error storing staff: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
        return response()->json([
            'message' => 'Something went wrong while saving staff.',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function listEstimates()
    {
        return Estimate::with('customer')->get();
    }

    // Add a new estimate
    public function addEstimate(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'estimate_number' => 'required|unique:estimates',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        $estimate = Estimate::create($data);
        return response()->json($estimate, 201);
    }

    // View a single estimate
    public function viewEstimate(Estimate $estimate)
    {
        return $estimate->load('customer');
    }

    // Update an existing estimate
    public function updateEstimate(Request $request, Estimate $estimate)
    {
        $data = $request->validate([
            'estimate_number' => 'sometimes|string',
            'date' => 'sometimes|date',
            'amount' => 'sometimes|numeric',
            'status' => 'nullable|string',
        ]);

        $estimate->update($data);
        return response()->json($estimate);
    }

    // Delete an estimate
    public function deleteEstimate(Estimate $estimate)
    {
        $estimate->delete();
        return response()->json(['message' => 'Estimate deleted']);
    }
    
       public function AllTasks()
{
    $data = Task::with(['project', 'customer', 'user', 'logs'])->get();

    return response()->json($data);
}

    
    public function generate(Request $request)
    {
        $data = $request->all();

        $pdf = PDF::loadView('payslip.pdf', ['staff' => $data]);

        return $pdf->download('Payslip_' . $data['name'] . '_' . date('F_Y') . '.pdf');
    }
public function update(Request $request, $id)
{
    $user = User::where('id', $id)->where('user_type', 'STAFF')->firstOrFail();

    $validated = $request->validate([
        'first_name' => 'required|string|max:191',
        'last_name' => 'required|string|max:191',
        'phone' => 'required|string|max:250',
        'user_type' => 'required|in:STAFF',
        'designation' => 'required|string|max:250',
        'status' => 'nullable|integer',
        'date_of_birth' => 'nullable|date',
        'blood_group' => 'nullable|string|max:5',
        'join_date' => 'nullable|date',
        'nature_of_staff' => 'nullable|in:Probation,Permanent,Contract',
        'daily_remuneration' => 'nullable|numeric',
        'rent_allowance_percent' => 'nullable|numeric',
        'casual_leaves' => 'nullable|integer',
        'esi_card_no' => 'nullable|string|max:100',
        'pf_no' => 'nullable|string|max:100',
        'covid_vaccinated' => 'nullable|boolean',
        'skills' => 'nullable|string',
        'qualification' => 'nullable|string|max:250',
        'experience' => 'nullable|string',
        'salutation' => 'nullable|string',
        'staff_type' => 'nullable|in:Monthly,Daily',
        'parent_mobile' => 'nullable|string|max:20',
        'work_phone' => 'nullable|string|max:50',
        'personal_email' => 'nullable|string|max:255',
        'email' => 'nullable|string|email|max:255',
        'emergency_contact_1' => 'nullable|string|max:20',
        'emergency_contact_2' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'info_date' => 'nullable|date',
        'info_title' => 'nullable|string|max:255',
        'info_description' => 'nullable|string',
        'bank_document_date' => 'nullable|date',
        'bank_document_title' => 'nullable|string|max:255',
        'bank_document_description' => 'nullable|string',
        'organization' => 'nullable|string',
        'isLogin' => 'nullable|boolean',
        'department' => 'nullable|string|max:191',
    ]);

    $user->fill($validated);

    // Force user_type
    $user->user_type = 'STAFF';

    // Full name
    $user->name = $user->first_name . ' ' . $user->last_name;

    // Optional flags
    $user->isLogin = $request->input('isLogin', 0);

    // Optional file uploads
    $user->resume = $this->storeFile($request, 'resume', $user->resume);
    $user->aadhar_front = $this->storeFile($request, 'aadhar_front', $user->aadhar_front);
    $user->aadhar_back = $this->storeFile($request, 'aadhar_back', $user->aadhar_back);
    $user->driving_license_front = $this->storeFile($request, 'driving_license_front', $user->driving_license_front);
    $user->driving_license_back = $this->storeFile($request, 'driving_license_back', $user->driving_license_back);
    $user->photo = $this->storeFile($request, 'photo', $user->photo);
    $user->passport_size_photo = $this->storeFile($request, 'passport_size_photo', $user->passport_size_photo);
    $user->pan_card = $this->storeFile($request, 'pan_card', $user->pan_card);
    $user->passport_front = $this->storeFile($request, 'passport_front', $user->passport_front);
    $user->passport_back = $this->storeFile($request, 'passport_back', $user->passport_back);
    $user->pf_document = $this->storeFile($request, 'pf_document', $user->pf_document);
    $user->esi_document = $this->storeFile($request, 'esi_document', $user->esi_document);
    $user->employment_contract = $this->storeFile($request, 'employment_contract', $user->employment_contract);
    $user->bank_document_file = $this->storeFile($request, 'bank_document_file', $user->bank_document_file);

    $user->save();

    return response()->json([
        'message' => 'Staff updated successfully',
        'data' => $user
    ]);
}

public function destroy($id)
{
    $user = User::where('id', $id)->where('user_type', 'STAFF')->first();

    if (!$user) {
        return response()->json(['message' => 'Staff not found'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'Staff deleted successfully']);
}

public function getDurationForUser($id)
{
    $task = Task::findOrFail($id);

    $logs = $task->logs()->with('user')->get()->groupBy('user_id');

    $results = [];

    foreach ($logs as $userId => $userLogs) {
        $user = $userLogs->first()->user;
        $totalSeconds = 0;

        foreach ($userLogs as $log) {
            $durationStr = strtolower($log->duration); // normalize

            preg_match('/(\d+)\s*hr/', $durationStr, $hrMatch);
            preg_match('/(\d+)\s*min/', $durationStr, $minMatch);
            preg_match('/(\d+)\s*sec/', $durationStr, $secMatch);

            $hrs = isset($hrMatch[1]) ? (int)$hrMatch[1] : 0;
            $mins = isset($minMatch[1]) ? (int)$minMatch[1] : 0;
            $secs = isset($secMatch[1]) ? (int)$secMatch[1] : 0;

            $totalSeconds += ($hrs * 3600) + ($mins * 60) + $secs;
        }

        // Format to hh:mm:ss
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $results[] = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'total_duration' => $formattedDuration,
        ];
    }

    return response()->json([
        'task_id' => $task->id,
        'task_name' => $task->task_name ?? null,
         'email' => $user->email ?? null,
        'users' => $results
    ]);
}


public function assignTaskToCustomer(Request $request, $customerId)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'task_name' => 'required|string|max:255',
        'task_description' => 'nullable|string',
        'deadline' => 'required|date',
        'project_name' => 'required|string|max:255', // required to fetch project_id
        'project_value' => 'nullable|numeric',
        'project_status' => 'nullable|in:In Progress,Completed,Pending',
    ]);

    $customer = User::findOrFail($customerId);

    $now = Carbon::now();
    $deadline = Carbon::parse($request->deadline);
    $diff = $now->diff($deadline);

    $parts = [];
    if ($diff->d > 0) $parts[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
    if ($diff->h > 0) $parts[] = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
    if ($diff->i > 0) $parts[] = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    if (empty($parts)) $parts[] = 'less than 1 minute';
    $detailedDuration = implode(' ', $parts);

    // ✅ Get the project ID based on project_name
    $project = \App\Models\Project::where('project_name', $request->project_name)->first();

    if (!$project) {
        return response()->json(['error' => 'Project not found with the provided name'], 404);
    }

    // ✅ Create task
    $task = $customer->tasks()->create([
        'user_id' => $request->user_id,
        'task_name' => $request->task_name,
        'task_description' => $request->task_description,
        'deadline' => $request->deadline,
        'assigned_by' => auth()->id() ?? null,
        'project_name' => $request->project_name,
        'project_id' => $project->id, // ✅ store project_id
        'project_value' => $request->project_value,
        'project_status' => $request->project_status,
        'duration' => $detailedDuration,
    ]);

    return response()->json([
        'message' => 'Task assigned to customer successfully',
        'task' => $task,
        'duration_detailed' => $detailedDuration
    ]);
}
public function resume(Request $request, $taskId, $userId)
{
    $task = Task::findOrFail($taskId);

    $task->project_status = 'In Progress';
    $task->save();

    $log = new TaskLog();
    $log->task_id = $taskId;
    $log->user_id = $userId;
    $log->resumed_at = now();
    $log->save();

    return response()->json(['message' => 'Task resumed']);
}


public function pause(Request $request, $taskId, $userId)
{
    $task = Task::findOrFail($taskId);
    $task->project_status = 'Paused';
    $task->save();

    $log = TaskLog::where('task_id', $taskId)
                  ->where('user_id', $userId)
                  ->whereNull('paused_at')
                  ->latest()
                  ->first();

    if ($log) {
        $log->paused_at = now();

        if ($log->resumed_at && $log->paused_at) {
            $resumed = Carbon::parse($log->resumed_at);
            $paused = Carbon::parse($log->paused_at);
            $interval = $resumed->diff($paused);

            // Formatted time: "X hrs Y minutes Z seconds"
            $formatted = '';
            if ($interval->h > 0) {
                $formatted .= $interval->h . ' hrs ';
            }
            if ($interval->i > 0 || $interval->h === 0) {
                $formatted .= $interval->i . ' minutes ';
            }
            if ($interval->s > 0 || ($interval->h === 0 && $interval->i === 0)) {
                $formatted .= $interval->s . ' seconds';
            }

            $formatted = trim($formatted);
            $log->duration = $formatted;
            $log->save();

            TaskTracking::create([
                'task_id'    => $taskId,
                'user_id'    => $userId,
                'time_taken' => $formatted,
                'date'       => now()->toDateString(),
                'time'       => now()->toTimeString(),
            ]);
        }
    } else {
        Log::info('No open log found to pause', ['task_id' => $taskId, 'user_id' => $userId]);
    }

    return response()->json(['message' => 'Task paused']);
}




public function end(Request $request, $taskId, $userId)
{
    $task = Task::findOrFail($taskId);

    $log = $task->logs()
                ->where('user_id', $userId)
                ->whereNull('paused_at')
                ->latest()
                ->first();

    if ($log) {
        $log->paused_at = now();

        $resumed = Carbon::parse($log->resumed_at);
        $paused = Carbon::parse($log->paused_at);
        $interval = $resumed->diff($paused);

        $formatted = '';
        if ($interval->h > 0) {
            $formatted .= $interval->h . ' hrs ';
        }
        if ($interval->i > 0 || $interval->h === 0) {
            $formatted .= $interval->i . ' minutes ';
        }
        if ($interval->s > 0 || ($interval->h === 0 && $interval->i === 0)) {
            $formatted .= $interval->s . ' seconds';
        }

        $formatted = trim($formatted);
        $log->duration = $formatted;
        $log->save();

        // You can optionally convert total hours as decimal for `task->duration` sum
        $task->duration += round(($interval->h * 60 + $interval->i + $interval->s / 60) / 60, 2);

        TaskTracking::create([
            'task_id'    => $taskId,
            'user_id'    => $userId,
            'time_taken' => $formatted,
            'date'       => now()->toDateString(),
            'time'       => now()->toTimeString(),
        ]);
    }

    $task->project_status = 'Completed';
    $task->save();

    return response()->json(['message' => 'Task ended', 'total_duration' => $task->duration]);
}


public function deleteTask($id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    $task->delete();

    return response()->json(['message' => 'Task deleted successfully']);
}


public function showTasks($id)
{
    $task = Task::with(['project', 'user'])->findOrFail($id);

    return response()->json($task);
}
public function updateTasks(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $task->update($request->all());

    return response()->json(['message' => 'Task updated successfully']);
}
public function getSingleBacklog($id)
{
    $backlog = Backlog::with(['user', 'assignedUser', 'takenUser', 'project', 'task'])->findOrFail($id);
    return response()->json($backlog);
}

public function addBacklog(Request $request)
{
    try {
        Log::info('Incoming request to addBacklog', $request->all());

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'backlog_assigned_user_id' => 'nullable|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'backlog_taken_user_id' => 'nullable|exists:users,id',
            'assigned_task_id' => 'nullable|exists:task,id',
            'sprint_name' => 'nullable|string|max:250',
            'backlog_name' => 'required|string|max:250',
            'backlog_description' => 'nullable|string',
            'estimated_time' => 'nullable|numeric',
            'ceo_approval' => 'nullable|boolean',
            'status' => 'required|integer'
        ]);

        Log::info('Validated data for backlog:', $validated);

        $backlog = Backlog::create($validated);

        Log::info('Backlog created successfully:', $backlog->toArray());

        return response()->json($backlog, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed for backlog:', ['errors' => $e->errors()]);
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Error adding backlog:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Something went wrong', 'debug' => $e->getMessage()], 500);
    }
}


public function updateBacklog(Request $request, $id)
{
    $backlog = Backlog::findOrFail($id);

    
        $validated = $request->validate([
            'backlog_name' => 'sometimes|required|string|max:250',
            'backlog_description' => 'nullable|string',
            'estimated_time' => 'nullable|numeric',
            'ceo_approval' => 'nullable|string',
            'status' => 'nullable|integer',
            'project_id' => 'nullable|exists:projects,id',
            'backlog_taken_user_id' => 'nullable|exists:users,id',
            'assigned_task_id' => 'nullable|exists:task,id', // ✅ Add this
        ]);


    $backlog->update($validated);

    return response()->json($backlog);
}


public function deleteBacklog($id)
{
    $backlog = Backlog::findOrFail($id);
    $backlog->delete();

    return response()->json(['message' => 'Backlog deleted successfully.']);
}


public function getAllBacklogs()
{
    try {
        $backlogs = Backlog::with(['project', 'task', 'user'])->get();
        return response()->json($backlogs);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch backlogs',
            'message' => $e->getMessage()
        ], 500);
    }
}


public function approveBacklog($id)
{
    $backlog = Backlog::findOrFail($id);
    $backlog->ceo_approval = 'Approved';
    $backlog->save();

    event(new BacklogApproved($backlog->id, "Backlog #{$backlog->id} approved"));

    return response()->json(['message' => 'Backlog approved by CEO.']);
}
public function rejectBacklog($id)
{
    if (Auth::user()->type !== 'ceo') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $backlog = Backlog::findOrFail($id);
    $backlog->ceo_approval = 'Rejected';
    $backlog->save();

    return response()->json(['message' => 'Backlog rejected by CEO.']);
}
public function Salary(Request $request)
{
    $monthName = $request->query('month'); // e.g., "July"
    $year = date('Y'); // You can also make year dynamic if needed

    // Default to current month if none provided
    if (!$monthName) {
        $monthName = date('F');
    }

    // Convert month name to number
    $monthNum = date('m', strtotime($monthName));
    $startOfMonth = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
    $endOfMonth = $startOfMonth->copy()->endOfMonth();

    // Get all dates in the month except Sundays
    $allWorkingDates = collect(CarbonPeriod::create($startOfMonth, $endOfMonth))
        ->filter(function ($date) {
            return $date->dayOfWeek !== Carbon::SUNDAY;
        });

    // Total working days (excluding Sundays)
    $totalWorkingDays = $allWorkingDates->count();

    $users = User::with(['leaveRequests' => function ($query) use ($startOfMonth, $endOfMonth) {
        $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('from_date', [$startOfMonth, $endOfMonth])
              ->orWhereBetween('to_date', [$startOfMonth, $endOfMonth])
              ->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                  $q2->where('from_date', '<=', $startOfMonth)
                     ->where('to_date', '>=', $endOfMonth);
              });
        });
    }])->get();

    $results = $users->map(function ($user) use ($allWorkingDates) {
        $leaveDates = collect();

        foreach ($user->leaveRequests as $leave) {
            $from = Carbon::parse($leave->from_date);
            $to = Carbon::parse($leave->to_date);
            $period = CarbonPeriod::create($from, $to);

            // Add only working days (exclude Sundays) within leave period
            foreach ($period as $date) {
                if ($date->dayOfWeek !== Carbon::SUNDAY && $allWorkingDates->contains($date)) {
                    $leaveDates->push($date->toDateString());
                }
            }
        }

        $uniqueLeaveDays = $leaveDates->unique()->count();
        $attendedDays = $allWorkingDates->count() - $uniqueLeaveDays;

        $dailyHours = 8;
        $monthlyHours = $attendedDays * $dailyHours;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'created_at'=>$user->created_at,
            'leaveDays' => $uniqueLeaveDays,
            'attendedDays' => $attendedDays,
            'monthlyHours' => $monthlyHours,
            'dailyHours' => $dailyHours,
        ];
    });

    return response()->json($results);
}
public function Punching(Request $request)
{
    $query = \App\Models\Attendance::with('user');

    // Apply search filter ONLY if provided
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->input('search') . '%');
        });

        // If date is also provided with search
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

    } elseif ($request->filled('date')) {
        // Apply only date filter if search is not given
        $query->whereDate('date', $request->input('date'));
    }

    $records = $query->get();

    $data = $records->map(function ($attendance) {
        return [
            'id' => $attendance->user->id,
            'name' => $attendance->user->name,
            'checkIn' => $attendance->check_in,
            'checkOut' => $attendance->check_out,
            'hours' => $attendance->working_hours,
            'date' => $attendance->date,
            'status' => $attendance->status,
        ];
    });

    return response()->json($data);
}


protected function storeFile(Request $request, $field)
{
    if ($request->hasFile($field)) {
        return $request->file($field)->store('uploads/staff', 'public');
    }
    return null;
}





}
