@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('quotation'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('quotation') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
            <div class="row">
                <div class="col-md-12" id="quotation-btn">
                    <button id="main-quotation-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_quotation') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-quotation"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('quotation_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.quotation.store') }}" method="post" enctype="multipart/form-data"
                            class="quotation_form">
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
                                                onchange="getRequest('{{ url('/') }}/admin/quotation/get_proposal?client_id='+this.value,'proposal_select','select')"
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
                                            <label for="proposal_id">{{ \App\CPU\translate('Proposal') }}</label>
                                            <select id="proposal_select"
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="proposal_id" required>
                                                <!--<option value="{{ null }}" selected disabled>Select Proposal</option>-->
                                                <!--@foreach ($proposal_list as $b)
    -->
                                                <!--    <option value="{{ $b['id'] }}">{{ $b['proposal_title'] }}</option>-->
                                                <!--
    @endforeach-->
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="quotation_number">{{ \App\CPU\translate('quotation_number') }}</label>
                                            <input readonly value="{{ $quotation_number }}"
                                                placeholder="Enter quotation_number" type="text" name="quotation_number"
                                                class="form-control" required>
                                        </div>

                                        <?php $quotation_date = date('Y-m-d'); ?>
                                        <?php $due_date = date('Y-m-d', strtotime($quotation_date . ' +30 days')); ?>

                                        <div class="form-group">
                                            <label for="quotation_date">{{ \App\CPU\translate('quotation_date') }}</label>
                                            <input value="{{ $quotation_date }}" placeholder="Enter quotation_date"
                                                type="date" name="quotation_date" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="due_date">{{ \App\CPU\translate('due_date') }} (+30 days)</label>
                                            <input value="{{ $due_date }}" placeholder="Enter due_date" type="date"
                                                name="due_date" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="notes">{{ \App\CPU\translate('notes') }}</label>
                                            <input placeholder="Enter notes" type="text" name="notes"
                                                class="form-control">
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

        <div class="row" style="margin-top: 20px" id="quotation-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('quotation_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $quotations->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_quotation') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search quotation') }}"
                                            aria-label="Search orders" value="{{ $filter_status }}">

                                        <input id="" type="hidden" name="filter_client" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search quotation') }}"
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
                                onchange="location.href='{{ route('admin.quotation.list') }}/?filter_status='+this.value+'&filter_client={{ $filter_client }}'">
                                <option value="" {{ $filter_status == '' ? 'selected' : '' }}>Select Status</option>
                                <option value="PENDING" {{ $filter_status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                <option value="ACCEPTED" {{ $filter_status == 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED
                                </option>
                                <option value="REJECTED" {{ $filter_status == 'REJECTED' ? 'selected' : '' }}>REJECTED
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.quotation.list') }}/?filter_status={{ $filter_status }}&filter_client='+this.value+''">
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
                                        <th>Proposal</th>
                                        <th>Quotation Number</th>
                                        <th>Quotation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($quotations as $key => $quotation)
                                    <tbody>
                                        <tr>
                                            <td>{{ $quotation->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.quotation.view', [$quotation['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.quotation.edit', [$quotation['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $quotation['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif

                                                <br>

                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Add Items') }}"
                                                    class="btn btn-warning btn-sm"
                                                    href="{{ route('admin.quotation.add_items', [$quotation['id']]) }}">
                                                    Items
                                                </a>

                                                @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <br>

                                                    <a class="btn btn-primary btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank"
                                                        title="{{ \App\CPU\translate('Generate quotation') }}"
                                                        href="{{ route('admin.quotation.generate_quotation', [$quotation['id']]) }}">
                                                        <i class="tio-download"></i> Generate Quotation
                                                    </a>
                                                @endif
                                            </td>

                                            @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    @php($clientss = \App\User::where(['id' => $quotation->client_id])->first())
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
                                                @if (\App\User::where(['id' => $quotation->client_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $quotation->client_id])->first()->name }}
                                                            ({{ \App\User::where(['id' => $quotation->client_id])->first()->user_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                @if (\App\Model\Proposal::where(['id' => $quotation->proposal_id])->first())
                                                    <b><i>{{ \App\Model\Proposal::where(['id' => $quotation->proposal_id])->first()->proposal_title }}</i></b>
                                                @endif
                                            </td>

                                            <td><b><i>{{ $quotation->quotation_number }}</i></b></td>
                                            <td>{{ date('d M Y', strtotime($quotation->quotation_date)) }}</td>
                                            <td>
                                                <?php if($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD'){ ?>
                                                <!--<b><i>{{ $quotation->status }}</i></b>-->
                                                <select style="width:150px;" id="{{ $quotation->id }}" name="status"
                                                    class="quotation_status_change form-control" id="type_form"
                                                    value="{{ $quotation['status'] }}">
                                                    <option value="PENDING"
                                                        {{ $quotation['status'] == 'PENDING' ? 'selected' : '' }}>PENDING
                                                    </option>
                                                    <option value="ACCEPTED"
                                                        {{ $quotation['status'] == 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED
                                                    </option>
                                                    <option value="REJECTED"
                                                        {{ $quotation['status'] == 'REJECTED' ? 'selected' : '' }}>REJECTED
                                                    </option>
                                                </select>
                                                <?php } else { ?>
                                                {{ $quotation->status }}
                                                <?php } ?>
                                            </td>


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $quotations->links() }}
                    </div>

                    @if (count($quotations) == 0)
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
        $('#main-quotation-add').on('click', function() {
            $('#main-quotation').show();
        });

        $('.cancel').on('click', function() {
            $('.quotation_form').attr('action', "{{ route('admin.quotation.store') }}");
            $('#main-quotation').hide();
        });

        $(document).on('change', '.quotation_status_change', function() {
            var id = $(this).attr("id");
            var status = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.quotation.quotation_status_change') }}",
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
                url: "{{ route('admin.quotation.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('quotation_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('quotation_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_quotation') }}?",
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
                        url: "{{ route('admin.quotation.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('quotation_deleted_successfully') }}'
                                );
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
