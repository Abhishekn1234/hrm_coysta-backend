<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmploymentHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UserController extends Controller
{
    use HasFactory;
    // 🟢 Get all users
    public function index()
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
   public function show($id)
{
    $user = User::with(['employmentHistories', 'departmentInfo'])->find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json($user, 200);
}


    // 🟢 Create a new user
    public function store(Request $request)
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
   public function update(Request $request, $id)
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
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}

