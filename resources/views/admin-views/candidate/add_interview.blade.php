@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('interview'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('candidate') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('Add new interview') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 text-black-50">{{ $candidate['name'] }}</h1>
                    </div>

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

                    <div class="card-body">
                        <form action="{{ route('admin.candidate.add_interview', [$candidate['id']]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <?php $today_date = date('Y-m-d'); ?>
                                    <?php $today_time = date('H:i:s'); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="interview_date">{{ \App\CPU\translate('interview_date') }}</label>
                                            <input value="{{ $today_date }}" placeholder="Enter interview_date"
                                                type="date" name="interview_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="interview_time">{{ \App\CPU\translate('interview_time') }}</label>
                                            <input value="{{ $today_time }}" placeholder="Enter interview_time"
                                                type="time" name="interview_time" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="interviewer_ids" style="padding-bottom: 3px">Interviewers</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="interviewer_ids[]" id="interviewer_ids" multiple="multiple" required>
                                                @foreach ($interviewer_list as $key => $a)
                                                    <option value="{{ $a['id'] }}">{{ $a['name'] }} (
                                                        {{ $a['user_type'] }} )</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="google_meet_link">{{ \App\CPU\translate('google_meet_link') }}</label>
                                            <input placeholder="Enter google_meet_link" type="text"
                                                name="google_meet_link" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ \App\CPU\translate('interview') }} {{ \App\CPU\translate('Table') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ \App\CPU\translate('sl') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('action') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('date') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('time') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('Interviewers') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('google_meet_link') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($interviews as $k => $de_p)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>
                                                <a title="{{ trans('Delete') }}" class="btn btn-danger btn-sm delete"
                                                    id="{{ $de_p->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                            <td>{{ date('d M Y', strtotime($de_p->interview_date)) }}</td>
                                            <td>{{ date('h:i:s A', strtotime($de_p->interview_time)) }}</td>
                                            <td>
                                                <ul>
                                                    @foreach (json_decode($de_p->interviewer_ids) as $key => $a)
                                                        <li><b><i>{{ \App\User::where(['status' => '1', 'id' => $a])->first()->name }}
                                                                    ({{ \App\User::where(['status' => '1', 'id' => $a])->first()->user_type }})</i></b>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $de_p->google_meet_link }}</td>
                                            <td>
                                                <!--{{ $de_p->interview_status }}-->

                                                <select style="width:100%;" id="{{ $de_p->id }}"
                                                    name="interview_status" class="interview_status_change form-control"
                                                    id="type_form" value="{{ $de_p->interview_status }}">
                                                    <option value="SCHEDULED"
                                                        {{ $de_p->interview_status == 'SCHEDULED' ? 'selected' : '' }}>
                                                        SCHEDULED</option>
                                                    <option value="COMPLETED"
                                                        {{ $de_p->interview_status == 'COMPLETED' ? 'selected' : '' }}>
                                                        COMPLETED</option>
                                                    <option value="REJECTED"
                                                        {{ $de_p->interview_status == 'REJECTED' ? 'selected' : '' }}>
                                                        REJECTED</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
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
        $(document).on('change', '.interview_status_change', function() {
            var id = $(this).attr("id");
            var interview_status = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.candidate.interview_status_change') }}",
                method: 'POST',
                data: {
                    id: id,
                    interview_status: interview_status
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
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_interviews') }}?",
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
                        url: "{{ route('admin.candidate.delete_interview') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('interview_removed_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
