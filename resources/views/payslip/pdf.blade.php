<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payslip</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
      padding: 30px;
      color: #333;
    }

    .container {
      max-width: 700px;
      margin: 0 auto;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .header {
      text-align: center;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 30px;
      color: #444;
    }

    .section {
      margin-bottom: 30px;
    }

    .section-title {
      font-size: 18px;
      font-weight: 600;
      border-bottom: 2px solid #eee;
      padding-bottom: 8px;
      margin-bottom: 16px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      font-size: 15px;
    }

    .row.total {
      font-weight: 600;
      border-top: 1px solid #eee;
      padding-top: 8px;
      margin-top: 8px;
    }

    .netpay {
      text-align: center;
      font-size: 20px;
      color: #0a9346;
      font-weight: bold;
      margin-top: 20px;
      border-top: 1px dashed #ccc;
      padding-top: 16px;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #777;
      margin-top: 24px;
    }
  </style>
</head>
<body>
     @php
      $baseSalary = $staff['daily_remuneration'] && $staff['daily_remuneration'] > 0
          ? $staff['daily_remuneration']
          : ($staff['base_salary'] ?? ($staff['basic_salary'] ?? 0));

      $totalEarnings = $baseSalary * 30;
      $tax = $staff['tax'] ?? 0;
      $tds = $staff['tds'] ?? 0;
      $loan = $staff['loan'] ?? 0;
      $totalDeductions = $tax + $tds + $loan;
      $net = $totalEarnings - $totalDeductions;
  @endphp

  <div class="container">
    <div class="header">
      Payroll Details for {{ $staff['name'] }}
    </div>

    <div class="section">
      <div class="section-title">Earnings</div>
      <div class="row"><span>Basic Salary</span><span> {{ number_format($baseSalary * 25) }}</span></div>
      <div class="row"><span>House Rent Allowance</span><span> {{ number_format($staff['housing_allowance'] * 3) }}</span></div>
      <div class="row"><span>Transport Allowance</span><span> {{ number_format($staff['transport_allowance'] * 2) }}</span></div>
      <div class="row total"><span>Total Earnings</span><span> {{ number_format($totalEarnings) }}</span></div>
    </div>

    <div class="section">
      <div class="section-title">Deductions</div>
      <div class="row"><span>Professional Tax</span><span> {{ number_format($staff['tax'] ?? 0) }}</span></div>
      <div class="row"><span>TDS</span><span> {{ number_format($staff['tds'] ?? 0) }}</span></div>
      <div class="row"><span>Loan Recovery</span><span> {{ number_format($staff['loan'] ?? 0) }}</span></div>
      <div class="row total">
        <span>Total Deductions</span>
        <span>
          {{ number_format(($staff['tax'] ?? 0) + ($staff['tds'] ?? 0) + ($staff['loan'] ?? 0)) }}
        </span>
      </div>
    </div>

    @php
      $net = ($staff['daily_remuneration'] * 30) - (($staff['tax'] ?? 0) + ($staff['tds'] ?? 0) + ($staff['loan'] ?? 0));
    @endphp

    <div class="netpay">
      Net Pay:  {{ number_format($net) }}
    </div>

    <div class="footer">
      * This is a system-generated payslip and does not require a signature.
    </div>
  </div>
</body>
</html>
