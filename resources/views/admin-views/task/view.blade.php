@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('task'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('task') }}</li>
            </ol>
        </nav>

        <!--<div class="row">-->
        <!--    <div class="col-md-12" id="task-btn">-->
        <!--        <button id="main-task-add" class="btn btn-primary"><i class="tio-add-circle"></i> {{ \App\CPU\translate('add_task') }}</button>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="row pt-4" id="main-task"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('task_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.task.store') }}" method="post" enctype="multipart/form-data"
                            class="task_form">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('date') }}</label>

                                            <?php $today_date = date('Y-m-d'); ?>
                                            <input placeholder="Enter date" type="date" value="{{ $today_date }}"
                                                name="date" class="form-control" required>
                                        </div>

                                        <style>
                                            .select2-container {
                                                width: 100% !important;
                                            }
                                        </style>

                                        <div class="form-group">
                                            <label for="team_lead_id">{{ \App\CPU\translate('Project') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="project_id" required>
                                                <option value="{{ null }}" selected disabled>Select Project
                                                </option>
                                                @foreach ($projects as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['project_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="task_name">{{ \App\CPU\translate('task_name') }}</label>
                                            <input placeholder="Enter task_name" type="text" name="task_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="task_description">{{ \App\CPU\translate('task_description') }}</label>
                                            <textarea name="task_description" placeholder="Enter task_description" class="editor textarea" cols="30"
                                                rows="10"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('ui_sample') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="ui_sample" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('database_file') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="database_file" class="custom-file-input"
                                                    accept=".jpg, .sql, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>

                                        <?php
                                        $test_case = '<table align="center" border="1" cellpadding="1" cellspacing="1" style="width:100%">
                                                                                    	<thead>
                                                                                    		<tr>
                                                                                    			<th scope="col">MODULE</th>
                                                                                    			<th scope="col">TEST_CASE_TITLE</th>
                                                                                    			<th scope="col">DESCRIPTION</th>
                                                                                    			<th scope="col">TEST_STEPS</th>
                                                                                    			<th scope="col">EXPECTED_RESULT</th>
                                                                                    			<th scope="col">ACTUAL_RESULT</th>
                                                                                    			<th scope="col">PASS/FAIL</th>
                                                                                    		</tr>
                                                                                    	</thead>
                                                                                    	<tbody>
                                                                                    		<tr>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    			<td>&nbsp;</td>
                                                                                    		</tr>
                                                                                    	</tbody>
                                                                                    </table>';
                                        ?>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('test_case') }}</label>
                                            <textarea name="test_case" placeholder="Enter test_case" class="editor textarea" cols="30" rows="10">{!! $test_case !!}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="test_case_updated">{{ \App\CPU\translate('test_case_updated') }}</label>
                                            <select name="test_case_updated" class="form-control" id="test_case_updated"
                                                required>
                                                <option value="{{ null }}" selected disabled>Select</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="agile_work_detail">{{ \App\CPU\translate('agile_work_detail') }}</label>
                                            <textarea name="agile_work_detail" placeholder="Enter agile_work_detail" class="editor textarea" cols="30"
                                                rows="10"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="estimated_time">{{ \App\CPU\translate('estimated_time') }} (in
                                                mins)</label>
                                            <input placeholder="Enter estimated_time" type="number" name="estimated_time"
                                                class="form-control" required>
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

        <div class="row" style="margin-top: 20px" id="task-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('task_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $tasks->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_task') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_project" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search task') }}"
                                            aria-label="Search orders" value="{{ $filter_project }}">

                                        <input id="" type="hidden" name="filter_task_status"
                                            class="form-control" placeholder="{{ \App\CPU\translate('Search task') }}"
                                            aria-label="Search orders" value="{{ $filter_task_status }}">

                                        <input id="" type="hidden" name="filter_date" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search task') }}"
                                            aria-label="Search orders" value="{{ $filter_date }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.task.list') }}/?filter_project='+this.value+'&filter_date={{ $filter_date }}&filter_task_status={{ $filter_task_status }}'">
                                <option value="0" {{ $filter_project == '' ? 'selected' : '' }}>Select Project</option>
                                @foreach ($projects as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_project == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.task.list') }}/?filter_project={{ $filter_project }}&filter_date={{ $filter_date }}&filter_task_status='+this.value+''">
                                <option value="" {{ $filter_task_status == '' ? 'selected' : '' }}>Select task status
                                </option>
                                <option value="In Progress" {{ $filter_task_status == 'In Progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="Completed" {{ $filter_task_status == 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <input placeholder="Enter name" type="date" name="filter_date"
                                value="{{ $filter_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.task.list') }}/?filter_project={{ $filter_project }}&filter_date='+this.value+'&filter_task_status={{ $filter_task_status }}'">
                        </div>
                    </div>

                    <?php $user_type = auth('customer')->user()->user_type; ?>

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

                                        <th>Punching</th>

                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Approval</th>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Name</th>
                                        <th>Estimated Time</th>
                                        <th>Adjusted Time</th>
                                        <th>Tracked Time</th>
                                        <th>Task Status</th>
                                        <th>Test Status</th>



                                    </tr>
                                </thead>
                                @foreach ($tasks as $key => $task)
                                    <?php
                                    $bg = 'white';
                                    $fg = 'black';
                                    
                                    if ($task->task_status == 'Completed') {
                                        if ($task->tracked_actual_time_taken != 0 && $task->tech_lead_adjusted_time != 0) {
                                            $fg = 'white';
                                            if ($task->test_case_updated == 1 && $task->tested_by == 0) {
                                                $bg = '#f19332';
                                            } else {
                                                if ($task->tracked_actual_time_taken > $task->tech_lead_adjusted_time) {
                                                    $bg = '#c10034';
                                                }
                                    
                                                if ($task->tracked_actual_time_taken == $task->tech_lead_adjusted_time) {
                                                    $bg = '#006800';
                                                }
                                    
                                                if ($task->tracked_actual_time_taken < $task->tech_lead_adjusted_time) {
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
                                            <td>{{ $tasks->firstItem() + $key }}</td>
                                            @if ($user_type == 'CEO')
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="ceo_approval"
                                                            id="{{ $task->tsid }}" <?php if ($task->ceo_approval == 1) {
                                                                echo 'checked';
                                                            } ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            @endif

                                            <td style="text-align:center;">
                                                <?php if($task['task_status'] == 'In Progress'){ ?>
                                                <?php if($task['task_tracking_status'] == 'NOT_STARTED'){ ?>
                                                <a class="btn btn-success btn-sm task_start"
                                                    title="{{ \App\CPU\translate('task_start') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">START</a><br>
                                                <?php } ?>

                                                <?php if($task['task_tracking_status'] == 'STARTED'){ ?>
                                                <a class="btn btn-warning btn-sm task_pause"
                                                    title="{{ \App\CPU\translate('task_pause') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">PAUSE</a><br>
                                                <a class="btn btn-danger btn-sm task_end"
                                                    title="{{ \App\CPU\translate('task_end') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">END</a>
                                                <?php } ?>

                                                <?php if($task['task_tracking_status'] == 'PAUSED'){ ?>
                                                <a class="btn btn-primary btn-sm task_resume"
                                                    title="{{ \App\CPU\translate('task_resume') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">RESUME</a><br>
                                                <a class="btn btn-danger btn-sm task_end"
                                                    title="{{ \App\CPU\translate('task_end') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">END</a>
                                                <?php } ?>

                                                <?php if($task['task_tracking_status'] == 'RESUMED'){ ?>
                                                <a class="btn btn-warning btn-sm task_pause"
                                                    title="{{ \App\CPU\translate('task_pause') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">PAUSE</a><br>
                                                <a class="btn btn-danger btn-sm task_end"
                                                    title="{{ \App\CPU\translate('task_end') }}"
                                                    style="cursor: pointer;color:white!important;margin-bottom:5px;width:100%;"
                                                    id="{{ $task['tsid'] }}">END</a>
                                                <?php } ?>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.task.view', [$task['tsid']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <!--@if ($user_type == 'SCRUM_MASTER' || $task->date == $today_date)
    -->
                                                <!--
    @endif-->
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.task.edit', [$task['tsid']]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $task['tsid'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                            <td>
                                                Technical Lead :
                                                <?php if($task['tech_lead_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?><br>

                                                Team Lead :
                                                <?php if($task['team_lead_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?><br>

                                                CEO :
                                                <?php if($task['ceo_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?>
                                            </td>
                                            <td>{{ date('d M Y', strtotime($task->date)) }}</td>
                                            <td>{{ $task->project_name }}</td>
                                            <td>{{ $task->task_name }}</td>
                                            <td>{{ $task->estimated_time . ' min' }}</td>
                                            <td>{{ $task->tech_lead_adjusted_time == 0 ? 'Not Added' : $task->tech_lead_adjusted_time . ' min' }}
                                            </td>

                                            <?php
                                            $minutes = floor($task['tracked_actual_time_taken']);
                                            $seconds = round(($task['tracked_actual_time_taken'] - $minutes) * 60);
                                            $formatted_time = $task['tracked_actual_time_taken'] == null ? '' : $minutes . ' min ' . $seconds . ' sec';
                                            ?>

                                            <td>{{ $formatted_time }}</td>
                                            <td>{{ $task->task_status }}</td>
                                            <td>
                                                <?php if($task['test_case_updated'] == 1){ ?>
                                                <?php if($task['tested_by'] != 0){ ?>
                                                Tested Date : <b><i>{{ date('d M Y', strtotime($task->date)) }}</i></b><br>
                                                <?php if($task['test_status'] == 'SUCCESS'){ ?>
                                                Status : <b style="color:green;"><i>{{ $task->test_status }}</i></b>
                                                <?php } else { ?>
                                                Status : <b style="color:red;"><i>{{ $task->test_status }}</i></b>
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
                        {{ $tasks->links() }}
                    </div>

                    @if (count($tasks) == 0)
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
        $('#main-task-add').on('click', function() {
            $('#main-task').show();
        });

        $('.cancel').on('click', function() {
            $('.task_form').attr('action', "{{ route('admin.task.store') }}");
            $('#main-task').hide();
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
                url: "{{ route('admin.task.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('task_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('task_inactive_successfully') }}');
                    }
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
                url: "{{ route('admin.task.ceo_approval') }}",
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
                    // location.reload();
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_task') }}?",
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
                        url: "{{ route('admin.task.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.task_start', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_start_this_task') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
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
                        url: "{{ route('admin.task.task_start') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_started_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.task_pause', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_pause_this_task') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Pause') }}!',
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
                        url: "{{ route('admin.task.task_pause') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_paused_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.task_resume', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_resume_this_task') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Resume') }}!',
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
                        url: "{{ route('admin.task.task_resume') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_resumed_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.task_end', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are you sure end this task') }}?",
                text: "{{ \App\CPU\translate('Your task will be changed to completed and cannot be reverted') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('End') }}!',
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
                        url: "{{ route('admin.task.task_end') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('task_ended_successfully') }}');
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
