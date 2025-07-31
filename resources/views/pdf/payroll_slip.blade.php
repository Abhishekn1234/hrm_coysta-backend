<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 14px; padding: 30px; }
    .header { background-color: #28a745; color: white; padding: 12px 16px; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
    .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 20px; background: #fff; }
    .section-title { background-color: #f8f9fa; padding: 8px 12px; font-weight: bold; margin-bottom: 10px; }
    .table { width: 100%; border-collapse: collapse; }
    .table td { padding: 6px 8px; border-bottom: 1px solid #eee; }
    .total-row { background-color: #f8f9fa; font-weight: bold; }
    .text-end { text-align: right; }
    .netpay { text-align: center; font-size: 20px; font-weight: bold; margin-top: 30px; }
  </style>
</head>
<body>
  <div class="header">
    Payroll Details – {{ $data['employee']['first_name'] ?? '' }} {{ $data['employee']['last_name'] ?? '' }}
    ({{ $data['employee']['emp_code'] ?? 'EMP' . $data['employee']['id'] }}) –
    {{ \Carbon\Carbon::parse($data['payrollMonth'])->format('F Y') }}
  </div>

  <div class="card">
    <div style="display: flex; gap: 20px;">
      <!-- Earnings -->
      <div style="flex: 1;">
        <div class="section-title">Earnings</div>
        <table class="table">
          <tr><td>Basic Salary</td><td class="text-end">₹{{ number_format($data['details']['basic_salary'], 2) }}</td></tr>
          <tr><td>House Rent Allowance</td><td class="text-end">₹{{ number_format($data['details']['hra'], 2) }}</td></tr>
          <tr><td>Transport Allowance</td><td class="text-end">₹{{ number_format($data['details']['transport_allowance'], 2) }}</td></tr>
          <tr class="total-row"><td>Total Earnings</td><td class="text-end">₹{{ number_format($data['details']['total_earnings'], 2) }}</td></tr>
        </table>
      </div>

      <!-- Deductions -->
      <div style="flex: 1;">
        <div class="section-title">Deductions</div>
        <table class="table">
          <tr><td>Professional Tax</td><td class="text-end">₹{{ number_format($data['details']['professional_tax'], 2) }}</td></tr>
          <tr><td>TDS</td><td class="text-end">₹{{ number_format($data['details']['tds'], 2) }}</td></tr>
          <tr><td>Loan Recovery</td><td class="text-end">₹{{ number_format($data['details']['loan_recovery'], 2) }}</td></tr>
          <tr class="total-row"><td>Total Deductions</td><td class="text-end">₹{{ number_format($data['details']['total_deductions'], 2) }}</td></tr>
        </table>
      </div>
    </div>
  </div>

  <div class="netpay">
    Net Pay: ₹{{ number_format($data['details']['net_pay'], 2) }}
  </div>
</body>
</html>
