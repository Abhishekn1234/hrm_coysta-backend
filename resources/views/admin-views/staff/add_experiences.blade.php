@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('experience certificates'))
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
                <li class="breadcrumb-item">{{ \App\CPU\translate('Add new experiences') }}</li>
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
                        <form action="{{ route('admin.staff.add_experiences', [$staff['id']]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="employement_start_date">{{ \App\CPU\translate('employement_start_date') }}</label>
                                            <input placeholder="Enter employement_start_date" type="date"
                                                name="employement_start_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="employement_end_date">{{ \App\CPU\translate('employement_end_date') }}</label>
                                            <input placeholder="Enter employement_end_date" type="date"
                                                name="employement_end_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                for="experience_description">{{ \App\CPU\translate('experience_description') }}</label>
                                            <textarea name="experience_description" placeholder="Enter experience_description" class="editor textarea"
                                                cols="30" rows="10" required></textarea>
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
                        <h5>{{ \App\CPU\translate('Iiem') }} {{ \App\CPU\translate('Table') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ \App\CPU\translate('sl') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('action') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('employement_start_date') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('employement_end_date') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('experience_description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($letters as $k => $de_p)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ trans('Delete') }}" class="btn btn-danger btn-sm delete"
                                                    id="{{ $de_p->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>

                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.staff.edit_experiences', [$staff['id'], $de_p->id]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>

                                                <br>

                                                <a title="{{ \App\CPU\translate('View') }}" target="_blank"
                                                    style="color:white;margin-top:10px;" class="btn btn-success btn-sm"
                                                    href="{{ route('admin.staff.generate_experience_certificate', [$de_p->staff_id, $de_p->id]) }}">
                                                    Experience Certificate
                                                </a>
                                            </td>
                                            <td>{{ date('d M Y', strtotime($de_p->employement_start_date)) }}</td>
                                            <td>{{ date('d M Y', strtotime($de_p->employement_end_date)) }}</td>
                                            <td>{!! $de_p->experience_description !!}</td>
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
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_experiences') }}?",
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
                        url: "{{ route('admin.staff.delete_experiences') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('experiences_removed_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
