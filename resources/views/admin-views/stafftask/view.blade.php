@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('staff task'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('staff task') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="stafftask-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('staff task_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $stafftasks->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_stafftask') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_project" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff task') }}"
                                            aria-label="Search orders" value="{{ $filter_project }}">

                                        <input id="" type="hidden" name="filter_task_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff task') }}"
                                            aria-label="Search orders" value="{{ $filter_task_status }}">

                                        <input id="" type="hidden" name="filter_staff_list" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff task') }}"
                                            aria-label="Search orders" value="{{ $filter_staff_list }}">

                                        <input id="" type="hidden" name="filter_date" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff task') }}"
                                            aria-label="Search orders" value="{{ $filter_date }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php $user_type = auth('customer')->user()->user_type; ?>
                    <?php $today_date = date('Y-m-d'); ?>

                    <style>
                        .blink_me {
                            animation: blinker 2s linear infinite;
                        }

                        @keyframes blinker {
                            50% {
                                opacity: 0;
                            }
                        }
                    </style>

                    <?php if($user_type == 'CEO' || $user_type == 'TEAM_LEAD' || $user_type == 'TECHNICAL_LEAD'  ){ ?>
                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-12 col-lg-12">
                            <h1 style="text-align:center;" class="blink_me"><b
                                    style="color:red;"><i>{{ $pending_approval }} PENDING {{ $user_type }}
                                        APPROVALS</i></b></h1>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-3 col-lg-3">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.stafftask.list') }}/?filter_project='+this.value+'&filter_date={{ $filter_date }}&filter_task_status={{ $filter_task_status }}&filter_staff_list={{ $filter_staff_list }}'">
                                <option value="0" {{ $filter_project == '' ? 'selected' : '' }}>Select Project</option>
                                @foreach ($projects as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_project == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-3 col-lg-3">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.stafftask.list') }}/?filter_project={{ $filter_project }}&filter_date={{ $filter_date }}&filter_task_status='+this.value+'&filter_staff_list={{ $filter_staff_list }}'">
                                <option value="" {{ $filter_task_status == '' ? 'selected' : '' }}>Select task status
                                </option>
                                <option value="In Progress" {{ $filter_task_status == 'In Progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="Completed" {{ $filter_task_status == 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-3 col-lg-3">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.stafftask.list') }}/?filter_project={{ $filter_project }}&filter_date={{ $filter_date }}&filter_task_status={{ $filter_task_status }}&filter_staff_list='+this.value+''">
                                <option value="0" {{ $filter_staff_list == '' ? 'selected' : '' }}>Select Staff</option>
                                @foreach ($staff_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_staff_list == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-3 col-lg-3">
                            <input placeholder="Enter name" type="date" name="filter_date" value="{{ $filter_date }}"
                                class="form-control"
                                onchange="location.href='{{ route('admin.stafftask.list') }}/?filter_project={{ $filter_project }}&filter_date='+this.value+'&filter_task_status={{ $filter_task_status }}&filter_staff_list={{ $filter_staff_list }}'">
                        </div>
                    </div>


                    <style>
                        ::-webkit-scrollbar {
                            height: 15px !important;
                        }
                    </style>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive" style="transform: rotateX(180deg);">
                            <table id="columnSearchDatatable" style="transform: rotateX(180deg);"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('sl') }}</th>
                                        @if ($user_type == 'CEO')
                                            <th>CEO Approval</th>
                                        @endif
                                        @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th>Punching</th>
                                        @endif
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Approval</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Project</th>
                                        <th>Name</th>
                                        <th>Estimated Time</th>
                                        <th>Adjusted Time</th>
                                        <th>Tracked Time</th>
                                        <th>Task Status</th>
                                        <th>Test Status</th>


                                    </tr>
                                </thead>
                                @foreach ($stafftasks as $key => $stafftask)
                                    <?php
                                    $bg = 'white';
                                    $fg = 'black';
                                    
                                    if ($stafftask->task_status == 'Completed') {
                                        if ($stafftask->tracked_actual_time_taken != 0 && $stafftask->tech_lead_adjusted_time != 0) {
                                            $fg = 'white';
                                            if ($stafftask->test_case_updated == 1 && $stafftask->tested_by == 0) {
                                                $bg = '#f19332';
                                            } else {
                                                if ($stafftask->tracked_actual_time_taken > $stafftask->tech_lead_adjusted_time) {
                                                    $bg = '#c10034';
                                                }
                                    
                                                if ($stafftask->tracked_actual_time_taken == $stafftask->tech_lead_adjusted_time) {
                                                    $bg = '#006800';
                                                }
                                    
                                                if ($stafftask->tracked_actual_time_taken < $stafftask->tech_lead_adjusted_time) {
                                                    $bg = '#065af5';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <style>
                                        #tbd-{{ $key }} {
                                            background-color: {{ $bg }} !important;
                                        }

                                        #tbd-{{ $key }} td {
                                            color: {{ $fg }} !important;
                                        }
                                    </style>
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $stafftasks->firstItem() + $key }}</td>
                                            @if ($user_type == 'CEO')
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="ceo_approval"
                                                            id="{{ $stafftask->tsid }}" <?php if ($stafftask->ceo_approval == 1) {
                                                                echo 'checked';
                                                            } ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            @endif

                                            @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td style="text-align:center;">
                                                    <?php if($stafftask['task_status'] == 'Completed'){ ?>
                                                    <?php if($stafftask['task_tracking_status'] == 'ENDED'){ ?>
                                                    <a class="btn btn-success btn-sm task_restart"
                                                        title="{{ \App\CPU\translate('task_restart') }}"
                                                        style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                        id="{{ $stafftask['tsid'] }}">RESTART</a><br>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            @endif

                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.stafftask.view', [$stafftask['tsid']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <!--@if ($user_type == 'SCRUM_MASTER' || $stafftask->date == $today_date)
    -->

                                                <!--
    @endif-->
                                                @if ($user_type != 'PRODUCT_OWNER' && $user_type != 'CLIENT')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.stafftask.edit', [$stafftask['tsid']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                Technical Lead :
                                                <?php if($stafftask['tech_lead_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?><br>

                                                Team Lead :
                                                <?php if($stafftask['team_lead_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?><br>

                                                CEO :
                                                <?php if($stafftask['ceo_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?>
                                            </td>
                                            <td>{{ date('d M Y', strtotime($stafftask->date)) }}</td>
                                            <td>{{ $stafftask->user_name }}<br>( {{ $stafftask->user_user_type }} )</td>
                                            <td>{{ $stafftask->project_name }}</td>
                                            <td>{{ $stafftask->task_name }}</td>
                                            <td>{{ $stafftask->estimated_time . ' min' }}</td>
                                            <td>{{ $stafftask->tech_lead_adjusted_time == 0 ? 'Not Added' : $stafftask->tech_lead_adjusted_time . ' min' }}
                                            </td>

                                            <?php
                                            $minutes = floor($stafftask['tracked_actual_time_taken']);
                                            $seconds = round(($stafftask['tracked_actual_time_taken'] - $minutes) * 60);
                                            $formatted_time = $stafftask['tracked_actual_time_taken'] == null ? '' : $minutes . ' min ' . $seconds . ' sec';
                                            ?>

                                            <td>
                                                <?php if(($user_type == 'CEO' || $user_type == 'TEAM_LEAD') && $stafftask->task_status == 'Completed'){ ?>
                                                <div class="form-group">
                                                    <input style="background-color:#d1d1d1;"
                                                        class="track_time_edit form-control" id="{{ $stafftask->tsid }}"
                                                        type="number"
                                                        value="{{ $stafftask->tracked_actual_time_taken }}"
                                                        name="update_tracked_actual_time_taken" class="form-control">
                                                </div>
                                                <?php } ?>
                                                {{ $formatted_time }}
                                            </td>
                                            <td>{{ $stafftask->task_status }}</td>
                                            <td>
                                                <?php if($stafftask['test_case_updated'] == 1){ ?>
                                                <?php if($stafftask['tested_by'] != 0){ ?>
                                                Tested Date :
                                                <b><i>{{ date('d M Y', strtotime($stafftask->date)) }}</i></b><br>
                                                <?php if($stafftask['test_status'] == 'SUCCESS'){ ?>
                                                Status : <b style="color:green;"><i>{{ $stafftask->test_status }}</i></b>
                                                <?php } else { ?>
                                                Status : <b style="color:red;"><i>{{ $stafftask->test_status }}</i></b>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Tested</i></b>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>No Testcases</i></b>
                                                <?php } ?>
                                            </td>



                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $stafftasks->links() }}
                    </div>

                    @if (count($stafftasks) == 0)
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
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('click', '.task_restart', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_restart_this_task') }}?",
                text: "{{ \App\CPU\translate('The completed task will be converted to progress') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Start') }}!',
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
                        url: "{{ route('admin.stafftask.task_restart') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_restarted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('change', '.track_time_edit', function() {
            var id = $(this).attr("id");
            var time_edit = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.stafftask.track_time_edit') }}",
                method: 'POST',
                data: {
                    id: id,
                    time_edit: time_edit
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('time_edit_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('time_edit_successfully') }}');
                    }
                    // location.reload();
                }
            });
        });

        $(document).on('change', '.ceo_approval', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var ceo_approval = 1;
            } else if ($(this).prop("checked") == false) {
                var ceo_approval = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.stafftask.ceo_approval') }}",
                method: 'POST',
                data: {
                    id: id,
                    ceo_approval: ceo_approval
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('ceo_approval_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('ceo_approval_successfully') }}');
                    }
                    location.reload();
                }
            });
        });

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
