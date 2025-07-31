@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('invoice'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('invoice') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
            <div class="row">
                <div class="col-md-12" id="invoice-btn">
                    <button id="main-invoice-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_invoice') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-invoice"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('invoice_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.invoice.store') }}" method="post" enctype="multipart/form-data"
                            class="invoice_form">
                            @csrf

                            <style>
                                .select2-container--default .select2-selection--multiple .select2-selection__choice {
                                    background-color: #177bbb;
                                    border: 1px solid #177bbb;
                                    border-radius: 3px;
                                    color: #fff;
                                }

                                .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                                    color: inherit;
                                }

                                .select2-container {
                                    width: 100% !important;
                                }
                            </style>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="client_id">{{ \App\CPU\translate('client') }}</label>
                                            <select
                                                onchange="getRequest('{{ url('/') }}/admin/invoice/get_quotation?client_id='+this.value,'quotation_select','select')"
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="client_id" required>
                                                <option value="{{ null }}" selected disabled>Select client
                                                </option>
                                                @foreach ($client_list as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }}
                                                        ({{ $b['user_type'] }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="quotation_id">{{ \App\CPU\translate('Quotation') }}</label>
                                            <select id="quotation_select"
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="quotation_id" required>
                                                <!--<option value="{{ null }}" selected disabled>Select Quotation</option>-->
                                                <!--@foreach ($quotation_list as $b)
    -->
                                                <!--    <option value="{{ $b['id'] }}">{{ $b['quotation_number'] }}</option>-->
                                                <!--
    @endforeach-->
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="invoice_number">{{ \App\CPU\translate('Invoice_number') }}</label>
                                            <input readonly value="{{ $invoice_number }}"
                                                placeholder="Enter invoice_number" type="text" name="invoice_number"
                                                class="form-control" required>
                                        </div>

                                        <?php $invoice_date = date('Y-m-d'); ?>
                                        <?php $due_date = date('Y-m-d', strtotime($invoice_date . ' +30 days')); ?>

                                        <div class="form-group">
                                            <label for="start_date">{{ \App\CPU\translate('date') }}</label>
                                            <input value="{{ $invoice_date }}" placeholder="Enter date" type="date"
                                                name="start_date" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="renewal_date">{{ \App\CPU\translate('renewal_date') }} (+30
                                                days)</label>
                                            <input value="{{ $due_date }}" placeholder="Enter renewal_date"
                                                type="date" name="renewal_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <a class="btn btn-secondary text-white cancel">{{ \App\CPU\translate('Cancel') }}</a>
                                <button id="add" type="submit"
                                    class="btn btn-primary">{{ \App\CPU\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px" id="invoice-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('invoice_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $invoices->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_invoice') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search invoice') }}"
                                            aria-label="Search orders" value="{{ $filter_status }}">

                                        <input id="" type="hidden" name="filter_client" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search invoice') }}"
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
                                onchange="location.href='{{ route('admin.invoice.list') }}/?filter_status='+this.value+'&filter_client={{ $filter_client }}'">
                                <option value="" {{ $filter_status == '' ? 'selected' : '' }}>Select Status</option>
                                <option value="PENDING" {{ $filter_status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                <option value="PAID" {{ $filter_status == 'PAID' ? 'selected' : '' }}>PAID</option>
                                <option value="OVERDUE" {{ $filter_status == 'OVERDUE' ? 'selected' : '' }}>OVERDUE</option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.invoice.list') }}/?filter_status={{ $filter_status }}&filter_client='+this.value+''">
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
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th>Share</th>
                                        @endif
                                        <th>Client</th>
                                        <th>Quotation</th>
                                        <th>Invoice Number</th>
                                        <th>Start Date</th>
                                        <th>Renewal Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($invoices as $key => $invoice)
                                    <tbody>
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.invoice.view', [$invoice['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.invoice.edit', [$invoice['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $invoice['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif

                                                <br>

                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Receipts') }}"
                                                    class="btn btn-warning btn-sm"
                                                    href="{{ route('admin.invoice.add_receipts', [$invoice['id']]) }}">
                                                    Receipts
                                                </a>

                                                @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <br>

                                                    <a class="btn btn-primary btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank"
                                                        title="{{ \App\CPU\translate('Generate quotation') }}"
                                                        href="{{ route('admin.invoice.generate_invoice', [$invoice['id']]) }}">
                                                        <i class="tio-download"></i> Generate Invoice
                                                    </a>
                                                @endif
                                            </td>

                                            @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    @php($clientss = \App\User::where(['id' => $invoice->client_id])->first())
                                                    <?php
                                                    if ($clientss) {
                                                        $number = $clientss->phone;
                                                    } else {
                                                        $number = '9747625648';
                                                    }
                                                    $message = 'Invoice Pdf';
                                                    $url = "https://web.whatsapp.com/send?phone=$number&text=$message";
                                                    
                                                    if ($clientss) {
                                                        $email = $clientss->email;
                                                    } else {
                                                        $email = 'rishikeshr850@gmail.com';
                                                    }
                                                    
                                                    $subject = 'Invoice Pdf';
                                                    $body = 'Invoice Pdf';
                                                    $url2 = "mailto:$email?subject=$subject&body=$body";
                                                    ?>

                                                    <a class="btn btn-success btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank" title="{{ \App\CPU\translate('Generate Pdf') }}"
                                                        href="{{ $url }}">
                                                        <i class="tio-whatsapp"></i>
                                                    </a>

                                                    <a class="btn btn-danger btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank" title="{{ \App\CPU\translate('Generate Pdf') }}"
                                                        href="{{ $url2 }}">
                                                        <i class="tio-google"></i>
                                                    </a>
                                                </td>
                                            @endif
                                            <td>
                                                @if (\App\User::where(['id' => $invoice->client_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $invoice->client_id])->first()->name }}</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                @if (\App\Model\Quotation::where(['id' => $invoice->quotation_id])->first())
                                                    <b><i>{{ \App\Model\Quotation::where(['id' => $invoice->quotation_id])->first()->quotation_number }}</i></b>
                                                @endif
                                            </td>

                                            <td><b><i>{{ $invoice->invoice_number }}</i></b></td>
                                            <td>{{ date('d M Y', strtotime($invoice->start_date)) }}</td>
                                            <td>{{ date('d M Y', strtotime($invoice->renewal_date)) }}</td>
                                            <td>
                                                <?php if($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD'){ ?>
                                                <!--<b><i>{{ $invoice->status }}</i></b>-->
                                                <select style="width:150px;" id="{{ $invoice->id }}" name="status"
                                                    class="invoice_status_change form-control" id="type_form"
                                                    value="{{ $invoice['status'] }}">
                                                    <option value="PENDING"
                                                        {{ $invoice['status'] == 'PENDING' ? 'selected' : '' }}>PENDING
                                                    </option>
                                                    <option value="PAID"
                                                        {{ $invoice['status'] == 'PAID' ? 'selected' : '' }}>PAID</option>
                                                    <option value="OVERDUE"
                                                        {{ $invoice['status'] == 'OVERDUE' ? 'selected' : '' }}>OVERDUE
                                                    </option>
                                                </select>
                                                <?php } else { ?>
                                                {{ $invoice->status }}
                                                <?php } ?>
                                            </td>


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $invoices->links() }}
                    </div>

                    @if (count($invoices) == 0)
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

    <script>
        $('#main-invoice-add').on('click', function() {
            $('#main-invoice').show();
        });

        $('.cancel').on('click', function() {
            $('.invoice_form').attr('action', "{{ route('admin.invoice.store') }}");
            $('#main-invoice').hide();
        });

        $(document).on('change', '.invoice_status_change', function() {
            var id = $(this).attr("id");
            var status = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.invoice.invoice_status_change') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    }
                    // location.reload();
                }
            });
        });

        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.invoice.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('invoice_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('invoice_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_invoice') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.invoice.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('invoice_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
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
