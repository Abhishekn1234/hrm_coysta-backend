@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('termination_letters'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('staff') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('Edit termination_letters') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 text-black-50">{{ $staff['name'] }}</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.staff.edit_termination_letters', [$staff['id'], $letters->id]) }}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label
                                                for="termination_date">{{ \App\CPU\translate('termination_date') }}</label>
                                            <input placeholder="Enter termination_date" type="date"
                                                name="termination_date" value="{{ $letters->termination_date }}"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label
                                                for="last_working_date">{{ \App\CPU\translate('last_working_date') }}</label>
                                            <input placeholder="Enter last_working_date" type="date"
                                                name="last_working_date" value="{{ $letters->last_working_date }}"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="notice_period">{{ \App\CPU\translate('notice_period') }}</label>
                                            <input placeholder="Enter notice_period" type="text" name="notice_period"
                                                value="{{ $letters->notice_period }}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="reason">{{ \App\CPU\translate('reason') }}</label>
                                            <textarea name="reason" placeholder="Enter reason" class="editor textarea" cols="30" rows="10" required>{!! $letters->reason !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="settlement_process">{{ \App\CPU\translate('settlement_process') }}</label>
                                            <textarea name="settlement_process" placeholder="Enter settlement_process" class="editor textarea" cols="30"
                                                rows="10" required>{!! $letters->settlement_process !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('Edit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
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
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_termination_letters') }}?",
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
                        url: "{{ route('admin.staff.delete_termination_letters') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('termination_letters_removed_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
