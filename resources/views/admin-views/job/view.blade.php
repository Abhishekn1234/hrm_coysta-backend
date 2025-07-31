@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('job'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('job') }}</li>
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
                <div class="col-md-12" id="job-btn">
                    <button id="main-job-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_job') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-job"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('job_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.job.store') }}" method="post" enctype="multipart/form-data"
                            class="job_form">
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
                                            <label for="job_title">{{ \App\CPU\translate('Job Title') }}</label>
                                            <input placeholder="Enter job_title" type="text" name="job_title"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="job_description">{{ \App\CPU\translate('Job Description') }}</label>
                                            <textarea name="job_description" placeholder="Enter job_description" class="editor textarea" cols="30"
                                                rows="10" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="job_type" style="padding-bottom: 3px">Job Type</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="job_type" id="job_type" required>
                                                <option value="FULL_TIME">FULL_TIME</option>
                                                <option value="PART_TIME">PART_TIME</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="qualification_required">{{ \App\CPU\translate('Qualification Required') }}</label>
                                            <input placeholder="Enter qualification_required" type="text"
                                                name="qualification_required" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="experience_required">{{ \App\CPU\translate('Experience Required') }}</label>
                                            <input placeholder="Enter experience_required" type="text"
                                                name="experience_required" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="min_salary">{{ \App\CPU\translate('Minimum Salary') }}</label>
                                            <input placeholder="Enter min_salary" type="text" name="min_salary"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="max_salary">{{ \App\CPU\translate('Maximum Salary') }}</label>
                                            <input placeholder="Enter max_salary" type="text" name="max_salary"
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

        <div class="row" style="margin-top: 20px" id="job-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('job_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $jobs->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_job') }}"
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
                                        <th>Job Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Experience Required</th>
                                    </tr>
                                </thead>
                                @foreach ($jobs as $key => $job)
                                    <tbody>
                                        <tr>
                                            <td>{{ $job->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.job.view', [$job['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                @if (
                                                    $user_type == 'ADMIN' ||
                                                        $user_type == 'CEO' ||
                                                        $user_type == 'TEAM_LEAD' ||
                                                        $user_type == 'SCRUM_MASTER' ||
                                                        $user_type == 'HR')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.job.edit', [$job['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $job['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $job->job_title }}</td>
                                            <td>{{ $job->job_type }}</td>
                                            <td>{{ $job->experience_required }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $jobs->links() }}
                    </div>

                    @if (count($jobs) == 0)
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
        $('#main-job-add').on('click', function() {
            $('#main-job').show();
        });

        $('.cancel').on('click', function() {
            $('.job_form').attr('action', "{{ route('admin.job.store') }}");
            $('#main-job').hide();
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_job') }}?",
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
                        url: "{{ route('admin.job.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('job_deleted_successfully') }}');
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
