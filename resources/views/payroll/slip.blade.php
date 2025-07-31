<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pay Slip - {{ $data['employee']['first_name'] }} {{ $data['employee']['last_name'] }}</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            font-size: 14px;
            padding: 20px;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f1f1f1;
        }

        .section-title {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .net-pay {
            font-size: 16px;
            font-weight: bold;
            background: #e6f7ff;
            text-align: right;
            padding: 10px;
            border: 1px solid #007bff;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">
        Salary Slip - {{ \Carbon\Carbon::parse($data['month'])->format('F Y') }}
        {{ $data['employee']['emp_code'] ?? 'EMP' . $data['employee']['id'] }} - 
        {{ $data['employee']['first_name'] }} {{ $data['employee']['last_name'] }}
    </h2>

    <table>
        <tr><th colspan="2" class="section-title">Earnings</th></tr>
        <tr><td>Basic Salary</td><td class="text-right">{{ number_format($data['payroll']['basic_salary'] ?? 0, 2) }}</td></tr>
        <tr><td>HRA</td><td class="text-right">{{ number_format($data['payroll']['hra'] ?? 0, 2) }}</td></tr>
        <tr><td>Transport Allowance</td><td class="text-right">{{ number_format($data['payroll']['transport_allowance'] ?? 0, 2) }}</td></tr>
        <tr><td>Salary Deduction</td><td class="text-right">{{ number_format($data['payroll']['salary_deduction'] ?? 0, 2) }}</td></tr>
        <tr><td><strong>Total Earnings</strong></td><td class="text-right"><strong>{{ number_format($data['payroll']['total_earnings'] ?? 0, 2) }}</strong></td></tr>
    </table>

    <table>
        <tr><th colspan="2" class="section-title">Deductions</th></tr>
        <tr><td>Professional Tax</td><td class="text-right">{{ number_format($data['payroll']['professional_tax'] ?? 0, 2) }}</td></tr>
        <tr><td>TDS</td><td class="text-right">{{ number_format($data['payroll']['tds'] ?? 0, 2) }}</td></tr>
        <tr><td>Loan Recovery</td><td class="text-right">{{ number_format($data['payroll']['loan_recovery'] ?? 0, 2) }}</td></tr>
        <tr><td>Leave Deduction</td><td class="text-right">{{ number_format($data['payroll']['leave_deduction'] ?? 0, 2) }}</td></tr>
        <tr><td><strong>Total Deductions</strong></td><td class="text-right"><strong>{{ number_format($data['payroll']['total_deductions'] ?? 0, 2) }}</strong></td></tr>
    </table>

    <div class="net-pay">
        Net Pay:  {{ number_format($data['payroll']['net_pay'] ?? 0, 2) }}
    </div>

    <div class="footer">
        This is a system-generated payslip. If you have any queries, please contact HR.
    </div>

</body>
</html>
