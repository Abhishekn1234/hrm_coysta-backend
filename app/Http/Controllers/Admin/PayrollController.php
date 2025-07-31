<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PDF;

class PayrollController extends Controller
{
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
            'payroll.professional_tax' => 'numeric',
            'payroll.tds' => 'numeric',
            'payroll.loan_recovery' => 'numeric',
            'payroll.total_earnings' => 'numeric',
            'payroll.total_deductions' => 'numeric',
            'payroll.net_pay' => 'numeric',
        ]);

        $data = $validated;

        // Return view (or generate PDF)
        $pdf = PDF::loadView('payroll.slip', compact('data'));
        return $pdf->download('Payroll-Slip.pdf');
    }
}
