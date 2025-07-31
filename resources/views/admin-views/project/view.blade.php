@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('project'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('project') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if (
            $user_type == 'ADMIN' ||
                $user_type == 'CEO' ||
                $user_type == 'TEAM_LEAD' ||
                $user_type == 'SCRUM_MASTER' ||
                $user_type == 'HR')
            <div class="row">
                <div class="col-md-12" id="project-btn">
                    <button id="main-project-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_project') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-project"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('project_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.project.store') }}" method="post" enctype="multipart/form-data"
                            class="project_form">
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
                                            <label for="project_name">{{ \App\CPU\translate('project_name') }}</label>
                                            <input placeholder="Enter project_name" type="text" name="project_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="project_description">{{ \App\CPU\translate('project_description') }}</label>
                                            <textarea name="project_description" placeholder="Enter project_description" class="editor textarea" cols="30"
                                                rows="10" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="project_starting_date">{{ \App\CPU\translate('project_starting_date') }}</label>
                                            <input placeholder="Enter project_starting_date" type="date"
                                                name="project_starting_date" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="expected_release_date">{{ \App\CPU\translate('expected_release_date') }}</label>
                                            <input placeholder="Enter expected_release_date" type="date"
                                                name="expected_release_date" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="deadline">{{ \App\CPU\translate('deadline') }}</label>
                                            <input placeholder="Enter deadline" type="date" name="deadline"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="product_owner_id">{{ \App\CPU\translate('Product Owner') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="product_owner_id" required>
                                                <option value="{{ null }}" selected disabled>Select Product Owner
                                                </option>
                                                @foreach ($owner as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }} (
                                                        {{ $b['designation'] }} )</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="staffs" style="padding-bottom: 3px">Staffs</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="choice_staffs[]" id="choice_staffs" multiple="multiple" required>
                                                @foreach ($staff_list as $key => $a)
                                                    <option value="{{ $a['id'] }}">{{ $a['name'] }} (
                                                        {{ $a['user_type'] }} )</option>
                                                @endforeach
                                            </select>
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

        <div class="row" style="margin-top: 20px" id="project-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('project_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $projects->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_project') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('sl') }}</th>
                                        <th>Project Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Name</th>
                                        <th>Starting Date</th>
                                        <th>Expected Release Date</th>
                                        <th>Deadline</th>

                                        @if (
                                            $user_type == 'ADMIN' ||
                                                $user_type == 'CEO' ||
                                                $user_type == 'TEAM_LEAD' ||
                                                $user_type == 'SCRUM_MASTER' ||
                                                $user_type == 'HR')
                                            <th>Status</th>
                                        @endif

                                    </tr>
                                </thead>
                                @foreach ($projects as $key => $project)
                                    <tbody>
                                        <tr>
                                            <td>{{ $projects->firstItem() + $key }}</td>
                                            <td>{{ $project->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.project.view', [$project['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                @if ($project['id'] != '1' && $project['id'] != '2')
                                                    @if (
                                                        $user_type == 'ADMIN' ||
                                                            $user_type == 'CEO' ||
                                                            $user_type == 'TEAM_LEAD' ||
                                                            $user_type == 'SCRUM_MASTER' ||
                                                            $user_type == 'HR')
                                                        <a class="btn btn-primary btn-sm edit"
                                                            title="{{ \App\CPU\translate('Edit') }}"
                                                            href="{{ route('admin.project.edit', [$project['id']]) }}"
                                                            style="cursor: pointer;">
                                                            <i class="tio-edit"></i>
                                                        </a>
                                                        <a class="btn btn-danger btn-sm delete"
                                                            title="{{ \App\CPU\translate('Delete') }}"
                                                            style="cursor: pointer;" id="{{ $project['id'] }}">
                                                            <i class="tio-add-to-trash"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $project->project_name }}</td>
                                            <td>{{ $project->project_starting_date }}</td>
                                            <td>{{ $project->expected_release_date }}</td>
                                            <td>{{ $project->deadline }}</td>

                                            @if (
                                                $user_type == 'ADMIN' ||
                                                    $user_type == 'CEO' ||
                                                    $user_type == 'TEAM_LEAD' ||
                                                    $user_type == 'SCRUM_MASTER' ||
                                                    $user_type == 'HR')
                                                <td>
                                                    @if ($project['id'] != '1')
                                                        <label class="switch">
                                                            <input type="checkbox" class="status"
                                                                id="{{ $project->id }}" <?php if ($project->status == 1) {
                                                                    echo 'checked';
                                                                } ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    @endif

                                                    @if ($project['id'] == '1')
                                                        <b><i>Active</i></b>
                                                    @endif
                                                </td>
                                            @endif


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $projects->links() }}
                    </div>

                    @if (count($projects) == 0)
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
        $('#main-project-add').on('click', function() {
            $('#main-project').show();
        });

        $('.cancel').on('click', function() {
            $('.project_form').attr('action', "{{ route('admin.project.store') }}");
            $('#main-project').hide();
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
                url: "{{ route('admin.project.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('project_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('project_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_project') }}?",
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
                        url: "{{ route('admin.project.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('project_deleted_successfully') }}');
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
