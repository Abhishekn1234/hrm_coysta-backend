@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('testcase'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('testcases') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="testcase-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('testcase_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $testcases->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_testcase') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_project" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search testcase') }}"
                                            aria-label="Search orders" value="{{ $filter_project }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-12 col-lg-12">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.testcase.list') }}/?filter_project='+this.value+''">
                                <option value="0" {{ $filter_project == '' ? 'selected' : '' }}>Select Project</option>
                                @foreach ($projects as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_project == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <?php $user_type = auth('customer')->user()->user_type; ?>
                    <?php $today_date = date('Y-m-d'); ?>

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
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Project</th>
                                        <th>Name</th>

                                    </tr>
                                </thead>
                                @foreach ($testcases as $key => $testcase)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $testcases->firstItem() + $key }}</td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.testcase.view', [$testcase['tsid']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.testcase.edit', [$testcase['tsid']]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>

                                            </td>
                                            <td>{{ date('d M Y', strtotime($testcase->date)) }}</td>
                                            <td>{{ $testcase->user_name }}<br>( {{ $testcase->user_user_type }} )</td>
                                            <td>{{ $testcase->project_name }}</td>
                                            <td>{{ $testcase->task_name }}</td>


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $testcases->links() }}
                    </div>

                    @if (count($testcases) == 0)
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
