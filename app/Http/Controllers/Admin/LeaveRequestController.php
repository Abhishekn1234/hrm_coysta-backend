<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // <-- THIS IS REQUIRED
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    // Apply for leave
    public function apply(Request $request)
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


    // Get all leaves (admin)
   public function getEnums()
{
    // Get column types from the table
    $enumColumns = ['leave_type'];
    $enums = [];

    foreach ($enumColumns as $column) {
        $type = DB::select("SHOW COLUMNS FROM leave_requests WHERE Field = '$column'")[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enumValues = [];

        if (isset($matches[1])) {
            foreach (explode(',', $matches[1]) as $value) {
                $enumValues[] = trim($value, "'");
            }
        }

        $enums[$column] = $enumValues;
    }

    return response()->json($enums);
}

    // Get leave by ID
    public function show($id)
    {
        $leave = LeaveRequest::with('employee')->findOrFail($id);
        return response()->json($leave);
    }

    // Update leave (e.g., status)
    public function update(Request $request, $id)
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
    public function destroy($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->delete();

        return response()->json(['message' => 'Leave deleted']);
    }
}
