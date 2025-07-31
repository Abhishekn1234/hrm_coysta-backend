@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('receipts'))
@push('css_or_js')
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/back-end/css/custom.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('invoice_report') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('Receipts') }}</li>
            </ol>
        </nav>
        <?php $user_type = auth('customer')->user()->user_type; ?>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ \App\CPU\translate('receipt') }} {{ \App\CPU\translate('Table') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ \App\CPU\translate('sl') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('receipt_number') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('receipt_date') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('payment_method') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('amount_paid') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @foreach ($receipts as $k => $de_p)
                                        <?php $total = $total + $de_p->amount_paid; ?>
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $de_p->receipt_number }}</td>
                                            <td>{{ date('d M Y', strtotime($de_p->receipt_date)) }}</td>
                                            <td>{{ $de_p->payment_method }}</td>
                                            <td style="text-align:right;">{{ $de_p->amount_paid }} $</td>
                                        </tr>
                                    @endforeach

                                    <?php if(count($receipts) > 0) { ?>
                                    <tr>
                                        <th scope="col" colspan="4" style="text-align:right;">Grand Total</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $total }} $
                                        </th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="4" style="text-align:right;">Amount to be paid</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $items_total }} $
                                        </th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="4" style="text-align:right;">Balance</th>
                                        <th scope="col" colspan="1" style="text-align:right;">
                                            {{ $items_total - $total }} $</th>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
