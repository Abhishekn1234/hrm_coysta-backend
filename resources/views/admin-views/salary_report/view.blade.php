@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('salary_report'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('salary_report') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row" style="margin-top: 20px" id="salary_report-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('salary_report') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.salary_report.list') }}/?filter_month='+this.value+'&filter_year={{ $filter_year }}'">
                                @foreach ($months as $b)
                                    <option value="{{ $b->id }}" {{ $filter_month == $b->id ? 'selected' : '' }}>
                                        {{ $b->month_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.salary_report.list') }}/?filter_month={{ $filter_month }}&filter_year='+this.value+''">
                                @for ($i = 2025; $i <= $cr_year; $i++)
                                    <option value="{{ $i }}" {{ $filter_year == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Employement Type</th>
                                        <th>Full Days</th>
                                        <th>Half Days</th>
                                        <th>Unaccountable</th>
                                        <th>Export Attendence</th>
                                    </tr>
                                </thead>

                                @foreach ($salary_reports as $key => $salary_report)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $salary_report->id }}</td>
                                            <td>{{ $salary_report->name }} ({{ $salary_report->user_type }})</td>
                                            <td>{{ $salary_report->employment_type }}</td>
                                            <td>{{ $salary_report->fds }}</td>
                                            <td>{{ $salary_report->hds }}</td>
                                            <td>{{ $salary_report->uac }}</td>

                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.salary_report.export_attendence', ['id' => $salary_report->id, 'filter_month' => $filter_month, 'filter_year' => $filter_year]) }}">
                                                    Export Attendence
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    @if (count($salary_reports) == 0)
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
