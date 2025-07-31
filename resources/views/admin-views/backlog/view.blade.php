@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('backlog'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('backlog') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row">
            <div class="col-md-12" id="backlog-btn">
                <button id="main-backlog-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                    {{ \App\CPU\translate('add_backlog') }}</button>
                @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                    <a href="{{ route('admin.backlog.bulk-import') }}"
                        class="btn btn-success">{{ \App\CPU\translate('Import Backlogs') }}</a>
                @endif
            </div>
        </div>

        <div class="row pt-4" id="main-backlog"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('backlog_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.backlog.store') }}" method="post" enctype="multipart/form-data"
                            class="backlog_form">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <style>
                                            .select2-container {
                                                width: 100% !important;
                                            }
                                        </style>

                                        <div class="form-group">
                                            <label for="project_id">{{ \App\CPU\translate('Project') }}</label>
                                            <select
                                                onchange="getRequest('{{ url('/') }}/admin/backlog/get_sprint?project_id='+this.value,'sprint_name','input')"
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
                                            <label
                                                for="backlog_assigned_user_id">{{ \App\CPU\translate('Assigned To') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="backlog_assigned_user_id" required>
                                                <option value="{{ null }}" selected disabled>Select Assigned To
                                                </option>
                                                @foreach ($staff_list as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="sprint_name">{{ \App\CPU\translate('sprint_name') }}</label>
                                            <input style="background-color:#d1d1d1;" readonly
                                                placeholder="Enter sprint_name" type="text" name="sprint_name"
                                                id="sprint_name" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="backlog_name">{{ \App\CPU\translate('backlog_name') }}</label>
                                            <input placeholder="Enter backlog_name" type="text" name="backlog_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="backlog_description">{{ \App\CPU\translate('backlog_description') }}</label>
                                            <textarea name="backlog_description" placeholder="Enter backlog_description" class="editor textarea" cols="30"
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

        <div class="row" style="margin-top: 20px" id="backlog-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('backlog_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $backlogs->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_backlog') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_project" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search backlog') }}"
                                            aria-label="Search orders" value="{{ $filter_project }}">

                                        <input id="" type="hidden" name="filter_staff_list"
                                            class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff task') }}"
                                            aria-label="Search orders" value="{{ $filter_staff_list }}">

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
                                onchange="location.href='{{ route('admin.backlog.list') }}/?filter_project='+this.value+'&filter_staff_list={{ $filter_staff_list }}'">
                                <option value="0" {{ $filter_project == '' ? 'selected' : '' }}>Select Project</option>
                                @foreach ($projects as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_project == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.backlog.list') }}/?filter_project={{ $filter_project }}&filter_staff_list='+this.value+''">
                                <option value="0" {{ $filter_staff_list == '' ? 'selected' : '' }}>Select Assigned To
                                </option>
                                @foreach ($staff_list_f as $b)
                                    <option value="{{ $b['id'] }}"
                                        {{ $filter_staff_list == $b['id'] ? 'selected' : '' }}>{{ $b['name'] }}</option>
                                @endforeach
                            </select>
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
                                        @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th>CEO Approval</th>
                                        @endif
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Project</th>
                                        <th>Sprint</th>
                                        <th>Name</th>
                                        <th>Estimated Time <br>(in mins)</th>
                                        <th>Added By</th>
                                        <th>Assigned To</th>
                                        <th>Taken By</th>
                                        <th>Status</th>
                                        <th>Approval</th>
                                    </tr>
                                </thead>
                                @foreach ($backlogs as $key => $backlog)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $backlogs->firstItem() + $key }}</td>
                                            @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="ceo_approval"
                                                            id="{{ $backlog->tsid }}" <?php if ($backlog->ceo_approval == 1) {
                                                                echo 'checked';
                                                            } ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            @endif
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.backlog.view', [$backlog['tsid']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                @if ($user_id == $backlog->user_id || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.backlog.edit', [$backlog['tsid']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $backlog['tsid'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif

                                                @if ($backlog->status == 0 && $backlog['ceo_approval'] == 1 && $user_type != 'PRODUCT_OWNER' && $user_type != 'CLIENT')
                                                    <br>
                                                    <a style="width:100%;margin-top:10px;"
                                                        class="btn btn-success btn-sm add_to_task"
                                                        title="{{ \App\CPU\translate('add_to_task') }}"
                                                        style="cursor: pointer;" id="{{ $backlog['tsid'] }}">
                                                        Add To Task
                                                    </a>
                                                @endif
                                            </td>

                                            <td>{{ $backlog->project_name }}</td>
                                            <td>{{ $backlog->sprint_name }}</td>
                                            <td>{{ $backlog->backlog_name }}</td>
                                            <td>
                                                <?php if(($user_type == 'CEO' || $user_type == 'TEAM_LEAD') && $backlog->ceo_approval == 0){ ?>
                                                <div class="form-group">
                                                    <input style="background-color:#d1d1d1;"
                                                        class="time_edit form-control" id="{{ $backlog->tsid }}"
                                                        type="number" value="{{ $backlog->estimated_time }}"
                                                        name="update_estimated_time" class="form-control">
                                                </div>
                                                <?php } else { ?>
                                                {{ $backlog->estimated_time }}
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <b><i>{{ \App\User::where(['id' => $backlog->user_id])->first()->name }}
                                                        ({{ \App\User::where(['id' => $backlog->user_id])->first()->user_type }})</i></b>
                                            </td>
                                            <td>
                                                @if (\App\User::where(['id' => $backlog->backlog_assigned_user_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $backlog->backlog_assigned_user_id])->first()->name }}
                                                            ({{ \App\User::where(['id' => $backlog->backlog_assigned_user_id])->first()->user_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                @if (\App\User::where(['id' => $backlog->backlog_taken_user_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $backlog->backlog_taken_user_id])->first()->name }}
                                                            ({{ \App\User::where(['id' => $backlog->backlog_taken_user_id])->first()->user_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                <?php if($backlog['status'] == 1){ ?>
                                                <b
                                                    style="color:green;"><i>{{ \App\Model\Task::where(['id' => $backlog->assigned_task_id])->first() ? \App\Model\Task::where(['id' => $backlog->assigned_task_id])->first()->task_status : '' }}</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Started</i></b>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                CEO :
                                                <?php if($backlog['ceo_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $backlogs->links() }}
                    </div>

                    @if (count($backlogs) == 0)
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
        $('#main-backlog-add').on('click', function() {
            $('#main-backlog').show();
        });

        $('.cancel').on('click', function() {
            $('.backlog_form').attr('action', "{{ route('admin.backlog.store') }}");
            $('#main-backlog').hide();
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_backlog') }}?",
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
                        url: "{{ route('admin.backlog.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('backlog_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.add_to_task', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "Are you sure want to add this backlog to today's task ?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('add_to_task') }}!',
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
                        url: "{{ route('admin.backlog.add_to_task') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('backlog_add_to_task_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
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
                url: "{{ route('admin.backlog.ceo_approval') }}",
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

        $(document).on('change', '.time_edit', function() {
            var id = $(this).attr("id");
            var time_edit = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.backlog.time_edit') }}",
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

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().val(data.sprint_name);
                },
            });
        }
    </script>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
