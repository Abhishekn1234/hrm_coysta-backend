@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('certificate'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('certificate') }}</li>
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
                <div class="col-md-12" id="certificate-btn">
                    <button id="main-certificate-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_certificate') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-certificate"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('certificate_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.certificate.store') }}" method="post" enctype="multipart/form-data"
                            class="certificate_form">
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
                                            <label
                                                for="certificate_name">{{ \App\CPU\translate('certificate_name') }}</label>
                                            <input placeholder="Enter certificate_name" type="text"
                                                name="certificate_name" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="certificate_description">{{ \App\CPU\translate('certificate_description') }}</label>
                                            <textarea name="certificate_description" placeholder="Enter certificate_description" class="editor textarea"
                                                cols="30" rows="10" required></textarea>
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

        <div class="row" style="margin-top: 20px" id="certificate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('certificate_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $certificates->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_certificate') }}"
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
                                        <th>Id</th>
                                        <th>{{ \App\CPU\translate('action') }}</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                @foreach ($certificates as $key => $certificate)
                                    <tbody>
                                        <tr>
                                            <td>{{ $certificate->id }}</td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.certificate.view', [$certificate['id']]) }}">
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
                                                        href="{{ route('admin.certificate.edit', [$certificate['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                        id="{{ $certificate['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $certificate->certificate_name }}</td>

                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $certificates->links() }}
                    </div>

                    @if (count($certificates) == 0)
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
        $('#main-certificate-add').on('click', function() {
            $('#main-certificate').show();
        });

        $('.cancel').on('click', function() {
            $('.certificate_form').attr('action', "{{ route('admin.certificate.store') }}");
            $('#main-certificate').hide();
        });


        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_certificate') }}?",
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
                        url: "{{ route('admin.certificate.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('certificate_deleted_successfully') }}'
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
