<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Message;
use App\Models\Benefit;
use App\Models\Attendance;
use App\Models\Designation;
use App\Models\Organization;
use App\Models\LeaveRequest;
use App\Models\EmploymentHistory;
use App\Models\EmployeeDocument;

use App\Notifications\MessageNotification;
class HrmController extends Controller
{
    // 1️⃣ Create a new employee with optional files
    public function storePersonalDetails(Request $request)
    {  
        Log::info('📥 Incoming Request to storePersonalDetails:', $request->all());

        $validated = $request->validate([
            // Personal Info
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'place' => 'nullable|string|max:250',
            'qualification' => 'nullable|string|max:250',
            'experience' => 'nullable|string|max:250',
            'expertise' => 'nullable|string|max:250',
            'hourlyRate' => 'nullable|string|max:250',
            'monthlyRate' => 'nullable|string|max:250',
            'annualCTC' => 'nullable|string|max:250',
            'probationPeriod' => 'nullable|string|max:250',
            'joinType' => 'nullable|in:DIRECT,CANDIDATE',
            'image' => 'nullable|string|max:250',

            // Emergency Contact
            'emergencyContactName' => 'required|string|max:255',
            'emergencyContactRelationship' => 'required|string|max:255',
            'emergencyContactPhone' => 'required|string|max:20',
            'emergencyContactEmail' => 'nullable|email|max:255',

            // Employment
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employmentType' => 'required|in:FULL_TIME,PARTIAL,FREELANCE',
            'hireDate' => 'required|date',
            'reportingManager' => 'required|string|max:255',
            'workLocation' => 'required|string|max:255',
           

            // Salary
            'baseSalary' => 'required|numeric',
             'ptax' => 'nullable|numeric',
                'loan' => 'nullable|numeric',
                'tds'  => 'nullable|numeric',
            'payFrequency' => 'required|string|in:Monthly,MONTHLY,Bi-weekly,BI-WEEKLY,Weekly,WEEKLY',
            'housingAllowance' => 'nullable|numeric',
            'transportAllowance' => 'nullable|numeric',
            'medicalAllowance' => 'nullable|numeric',
            'otherAllowances' => 'nullable|numeric',
              'documentsAuthentic'=>'nullable|boolean',
            // Bank
            'bankName' => 'required|string|max:255',
            'routingNumber' => 'nullable|string|max:255',
            'paymentMethod' => 'nullable|string|max:255',
            'accountNumber' => 'nullable|string|max:255',

            // Documents
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'idProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'employmentContract' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'medicalCertificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'educationCertificates' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            // Employment History
            'positions' => 'nullable|array',
            'positions.*.previousCompany' => 'required_with:positions|string|max:255',
            'positions.*.previousStartDate' => 'required_with:positions|date',
            'positions.*.previousEndDate' => 'required_with:positions|date',
            'positions.*.previousPosition' => 'required_with:positions|string|max:255',
            'positions.*.previousResponsibilities' => 'nullable|string',
        ]);
          Log::info('✅ Validated Data:', $validated);
            $userType = $validated['employmentType'];
                $baseSalary = $validated['baseSalary'];
        try {
            // Store uploaded files
            $resumePath = $request->file('resume')?->store('resumes', 'public');
            $idProofPath = $request->file('idProof')?->store('id_proofs', 'public');
            $contractPath = $request->file('employmentContract')?->store('contracts', 'public');
            $medCertPath = $request->file('medicalCertificate')?->store('medical_certificates', 'public');
            $eduCertPath = $request->file('educationCertificates')?->store('education_certificates', 'public');

            // Create User
            $user = User::create([
                
                'user_type' => 'STAFF',
                'first_name' => $validated['firstName'],
                'last_name' => $validated['lastName'],
                'name' => $validated['firstName'] . ' ' . $validated['lastName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['dob'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'place' => $validated['place'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'expertise' => $validated['expertise'] ?? null,
                'hourly_rate' => $validated['hourlyRate'] ?? null,
                'monthly_rate' => $validated['monthlyRate'] ?? null,
                'annual_ctc' => $validated['annualCTC'] ?? null,
                'probation_period' => $validated['probationPeriod'] ?? null,
                'join_type' => $validated['joinType'] ?? 'DIRECT',
                'image' => $validated['image'] ?? null,

                'emergency_contact_name' => $validated['emergencyContactName'],
                'emergency_contact_relationship' => $validated['emergencyContactRelationship'],
                'emergency_contact_phone' => $validated['emergencyContactPhone'],
                'emergency_contact_email' => $validated['emergencyContactEmail'] ?? null,

                'designation' => $validated['position'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'employment_type' => $validated['employmentType'],
                'hire_date' => $validated['hireDate'],
                'join_date' => $validated['hireDate'],
                'reporting_manager' => $validated['reportingManager'],
                'work_location' => $validated['workLocation'],
                'status' => 1,
                

                 'base_salary' => $userType === 'FREELANCE' ? null : $baseSalary,
                  'basic_salary' => $userType === 'FREELANCE' ? null : $baseSalary,
                'daily_remuneration' => $userType === 'FREELANCE' ? $baseSalary : null,
                'pay_frequency' => $validated['payFrequency'],
                'hra' => $validated['housingAllowance'] ?? 0,
                'transport_allowance' => $validated['transportAllowance'] ?? 0,
                'medical_allowance' => $validated['medicalAllowance'] ?? 0,
                'special_allowances' => $validated['otherAllowances'] ?? 0,
                  'housing_allowance' => $validated['housingAllowance'] ?? 0,
                  'other_allowances'=>$validated['otherAllowances'] ?? 0,
                'bank_name' => $validated['bankName'],
                'account_number' => $validated['accountNumber'] ?? null,
                'routing_number' => $validated['routingNumber'] ?? null,
                'payment_method' => $validated['paymentMethod'] ?? null,
                 'ptax' => $validated['ptax'] ?? 0,
                    'tds' => $validated['tds'] ?? 0,
                    'loan' => $validated['loan'] ?? 0,

                'resume' => $resumePath,
                'id_proof' => $idProofPath,
                'employment_contract' => $contractPath,
                'medical_certificate' => $medCertPath,
                'education_certificates' => $eduCertPath,
                'documents_authentic'=>$validated['documentsAuthentic']
            ]);

            // Save Employment History
            if (!empty($validated['positions'])) {
                foreach ($validated['positions'] as $position) {
                    EmploymentHistory::create([
                        'user_id' => $user->id,
                        'previous_company' => $position['previousCompany'],
                        'previous_start_date' => $position['previousStartDate'],
                        'previous_end_date' => $position['previousEndDate'],
                        'previous_position' => $position['previousPosition'],
                        'previous_responsibilities' => $position['previousResponsibilities'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Employee created successfully',
                'user' => $user,
            ], 201);

        } catch (\Exception $error) {
            \Log::error('Error in storePersonalDetails: ' . $error->getMessage());
            return response()->json([
                'message' => 'Error creating employee',
                'error' => $error->getMessage()
            ], 500);
        }
    }
     public function checkIn(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $userId = $request->input('user_id');
    $today = now()->toDateString();

    $existing = Attendance::where('user_id', $userId)->where('date', $today)->first();

    if ($existing) {
        return response()->json(['message' => 'Already checked in today'], 400);
    }

    Attendance::create([
        'user_id' => $userId,
        'check_in' => now()->format('H:i:s'),
        'date' => $today,
        'status' => 'present'
    ]);

    return response()->json(['message' => 'Checked in successfully']);
}
public function checkOut(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $userId = $request->input('user_id');
    $today = now()->toDateString();

    $attendance = Attendance::where('user_id', $userId)
        ->where('date', $today)
        ->first();

    if (!$attendance || (!$attendance->check_in && $attendance->status !== 'present')) {
        return response()->json(['message' => 'No valid check-in or present status found for today'], 400);
    }

    if ($attendance->check_out) {
        return response()->json(['message' => 'Already checked out'], 400);
    }

    $checkOut = now();

    // If check_in is not set but status is 'present', use the time when present was marked or fallback to now
    if (!$attendance->check_in && $attendance->status === 'present') {
        $checkIn = now(); // or use $attendance->status_time if you store that
        $attendance->check_in = $checkIn->format('H:i:s'); // Save it so future calls are consistent
        $attendance->save();
    } else {
        $checkIn = Carbon::createFromFormat('H:i:s', $attendance->check_in);
    }

    $workingHours = $checkIn->diff($checkOut)->format('%H:%I:%S');

    $attendance->update([
        'check_out' => $checkOut->format('H:i:s'),
        'working_hours' => $workingHours,
    ]);

    return response()->json(['message' => 'Checked out successfully']);
}


    // 2️⃣ Upload Document
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            $user = User::findOrFail($id);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/' . $user->id, $fileName, 'public');

                $document = EmployeeDocument::create([
                    'user_id' => $user->id,
                    'title' => $request->input('title'),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);

                return response()->json([
                    'message' => 'Document uploaded successfully',
                    'document' => $document,
                    'url' => asset('storage/' . $path),
                ], 200);
            }

            return response()->json(['message' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            \Log::error('Upload Document Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Upload failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

     public function generateDocument(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($id);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('documents', 'public');
        }

        $document = EmployeeDocument::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'body' => $request->body,
            'image' => $path,
        ]);

        return response()->json(['message' => 'Document generated successfully', 'document' => $document]);
    }

   
 public function getDocuments($id)
{
    try {
        $user = User::findOrFail($id);

        // Fetch documents from the related EmployeeDocument table
        $employeeDoc = EmployeeDocument::where('user_id', $user->id)->first();

        if (!$employeeDoc) {
            return response()->json(['data' => null]);
        }

        $fileFields = [
            'resume',
            'id_proof',
            'employment_contract',
            'medical_certificate',
            'education_certificates'
        ];

        $documents = [];

        foreach ($fileFields as $field) {
            $documents[$field] = $employeeDoc->$field
                ? asset('storage/' . ltrim($employeeDoc->$field, '/'))
                : null;
        }

        // Add flags from EmployeeDocument
        $documents['file_path']=$employeeDoc->file_path;
        $documents['documents_authentic'] = (bool) $employeeDoc->documents_authentic;
        $documents['suspend'] = (bool) $employeeDoc->suspend;
        $documents['signed_date'] = $employeeDoc->created_at;
        $documents['verified_date'] = $employeeDoc->verified_at;

        return response()->json(['data' => $documents]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to fetch documents',
            'error' => $e->getMessage()
        ], 500);
    }
}



    // 4️⃣ Get all employees
  
public function getEmployees(Request $request)
{
    try {
        $month = $request->query('month'); // optional: format YYYY-MM
        $monthDate = $month ? Carbon::parse($month . '-01') : now();
        $year = $monthDate->year;
        $monthNum = $monthDate->month;

        // Fetch all public holidays in the given month
        $holidays = Holiday::whereMonth('date', $monthNum)
            ->whereYear('date', $year)
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Calculate total working days (excluding Sundays and public holidays)
        $totalWorkingDays = collect(range(1, $monthDate->daysInMonth))->filter(function ($day) use ($monthDate, $holidays) {
            $date = Carbon::create($monthDate->year, $monthDate->month, $day);
            return !$date->isSunday() && !in_array($date->format('Y-m-d'), $holidays);
        })->count();

        $employees = User::with('departmentRelation')->orderByDesc('id')->get();

        $results = [];

        foreach ($employees as $emp) {
            $leaves = LeaveRequest::where('employee_id', $emp->id)
                ->whereMonth('from_date', $monthNum)
                ->whereYear('from_date', $year)
                ->get();

            $totalDaysTaken = 0;
            $leaveTypeCounts = [];

            foreach ($leaves as $leave) {
                $start = Carbon::parse($leave->from_date);
                $end = Carbon::parse($leave->to_date);

                $days = $start->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
                    return !$date->isSunday() && !in_array($date->format('Y-m-d'), $holidays);
                }, $end->copy()->addDay());

                $totalDaysTaken += $days;

                // Grouping leave types
                $type = $leave->leave_type ?? 'Unknown';
                if (!isset($leaveTypeCounts[$type])) {
                    $leaveTypeCounts[$type] = 0;
                }
                $leaveTypeCounts[$type] += $days;
            }

            // Fetch all attendance records for the employee for the month (excluding holidays and Sundays)
            $attendanceRecords = Attendance::where('user_id', $emp->id)
                ->whereMonth('date', $monthNum)
                ->whereYear('date', $year)
                ->get()
                ->filter(function ($record) use ($holidays) {
                    $date = Carbon::parse($record->date);
                    return !$date->isSunday() && !in_array($date->format('Y-m-d'), $holidays);
                });

            $dailyDurations = [];
           foreach ($attendanceRecords as $record) {
    if ($record->working_hours && preg_match('/^\d{2}:\d{2}:\d{2}$/', $record->working_hours)) {
        // Convert HH:MM:SS to seconds
        list($hours, $minutes, $seconds) = explode(':', $record->working_hours);
        $duration = ($hours * 3600) + ($minutes * 60) + $seconds;
        $dailyDurations[] = $duration;
    } elseif ($record->check_in && $record->check_out) {
        $checkIn = Carbon::parse($record->check_in);
        $checkOut = Carbon::parse($record->check_out);
        $duration = $checkOut->diffInSeconds($checkIn);
        $dailyDurations[] = $duration;
    }
}



            // Calculate daily average hours and monthly total
            $totalSeconds = array_sum($dailyDurations);
            $avgSecondsPerDay = count($dailyDurations) ? $totalSeconds / count($dailyDurations) : 0;

            $dailyHoursFormatted = gmdate('H:i', $avgSecondsPerDay);
            $monthlyHoursFormatted = gmdate('H:i', $totalSeconds);

            $results[] = [
                'id' => $emp->id,
                'first_name' => $emp->first_name,
                'last_name' => $emp->last_name,
                'emp_code' => $emp->emp_code ?? 'EMP' . $emp->id,
                'department_relation' => $emp->departmentRelation,
                'position' => $emp->position,
                'status' => $emp->status,
                'daily_hours' => $dailyHoursFormatted,
                'monthly_hours' => $monthlyHoursFormatted,
                'leave_days' => $totalDaysTaken,
                'leaves_taken' => $totalDaysTaken,
                'leave_type_counts' => $leaveTypeCounts,  // <-- added here
                'total_leaves' => $totalWorkingDays,
                'base_salary' => $emp->base_salary,
                'email' => $emp->email,
                'phone' => $emp->phone,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch employees with leave stats',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function getEmployeeLeaveStats(Request $request)
{
    try {
        $month = $request->query('month') ?? now()->format('Y-m'); // default: current month

        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $employees = User::where('user_type', 'STAFF')->with(['leaveRequests' => function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('from_date', [$startOfMonth, $endOfMonth])
              ->orWhereBetween('to_date', [$startOfMonth, $endOfMonth]);
        }])->get();

        $results = [];

        foreach ($employees as $emp) {
            $totalDaysTaken = 0;

            foreach ($emp->leaveRequests as $leave) {
                $from = Carbon::parse($leave->from_date);
                $to = Carbon::parse($leave->to_date);

                $days = 0;
                while ($from->lte($to)) {
                    if (!$from->isSunday()) {
                        $days++;
                    }
                    $from->addDay();
                }

                $totalDaysTaken += $days;
            }

            $totalWorkingDays = 0;
            $dateCursor = $startOfMonth->copy();
            while ($dateCursor->lte($endOfMonth)) {
                if (!$dateCursor->isSunday()) {
                    $totalWorkingDays++;
                }
                $dateCursor->addDay();
            }

            $results[] = [
                'employee_id' => $emp->id,
                'employee_name' => $emp->name,
                'total_leaves_taken' => $totalDaysTaken,
                'working_days' => $totalWorkingDays,
                'leave_percentage' => round(($totalDaysTaken / $totalWorkingDays) * 100, 2) . '%'
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch leave stats',
            'error' => $e->getMessage()
        ], 500);
    }
}
//     public function getEmployeeById($id)
// {
//     try {
//         $user = User::where('user_type', 'STAFF')->findOrFail($id);

//         return response()->json([
//             'id' => $user->id,
//             'name' => $user->name,
//             'employee_code' => $user->employee_code ?? 'EMP' . $user->id,
//             'email' => $user->email,
//             'phone' => $user->phone,
//             'position' => $user->position,
//             'department' => $user->department,
//             'hire_date' => $user->hire_date,
//             // Add more fields as needed
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'Employee not found',
//             'error' => $e->getMessage(),
//         ], 404);
//     }
// }
public function getEmployeeById($id)
{
    try {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => 'User ID ' . $id . ' does not exist',
            ], 404);
        }

        // Count Casual Leave and Sick Leave
        $casualLeaveCount = DB::table('leave_requests')
            ->where('employee_id', $id)
            ->where('leave_type', 'Casual Leave')
            ->count();

        $sickLeaveCount = DB::table('leave_requests')
            ->where('employee_id', $id)
            ->where('leave_type', 'Sick Leave')
            ->count();

        // Get attendance records
        $attendanceRecords = DB::table('attendances')
            ->where('user_id', $id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'user' => $user,
            'leave_type_counts' => [
                'Casual Leave' => $casualLeaveCount,
                'Sick Leave' => $sickLeaveCount,
            ],
            'attendance' => $attendanceRecords
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to fetch employee data',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function employeeStats()
{
    $now = Carbon::now();
    $startOfThisMonth = $now->copy()->startOfMonth();
    $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
    $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

    $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
    $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

    // ✅ Basic user stats
    $totalUsers = User::count();
    $totalActiveUsers = User::where('status', 1)->count();
    $newUsersThisMonth = User::whereBetween('created_at', [$startOfThisMonth, $now])->count();
    $newUsersLastMonth = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
    $userGrowth = $newUsersThisMonth - $newUsersLastMonth;
    $activeUsersThisMonth = User::where('status', 1)->whereBetween('created_at', [$startOfThisMonth, $now])->count();
    $activeUsersLastMonth = User::where('status', 1)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
    $activeGrowth = $activeUsersThisMonth - $activeUsersLastMonth;

    // ✅ Leave requests stats
    $totalLeavesThisMonth = LeaveRequest::whereMonth('from_date', $now->month)
        ->whereYear('from_date', $now->year)
        ->count();

    $approvedLeavesThisMonth = LeaveRequest::where('status', 'APPROVED')
        ->whereMonth('from_date', $now->month)
        ->whereYear('from_date', $now->year)
        ->count();

    $pendingLeavesThisMonth = LeaveRequest::where('status', 'PENDING')
        ->whereMonth('from_date', $now->month)
        ->whereYear('from_date', $now->year)
        ->count();

    $rejectedLeavesThisMonth = LeaveRequest::where('status', 'REJECTED')
        ->whereMonth('from_date', $now->month)
        ->whereYear('from_date', $now->year)
        ->count();
                // 👇 Get ENUM values for 'position'
           $column = DB::select("SHOW COLUMNS FROM users WHERE Field = 'position'")[0]->Type;
            preg_match('/enum\((.*)\)/', $column, $matches);
            $allPositions = array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));

            $filledPositions = User::whereNotNull('position')->distinct()->pluck('position')->toArray();
            $openPositions = array_diff($allPositions, $filledPositions);

    // ✅ Users expected to return this week
    $returningThisWeek = LeaveRequest::whereBetween('to_date', [$startOfWeek, $endOfWeek])
        ->where('status', 'APPROVED')
        ->distinct('employee_id')
        ->count('employee_id');

    return response()->json([
        'total_users' => $totalUsers,
        'active_users' => $totalActiveUsers,
        'new_users_this_month' => $newUsersThisMonth,
        'new_users_last_month' => $newUsersLastMonth,
        'user_growth' => $userGrowth,
        'active_users_this_month' => $activeUsersThisMonth,
        'active_users_last_month' => $activeUsersLastMonth,
        'active_growth' => $activeGrowth,

        // 👇 Leave stats
        'total_leaves_this_month' => $totalLeavesThisMonth,
        'approved_leaves' => $approvedLeavesThisMonth,
        'pending_leaves' => $pendingLeavesThisMonth,
        'rejected_leaves' => $rejectedLeavesThisMonth,
         'available_positions' => count($allPositions),
            'filled_positions' => count($filledPositions),
            'open_positions' => count($openPositions),
            'open_position_list' => array_values($openPositions), // optional

        // 👇 Returning this week
        'users_returning_this_week' => $returningThisWeek
    ]);
}

public function getDepartmentDistribution()
{
    // Get total number of users with department
    $totalUsers = DB::table('users')
        ->whereNotNull('department')
        ->count();

    // Join with departments table to get department names
    $departments = DB::table('users')
        ->join('departments', 'users.department', '=', 'departments.id')
        ->select('departments.department_name as department_name', DB::raw('count(*) as count'))
        ->whereNotNull('users.department')
        ->groupBy('users.department', 'departments.department_name')
        ->get()
        ->map(function ($dept) use ($totalUsers) {
            $dept->percentage = round(($dept->count / $totalUsers) * 100, 1);
            return $dept;
        });

    return response()->json($departments);
}

public function suspend($id)
{
    $employee = User::find($id);
   
    if (!$employee) {
        return response()->json(['message' => 'Employee not found.'], 404);
    }

    $employee->suspend = 1;
    $employee->save();

    return response()->json([
        'message' => 'Employee suspended successfully.',
        'suspend' => $employee->suspend
    ]);
}

public function unsuspend($id)
{
    $employee = User::find($id);

    if (!$employee) {
        return response()->json(['message' => 'Employee not found.'], 404);
    }

    \Log::info("Before unsuspend: " . $employee->suspend);
    $employee->suspend = false;
    $employee->save();
    \Log::info("After unsuspend: " . $employee->suspend);

    return response()->json([
        'message' => 'Employee unsuspended successfully.',
        'suspend' => $employee->suspend
    ]);
}

// public function getAvailablePositions()
// {
//     // This fetches ENUM values from the `position` column in `users` table
//     $type = DB::select("SHOW COLUMNS FROM users WHERE Field = 'position'")[0]->Type;


//     preg_match('/enum\((.*)\)/', $type, $matches);
//     $enum = [];

//     foreach (explode(',', $matches[1]) as $value) {
//         $enum[] = trim($value, " '");
//     }

//     return response()->json([
//         'positions' => $enum
//     ]);
// }
public function getAvailablePositions()
{
    $columnInfo = DB::select("SHOW COLUMNS FROM users WHERE Field = 'position'");
    $type = $columnInfo[0]->Type;

    $enum = [];

    if (preg_match('/enum\((.*)\)/', $type, $matches) && isset($matches[1])) {
        foreach (explode(',', $matches[1]) as $value) {
            $enum[] = trim($value, " '");
        }
    }

    return response()->json([
        'positions' => $enum
    ]);
}

public function getEnums()
{
    $columns = [
        'employment_type',
        'gender',
        'work_location',
        'pay_frequency',
        'reporting_manager'
    ];

    $mapToCamel = [
        'employment_type' => 'employmentType',
        'gender' => 'gender',
        'work_location' => 'workLocation',
        'pay_frequency' => 'payFrequency',
        'reporting_manager' => 'reportingManager'
    ];

    $enums = [];

    foreach ($columns as $column) {
        $type = DB::select("SHOW COLUMNS FROM users WHERE Field = '{$column}'")[0]->Type;

        preg_match('/enum\((.*)\)/', $type, $matches);

        $enums[$mapToCamel[$column]] = array_map(function ($value) {
            return trim($value, " '");
        }, explode(',', $matches[1]));
    }

    return response()->json($enums);
}


public function getCurrentUser()
{
    $user = Auth::user();

    return response()->json([
        'name' => $user->name,
        'user_type' => $user->user_type,
    ]);
}
public function getBenefit($userId)
    {
        $benefit = Benefit::where('user_id', $userId)->first();
        return $benefit
            ? response()->json($benefit)
            : response()->json(['message' => 'Benefit not found'], 404);
    }
    public function getBenefits()
    {
                $benefits = Benefit::all();

                return $benefits->isEmpty()
                    ? response()->json(['message' => 'No benefits found'], 404)
                    : response()->json($benefits);
    }


    // ✅ Add a new benefit record
    public function addBenefit(Request $request)
    {
        $benefit = Benefit::create($request->all());
        return response()->json($benefit, 201);
    }

    // ✅ Edit existing benefit for a user
    public function editBenefit(Request $request, $userId)
    {
        $benefit = Benefit::where('user_id', $userId)->first();
        if (!$benefit) {
            return response()->json(['message' => 'Benefit not found'], 404);
        }

        $benefit->update($request->all());
        return response()->json($benefit);
    }

    // ✅ Delete benefit for a user
    public function deleteBenefit($userId)
    {
        $benefit = Benefit::where('user_id', $userId)->first();
        if (!$benefit) {
            return response()->json(['message' => 'Benefit not found'], 404);
        }

        $benefit->delete();
        return response()->json(['message' => 'Benefit deleted successfully']);
    }
   

public function exportBenefitsToPDF()
{
    $benefits = Benefit::all(); // Or your logic to get benefits with users

    $pdf = Pdf::loadView('pdf.benefits', compact('benefits'));

    return $pdf->download('benefits-summary.pdf');
}
public function bulkUpdateBenefits(Request $request)
{
    $updates = $request->input('benefits'); // Expected to be an array of benefit updates

    foreach ($updates as $item) {
        Benefit::where('id', $item['id'])->update([
            'value' => $item['value'],
            'remarks' => $item['remarks'] ?? null
        ]);
    }

    return response()->json(['message' => 'All benefits updated successfully.']);
}
public function index()
    {
        $organizations = Organization::all();
        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    public function create()
    {
        return response()->json([
            'message' => 'Create endpoint not required for API'
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $organization = Organization::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Organization created successfully.',
            'data' => $organization
        ], 201);
    }

    public function show(Organization $organization)
    {
        return response()->json([
            'success' => true,
            'data' => $organization
        ]);
    }

    public function edit(Organization $organization)
    {
        return response()->json([
            'message' => 'Edit endpoint not required for API'
        ], 200);
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $organization->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Organization updated successfully.',
            'data' => $organization
        ]);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return response()->json([
            'success' => true,
            'message' => 'Organization deleted successfully.'
        ]);
    }
public function holidays()
{
    return response()->json(Holiday::all());
}

// Create a holiday
public function storeHoliday(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'date' => 'required|date',
        'type' => 'required|in:national,festive,labour_day,regional,optional,sunday',
        'description' => 'nullable|string'
    ]);

    $holiday = Holiday::create($validated);
    return response()->json(['message' => 'Holiday added.', 'data' => $holiday], 201);
}

// Show single holiday
public function showHoliday($id)
{
    $holiday = Holiday::findOrFail($id);
    return response()->json($holiday);
}

// Update holiday
public function updateHoliday(Request $request, $id)
{
    $holiday = Holiday::findOrFail($id);

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'date' => 'sometimes|required|date',
        'type' => 'sometimes|required|in:national,festive,labour_day,regional,optional,sunday',
        'description' => 'nullable|string'
    ]);

    $holiday->update($validated);
    return response()->json(['message' => 'Holiday updated.', 'data' => $holiday]);
}

// Delete holiday
public function deleteHoliday($id)
{
    $holiday = Holiday::findOrFail($id);
    $holiday->delete();
    return response()->json(['message' => 'Holiday deleted.']);
}
public function index_organization()
{
    // Remove duplicate organizations by name
    $organizations = Organization::all()->unique('name')->values();

    // Get all users
    $users = User::all();

    // Get only CEO users globally (not per organization)
    $ceos = $users->filter(function ($user) {
        return strtolower($user->user_type) === 'ceo';
    })->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'organization' => $user->organization,
            'position' => $user->position,
        ];
    })->values();

    // Map organizations to their non-CEO users
    $result = $organizations->map(function ($org) use ($users) {
        $matchedUsers = $users->filter(function ($user) use ($org) {
            return $user->organization === $org->name && strtolower($user->user_type) !== 'ceo';
        })->unique('id')->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'position' => $user->position,
            ];
        })->values();

        return [
            'organization_id' => $org->id,
            'organization_name' => $org->name,
            'users' => $matchedUsers,
        ];
    });

    // Final response: organizations + global CEOs separately
    return response()->json([
        'organizations' => $result,
        'ceos' => $ceos
    ]);
}


public function designation_list()
    {
        return Designation::all();
    }

    // POST /designation/add
    public function designation_add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:designations,name|max:255',
        ]);

        $designation = Designation::create(['name' => $request->name]);

        return response()->json([
            'message' => 'Designation created successfully',
            'designation' => $designation
        ]);
    }

    // PUT /designation/update/{id}
    public function designation_update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id,
        ]);

        $oldName = $designation->name;

        $designation->update(['name' => $request->name]);

        DB::table('users')
            ->where('designation', $oldName)
            ->update(['designation' => $request->name]);

        return response()->json(['message' => 'Designation updated successfully']);
    }

    // DELETE /designation/delete/{id}
    public function designation_delete($id)
    {
        $designation = Designation::findOrFail($id);

        DB::table('users')
            ->where('designation', $designation->name)
            ->update(['designation' => null]);

        $designation->delete();

        return response()->json(['message' => 'Designation deleted successfully']);
    }
   public function storeAttandance(Request $request, $userId)
{
    $request->validate([
        'status' => 'required|in:present,absent,late',
        'check_in' => 'nullable|date',
        'check_out' => 'nullable|date',
    ]);

    // Automatically set check-in to now if status is present and no check-in provided
    $checkIn = ($request->status === 'present' && empty($request->check_in))
        ? Carbon::now()
        : ($request->check_in ? Carbon::parse($request->check_in) : null);

    $checkOut = $request->check_out ? Carbon::parse($request->check_out) : null;

    // Calculate working hours in decimal format
    $workingHours = ($checkIn && $checkOut)
        ? round($checkOut->floatDiffInHours($checkIn), 2)
        : 0;

    $attendance = Attendance::updateOrCreate(
        ['user_id' => $userId, 'date' => now()->toDateString()],
        [
            'status' => $request->status,
            'check_in' => $checkIn ? $checkIn->format('H:i:s') : null,
            'check_out' => $checkOut ? $checkOut->format('H:i:s') : null,
            'working_hours' => $workingHours,
        ]
    );

    return response()->json([
        'success' => true,
        'data' => [
            'user_id' => $userId,
            'date' => $attendance->date,
            'status' => ucfirst($attendance->status),
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'working_hours' => $workingHours,
        ]
    ]);
}


    // Get monthly summary
    public function monthlySummary(Request $request, $userId)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $present = Attendance::where('user_id', $userId)->where('status', 'present')
            ->whereMonth('date', $month)->whereYear('date', $year)->count();

        $absent = Attendance::where('user_id', $userId)->where('status', 'absent')
            ->whereMonth('date', $month)->whereYear('date', $year)->count();

        $late = Attendance::where('user_id', $userId)->where('status', 'late')
            ->whereMonth('date', $month)->whereYear('date', $year)->count();

        $totalDays = $present + $absent + $late;
        $percentage = $totalDays > 0 ? round(($present / $totalDays) * 100, 2) : 0;

        return response()->json([
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total_days' => $totalDays,
            'attendance_percentage' => $percentage
        ]);
    }
   


public function getUserAttendance($userId)
{
    try {
        // Step 1: Fetch user with default working hours
        $user = User::with('attendances')->findOrFail($userId);
        $standardHours = $user->working_hours ?? 8.5;

        // Step 2: Fetch and process attendance
        $records = Attendance::where('user_id', $userId)
            ->with('user')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($record) use ($standardHours) {
                try {
                    $checkIn = $record->check_in ? Carbon::parse($record->check_in) : null;
                    $checkOut = $record->check_out ? Carbon::parse($record->check_out) : null;

                    $totalHours = 0;

                    if ($checkIn && $checkOut) {
                        $diff = $checkOut->diffInMinutes($checkIn) / 60; // Convert minutes to hours
                        $totalHours = round(min($diff, 11), 2); // Max 11 hours per day
                    }

                    // Calculate percentage based on standardHours (8.5)
                    $dailyPercentage = round(min(($totalHours / $standardHours) * 100, 100), 1);

                    return [
                        'user_id' => $record->user_id,
                        'user_name' => $record->user->name ?? 'N/A',
                        'date' => $record->date,
                        'check_in' => $checkIn ? $checkIn->format('h:i A') : null,
                        'check_out' => $checkOut ? $checkOut->format('h:i A') : null,
                        'status' => ucfirst($record->status),
                        'total_hours' => $totalHours,
                        'daily_percentage' => $dailyPercentage . '%',
                        'working_hours' => $record->working_hours ?? $standardHours,
                    ];
                } catch (\Exception $e) {
                    return [
                        'user_id' => $record->user_id,
                        'user_name' => $record->user->name ?? 'N/A',
                        'date' => $record->date,
                        'check_in' => $record->check_in,
                        'check_out' => $record->check_out,
                        'status' => 'Error',
                        'total_hours' => 0,
                        'daily_percentage' => '0%',
                        'working_hours' => $standardHours,
                        'error' => $e->getMessage(),
                    ];
                }
            });

        return response()->json([
            'success' => true,
            'user_name' => $user->name,
            'standard_hours' => $standardHours,
            'attendance_count' => $records->count(),
            'data' => $records,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch attendance data.',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function exportAttendance(Request $request)
{
    $records = $request->input('records');

    if (!$records || !is_array($records)) {
        return response()->json(['error' => 'Invalid or missing records'], 422);
    }

    // Generate PDF using a Blade view
    $pdf = Pdf::loadView('pdf.attendance', ['records' => $records]);

    // You can stream it directly
    return $pdf->download('attendance-report.pdf');
}
   public function updateAttandance(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:present,absent,late',
        'check_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i',
    ]);

    $user = User::findOrFail($id);

    // ✅ Update status in `users` table
    $user->attendance = $request->status;
    $user->save();

    // ✅ Insert or update attendance log for today
    $today = Carbon::now()->toDateString();

    $attendance = Attendance::updateOrCreate(
        ['user_id' => $id, 'date' => $today],
        [
            'status' => $request->status,
            'check_in' => $request->check_in ?? null,
            'check_out' => $request->check_out ?? null,
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Attendance status updated and logged successfully',
        'user' => $user,
        'attendance' => $attendance,
    ]);
}


    // Show current attendance status
    public function showAttandance($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'attendance' => $user->attendance ?? 'not set'
        ]);
    }  

    public function getPayrollDetails($id)
{
    $employee = User::find($id);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    // Get current month days (for per-day salary calc)
    $currentMonth = now()->month;
    $daysInMonth = now()->daysInMonth;

    // Parse salary components with fallback
    $basic = (float) ($employee->base_salary ?? 0);
    $hra = (float) ($employee->housing_allowance ?? 0);
    $transport = (float) ($employee->transport_allowance ?? 0);
    $medical = (float) ($employee->medical_allowance ?? 0);
    $other = (float) ($employee->other_allowances ?? 0);

    // Calculate per-day basic salary using actual days in month (e.g., 31 for July)
    $perDaySalary = $basic > 0 ? $basic / $daysInMonth : 0;

    // Get unpaid leave days (Approved status)
    $leaveDays = DB::table('leave_requests')
        ->where('employee_id', $employee->id)
      
      
        ->whereMonth('from_date', $currentMonth)
        ->sum(DB::raw('DATEDIFF(to_date, from_date) + 1'));

    $leaveDays = (int) $leaveDays;

    // Leave deduction
    $leaveDeduction = $leaveDays * $perDaySalary;

    // Dynamic deductions from users table
    $ptax = (float) ($employee->ptax ?? 0);
    $tds = (float) ($employee->tds ?? 0);
    $loan = (float) ($employee->loan ?? 0);

    // Total earnings
    $totalEarnings = $basic + $hra + $transport + $medical + $other;

    // Total deductions
    $totalDeductions = $ptax + $tds + $loan + $leaveDeduction;

    // Net Pay
    $netPay = $totalEarnings - $totalDeductions;
     $leavesd=$basic-$leaveDeduction;
    return response()->json([
        'month_days' => $daysInMonth,
        'basic_salary' => round($basic, 2),
        'hra' => round($hra, 2),
        'transport_allowance' => round($transport, 2),
        'medical_allowance' => round($medical, 2),
        'other_allowances' => round($other, 2),

        'per_day_salary' => round($perDaySalary, 2),
        'leave_days' => $leaveDays,
        'leave_deduction' => round($leaveDeduction, 2),
         'salary_deduction'=>round($leavesd,2),
        'professional_tax' => round($ptax, 2),
        'tds' => round($tds, 2),
        'loan_recovery' => round($loan, 2),

        'total_earnings' => round($totalEarnings, 2),
        'total_deductions' => round($totalDeductions, 2),

        'net_pay' => round($netPay, 2),
    ]);
}

 public function generateSlip(Request $request)
    {
        $data = $request->all();

        $pdf = PDF::loadView('pdf.payroll_slip', compact('data'))
                  ->setPaper('a4', 'portrait');

        $fileName = "Payroll_{$data['employee']['first_name']}_{$data['payrollMonth']}.pdf";

        return $pdf->download($fileName);
    }
    public function printSlip(Request $request)
{
    $validated = $request->validate([
        'employee.first_name' => 'required|string',
        'employee.last_name' => 'required|string',
        'employee.emp_code' => 'nullable|string',
        'employee.id' => 'required|integer',
        'month' => 'required|date',

        'payroll.basic_salary' => 'numeric',
        'payroll.hra' => 'numeric',
        'payroll.transport_allowance' => 'numeric',
        'payroll.other_allowances' => 'numeric',         // ✅ Added here
        'payroll.professional_tax' => 'numeric',
        'payroll.tds' => 'numeric',
        'payroll.loan_recovery' => 'numeric',
        'payroll.leave_deduction' => 'numeric',
        'payroll.salary_deduction' => 'numeric',

        'payroll.total_earnings' => 'numeric',
        'payroll.total_deductions' => 'numeric',
        'payroll.net_pay' => 'numeric',
    ]);

    $data = $validated;

    // ✅ Log the entire payroll structure
    \Log::info('Payroll Data:', [
        'employee' => $data['employee'],
        'payroll' => $data['payroll'],
        'month' => $data['month'],
    ]);

    // ✅ Generate the PDF
    $pdf = PDF::loadView('payroll.slip', compact('data'));
    return $pdf->download('Payroll-Slip.pdf');
}





public function getUser()
    {
        return response()->json(User::all(), 200);
    }
   public function employmentHistories()
{
    return $this->hasMany(EmploymentHistory::class, 'user_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    // 🟢 Get single user by ID
   public function showUser($id)
{
    $user = User::with(['employmentHistories', 'departmentInfo'])->find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json($user, 200);
}


    // 🟢 Create a new user
    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:250',
            'user_type' => 'nullable|string',
            'place' => 'nullable|string|max:250',
            'address' => 'nullable|string',
            'gender' => 'nullable|string|max:250',
            'hourly_rate' => 'nullable|string|max:250',
            'monthly_rate' => 'nullable|string|max:250',
            'date_of_birth' => 'nullable|date',
            'qualification' => 'nullable|string|max:250',
            'experience' => 'nullable|string|max:250',
            'expertise' => 'nullable|string|max:250',
            'designation' => 'nullable|string|max:250',
            'role' => 'nullable|string',
            'image' => 'nullable|string',
            'reports_to' => 'nullable|numeric',
            'status' => 'nullable|numeric',
            'join_type' => 'nullable|string',
            'join_date' => 'nullable|date',
            'work_location' => 'nullable|string|max:250',
            'employment_type' => 'nullable|string',
            'annual_ctc' => 'nullable|string|max:250',
            'basic_salary' => 'nullable|string|max:250',
            'hra' => 'nullable|string|max:250',
            'special_allowances' => 'nullable|string|max:250',
            'probation_period' => 'nullable|string|max:250',
            'bank_name' => 'required|string|max:250',
            'account_holder_name' => 'nullable|string|max:244',
            'account_number' => 'nullable|string|max:244',
            'ifsc_code' => 'required|string|max:250',
            'branch' => 'required|string|max:250',
            'emergency_contact_name' => 'nullable|string|max:191',
            'emergency_contact_relationship' => 'nullable|string|max:191',
            'emergency_contact_phone' => 'nullable|string|max:191',
            'emergency_contact_email' => 'nullable|email|max:191',
            'position' => 'nullable|string|max:191',
            'department' => 'nullable|string|max:191',
            'hire_date' => 'nullable|date',
            'reporting_manager' => 'nullable|string|max:191',
            'previous_company' => 'nullable|string|max:191',
            'previous_start_date' => 'nullable|date',
            'previous_end_date' => 'nullable|date',
            'previous_position' => 'nullable|string|max:191',
            'previous_responsibilities' => 'nullable|string',
            'base_salary' => 'nullable|numeric',
            'pay_frequency' => 'nullable|string|max:191',
            'housing_allowance' => 'nullable|numeric',
            'transport_allowance' => 'nullable|numeric',
            'medical_allowance' => 'nullable|numeric',
            'other_allowances' => 'nullable|numeric',
            'routing_number' => 'nullable|string|max:191',
            'payment_method' => 'nullable|string|max:191',
            'resume' => 'nullable|string|max:191',
            'id_proof' => 'nullable|string|max:191',
            'employment_contract' => 'nullable|string|max:191',
            'medical_certificate' => 'nullable|string|max:191',
            'education_certificates' => 'nullable|string|max:191',
            'documents_authentic' => 'nullable|boolean',
        ]);

        $user = User::create($validated);
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // 🟢 Update user
   public function updateEmployee(Request $request, $id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Rebuild positions array from FormData format
    $positions = [];
    foreach ($request->all() as $key => $value) {
        if (preg_match('/^positions\[(\d+)\]\[(.+?)\]$/', $key, $matches)) {
            $index = $matches[1];
            $field = $matches[2];
            $positions[$index][$field] = $value;
        }
    }
    $request->merge(['positions' => $positions]);

    // Validate the fields
    $validated = $request->validate([
        'first_name' => 'sometimes|string|max:191',
        'last_name' => 'sometimes|string|max:191',
        'name' => 'sometimes|string|max:191',
        'email' => 'nullable|email|unique:users,email,' . $id,
        'phone' => 'nullable|string|max:250',
        'user_type' => 'nullable|string',
        'place' => 'nullable|string|max:250',
        'address' => 'nullable|string',
        'gender' => 'nullable|string|max:250',
        'hourly_rate' => 'nullable|string|max:250',
        'monthly_rate' => 'nullable|string|max:250',
        'date_of_birth' => 'nullable|date',
        'qualification' => 'nullable|string|max:250',
        'experience' => 'nullable|string|max:250',
        'expertise' => 'nullable|string|max:250',
        'designation' => 'nullable|string|max:250',
        'role' => 'nullable|string',
        'image' => 'nullable|string',
        'reports_to' => 'nullable|numeric',
        'status' => 'nullable|numeric',
        'join_type' => 'nullable|string',
        'join_date' => 'nullable|date',
        'work_location' => 'nullable|string|max:250',
        'employment_type' => 'nullable|string',
        'annual_ctc' => 'nullable|string|max:250',
        'basic_salary' => 'nullable|string|max:250',
        'hra' => 'nullable|string|max:250',
        'special_allowances' => 'nullable|string|max:250',
        'probation_period' => 'nullable|string|max:250',
        'bank_name' => 'sometimes|string|max:250',
        'account_holder_name' => 'nullable|string|max:244',
        'account_number' => 'nullable|string|max:244',
        'ifsc_code' => 'sometimes|string|max:250',
        'branch' => 'sometimes|string|max:250',
        'emergency_contact_name' => 'nullable|string|max:191',
        'emergency_contact_relationship' => 'nullable|string|max:191',
        'emergency_contact_phone' => 'nullable|string|max:191',
        'emergency_contact_email' => 'nullable|email|max:191',
        'position' => 'nullable|string|max:191',
        'department' => 'nullable|integer|exists:departments,id',
        'hire_date' => 'nullable|date',
        'reporting_manager' => 'nullable|string|max:191',
        'base_salary' => 'nullable|numeric',
        'pay_frequency' => 'nullable|string|max:191',
        'housing_allowance' => 'nullable|numeric',
        'transport_allowance' => 'nullable|numeric',
        'medical_allowance' => 'nullable|numeric',
        'other_allowances' => 'nullable|numeric',
        'routing_number' => 'nullable|string|max:191',
        'payment_method' => 'nullable|string|max:191',
        'documents_authentic' => 'nullable|boolean',
         'ptax' => 'nullable|numeric',
            'tds' => 'nullable|numeric',
            'loan' => 'nullable|numeric',

            'resume' => 'nullable|string|max:191',
            'id_proof' => 'nullable|string|max:191',
            'employment_contract' => 'nullable|string|max:191',
            'medical_certificate' => 'nullable|string|max:191',
            'education_certificates' => 'nullable|string|max:191',


        // 🔹 Employment History
        'positions' => 'nullable|array',
        'positions.*.id' => 'nullable|exists:employment_histories,id',
        'positions.*.previousCompany' => 'required_with:positions|string|max:255',
        'positions.*.previousStartDate' => 'required_with:positions|date',
        'positions.*.previousEndDate' => 'required_with:positions|date',
        'positions.*.previousPosition' => 'required_with:positions|string|max:255',
        'positions.*.previousResponsibilities' => 'nullable|string',
    ]);

    // Update user
    $user->update($validated);

    // Update/Add Employment Histories
    if (!empty($validated['positions'])) {
        foreach ($validated['positions'] as $position) {
            if (isset($position['id'])) {
                // Update existing history
                $history = EmploymentHistory::where('user_id', $user->id)
                                            ->where('id', $position['id'])
                                            ->first();
                if ($history) {
                    $history->update([
                        'previous_company' => $position['previousCompany'],
                        'previous_start_date' => $position['previousStartDate'],
                        'previous_end_date' => $position['previousEndDate'],
                        'previous_position' => $position['previousPosition'],
                        'previous_responsibilities' => $position['previousResponsibilities'] ?? null,
                    ]);
                }
            } else {
                // Create new history
                EmploymentHistory::create([
                    'user_id' => $user->id,
                    'previous_company' => $position['previousCompany'],
                    'previous_start_date' => $position['previousStartDate'],
                    'previous_end_date' => $position['previousEndDate'],
                    'previous_position' => $position['previousPosition'],
                    'previous_responsibilities' => $position['previousResponsibilities'] ?? null,
                ]);
            }
        }
    }

    return response()->json(['message' => 'User updated successfully', 'user' => $user->load('employmentHistories')], 200);
}


    // 🟢 Delete user
    public function destroyUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }


    public function getPriorityEnums()
{
    $result = DB::select("SHOW COLUMNS FROM messages WHERE Field = 'priority'");
    $type = $result[0]->Type;

    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $values = [];

    if (isset($matches[1])) {
        foreach (explode(',', $matches[1]) as $value) {
            $val = trim($value, "'");
            $values[] = [
                'value' => $val,
                'label' => ucfirst($val) // Capitalize for label
            ];
        }
    }

    return response()->json([
        'priorities' => $values
    ]);
}
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|string|max:50',
        ]);

        $message = Message::create($validated);

        $recipient = User::find($validated['user_id']);
        $recipient->notify(new MessageNotification($message));

        return response()->json(['status' => 'Message sent and notification created'], 200);
    }



    public function applyLeave(Request $request)
{
    \Log::info('Incoming leave request', $request->all()); // <--- log incoming data

    $validated = $request->validate([
        'employee_id' => 'required|exists:users,id',
        'leave_type' => 'required|string',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'reason' => 'nullable|string',
        'is_emergency' => 'boolean',
        'status' => 'in:PENDING,APPROVED,REJECTED'
    ]);

    $leave = LeaveRequest::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Leave request submitted successfully.',
        'leave' => $leave
    ]);
}
public function getLeave(){
     $leaves = LeaveRequest::with(['employee'])->get();
        return response()->json($leaves);
}

    // Get all leaves (admin)
//    public function getEnums()
// {
//     // Get column types from the table
//     $enumColumns = ['leave_type'];
//     $enums = [];

//     foreach ($enumColumns as $column) {
//         $type = DB::select("SHOW COLUMNS FROM leave_requests WHERE Field = '$column'")[0]->Type;

//         preg_match('/^enum\((.*)\)$/', $type, $matches);
//         $enumValues = [];

//         if (isset($matches[1])) {
//             foreach (explode(',', $matches[1]) as $value) {
//                 $enumValues[] = trim($value, "'");
//             }
//         }

//         $enums[$column] = $enumValues;
//     }

//     return response()->json($enums);
// }

    // Get leave by ID
    public function showLeave($id)
    {
        $leave = LeaveRequest::with('employee')->findOrFail($id);
        return response()->json($leave);
    }
public function getLeavesByUser($userId)
{
    $leaves = LeaveRequest::where('employee_id', $userId)->orderByDesc('from_date')->get();

    return response()->json([
        'employee_id' => $userId,
        'leaves' => $leaves
    ]);
}
// Update or create today's attendance for a specific user
public function updateAttendanceForUser(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:present,absent,late',
        'check_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i',
    ]);

    $user = User::findOrFail($id);

    // Update attendance status in user table
    $user->attendance = $request->status;
    $user->save();

    $today = Carbon::now()->toDateString();
    $currentTime = Carbon::now()->format('H:i');

    // Determine check_in value
    $checkIn = $request->check_in;

    if ($request->status === 'present' && !$checkIn) {
        $checkIn = $currentTime; // Automatically set current time if not provided
    }

    $attendance = Attendance::updateOrCreate(
        ['user_id' => $id, 'date' => $today],
        [
            'status' => $request->status,
            'check_in' => $checkIn,
            'check_out' => $request->check_out ?? null,
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Attendance status updated and logged successfully',
        'user' => $user,
        'attendance' => $attendance,
    ]);
}

// Get the last 30 daily attendance logs of a user
public function getUserDailyLogs($id)
{
    $logs = Attendance::where('user_id', $id)
        ->orderBy('date', 'desc')
        ->limit(30)
        ->get();

    return response()->json([
        'success' => true,
        'logs' => $logs
    ]);
}

// Get attendance summary (present/absent/late) for a specific user
public function getUserAttendanceSummary($id)
{
    $total = Attendance::where('user_id', $id)->count();

    if ($total === 0) {
        return response()->json([
            'success' => true,
            'message' => 'No attendance records found.',
            'summary' => [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'total' => 0,
                'percentages' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                ],
            ],
        ]);
    }

    $present = Attendance::where('user_id', $id)->where('status', 'present')->count();
    $absent = Attendance::where('user_id', $id)->where('status', 'absent')->count();
    $late = Attendance::where('user_id', $id)->where('status', 'late')->count();

    return response()->json([
        'success' => true,
        'summary' => [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'percentages' => [
                'present' => round(($present / $total) * 100, 2),
                'absent' => round(($absent / $total) * 100, 2),
                'late' => round(($late / $total) * 100, 2),
            ],
        ],
    ]);
}

// Get today's attendance records for all users
public function getAllUsersTodayAttendance()
{
    $today = Carbon::now()->toDateString();

    $logs = Attendance::with('user') // Assumes Attendance has `user()` relation
        ->where('date', $today)
        ->get();

    return response()->json([
        'success' => true,
        'date' => $today,
        'records' => $logs
    ]);
}

// Show the current (latest) attendance status from users table
public function getCurrentAttendanceStatus($id)
{
    $user = User::findOrFail($id);

    return response()->json([
        'attendance' => $user->attendance ?? 'not set'
    ]);
}

    // Update leave (e.g., status)
    public function updateLeave(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $validated = $request->validate([
            'leave_type' => 'sometimes|string',
            'from_date' => 'sometimes|date',
            'to_date' => 'sometimes|date|after_or_equal:from_date',
            'reason' => 'nullable|string',
            'is_emergency' => 'boolean',
            'status' => 'in:PENDING,APPROVED,REJECTED'
        ]);

        $leave->update($validated);

        return response()->json(['message' => 'Leave updated', 'leave' => $leave]);
    }

    // Delete leave
    public function destroyLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->delete();

        return response()->json(['message' => 'Leave deleted']);
    }
}
