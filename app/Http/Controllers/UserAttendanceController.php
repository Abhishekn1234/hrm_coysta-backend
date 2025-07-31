<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
class UserAttendanceController extends Controller
{
  public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:present,absent,late',  // expects lowercase
        'check_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i',
    ]);

    $user = User::findOrFail($id);

    // Update latest status in users table (optional)
    $user->attendance = $request->status;
    $user->save();

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
public function getDailyLogs($id)
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
public function getAttendanceSummary($id)
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
public function getAllUsersDailyAttendance()
{
    $today = Carbon::now()->toDateString();

    $logs = Attendance::with('user')  // Assumes relation exists
        ->where('date', $today)
        ->get();

    return response()->json([
        'success' => true,
        'date' => $today,
        'records' => $logs
    ]);
}


    // Show current attendance status
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'attendance' => $user->attendance ?? 'not set'
        ]);
    }
}