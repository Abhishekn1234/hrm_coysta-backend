@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('invoice_report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('invoice_report') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        <div class="row" style="margin-top: 20px" id="invoice_report-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('invoice_report_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $invoice_reports->total() }})</h5>
                                </div>
                            </div>
                            <div class="col-12 mt-1 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search_by_invoice_report') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search invoice_report') }}"
                                            aria-label="Search orders" value="{{ $filter_status }}">

                                        <input id="" type="hidden" name="filter_client" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search invoice_report') }}"
                                            aria-label="Search orders" value="{{ $filter_client }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.invoice_report.list') }}/?filter_status='+this.value+'&filter_client={{ $filter_client }}'">
                                <option value="" {{ $filter_status == '' ? 'selected' : '' }}>Select Status</option>
                                <option value="PENDING" {{ $filter_status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                <option value="PAID" {{ $filter_status == 'PAID' ? 'selected' : '' }}>PAID</option>
                                <option value="OVERDUE" {{ $filter_status == 'OVERDUE' ? 'selected' : '' }}>OVERDUE</option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.invoice_report.list') }}/?filter_status={{ $filter_status }}&filter_client='+this.value+''">
                                <option value="0" {{ $filter_client == '' ? 'selected' : '' }}>Select Client</option>
                                @foreach ($client_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_client == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}</th>
                                        <th>Client</th>
                                        <th>Quotation</th>
                                        <th>Invoice Number</th>
                                        <th>Start Date</th>
                                        <th>Renewal Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($invoice_reports as $key => $invoice_report)
                                    <tbody>
                                        <tr>
                                            <td>{{ $invoice_report->id }}</td>
                                            <td style="text-align:center;">
                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Receipts') }}"
                                                    class="btn btn-warning btn-sm"
                                                    href="{{ route('admin.invoice_report.add_receipts', [$invoice_report['id']]) }}">
                                                    Receipts
                                                </a>
                                            </td>

                                            <td>
                                                @if (\App\User::where(['id' => $invoice_report->client_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $invoice_report->client_id])->first()->name }}</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                @if (\App\Model\Quotation::where(['id' => $invoice_report->quotation_id])->first())
                                                    <b><i>{{ \App\Model\Quotation::where(['id' => $invoice_report->quotation_id])->first()->quotation_number }}</i></b>
                                                @endif
                                            </td>

                                            <td><b><i>{{ $invoice_report->invoice_number }}</i></b></td>
                                            <td>{{ date('d M Y', strtotime($invoice_report->start_date)) }}</td>
                                            <td>{{ date('d M Y', strtotime($invoice_report->renewal_date)) }}</td>
                                            <td>
                                                <b><i>{{ $invoice_report->status }}</i></b>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $invoice_reports->links() }}
                    </div>

                    @if (count($invoice_reports) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function() {
            mbimagereadURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });
    </script>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
