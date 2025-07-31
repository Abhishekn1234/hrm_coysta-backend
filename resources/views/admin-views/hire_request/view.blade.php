@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('hire_request'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('hire_request') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="hire_request-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('hire_request_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $hire_requests->total() }})</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $user_id = auth('customer')->user()->id; ?>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.hire_request.list') }}/?filter_client_list='+this.value+'&filter_staff_list={{ $filter_staff_list }}'">
                                <option value="0" {{ $filter_client_list == '' ? 'selected' : '' }}>Select Client</option>
                                @foreach ($client_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_client_list == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.hire_request.list') }}/?filter_client_list={{ $filter_client_list }}&filter_staff_list='+this.value+''">
                                <option value="0" {{ $filter_staff_list == '' ? 'selected' : '' }}>Select Staff</option>
                                @foreach ($staff_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_staff_list == $b['id'] ? 'selected' : '' }}>
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
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}</th>
                                        <th>Client</th>
                                        <th>Staff</th>
                                        <th>Request Date</th>

                                    </tr>
                                </thead>
                                @foreach ($hire_requests as $key => $hire_request)
                                    <tbody>
                                        <tr>
                                            <td>{{ $hire_request->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.hire_request.view', [$hire_request->client_id]) }}">
                                                    <i class="tio-visible"></i> Client
                                                </a>

                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-primary btn-sm"
                                                    href="{{ route('admin.hire_request.view', [$hire_request->staff_id]) }}">
                                                    <i class="tio-visible"></i> Staff
                                                </a>
                                            </td>
                                            <td>
                                                <b><i>{{ \App\User::where(['id' => $hire_request->client_id])->first()->name }}</i></b>
                                            </td>
                                            <td>
                                                <b><i>{{ \App\User::where(['id' => $hire_request->staff_id])->first()->name }}
                                                        ({{ \App\User::where(['id' => $hire_request->staff_id])->first()->user_type }})</i></b>
                                            </td>
                                            <td>{{ date('d M Y h:i:s A', strtotime($hire_request->created_at)) }}</td>

                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $hire_requests->links() }}
                    </div>

                    @if (count($hire_requests) == 0)
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

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
