@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('punching_report'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('punching_report') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row" style="margin-top: 20px" id="punching_report-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('punching_report') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <label>Date</label>
                            <input placeholder="Enter name" type="date" name="filter_date" value="{{ $filter_date }}"
                                class="form-control"
                                onchange="location.href='{{ route('admin.punching_report.list') }}/?filter_date='+this.value+'&filter_from_date=&filter_to_date='">
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <label>From Date</label>
                            <input placeholder="Enter name" type="date" name="filter_from_date"
                                value="{{ $filter_from_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.punching_report.list') }}/?filter_date=&filter_from_date='+this.value+'&filter_to_date={{ $filter_to_date }}'">
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <label>To Date</label>
                            <input placeholder="Enter name" type="date" name="filter_to_date"
                                value="{{ $filter_to_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.punching_report.list') }}/?filter_date=&filter_from_date={{ $filter_from_date }}&filter_to_date='+this.value+''">
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
                                        <th colspan="8">
                                            <h1 style="text-align:center;"><i>Punching Report
                                                    {{ $filter_date != '' ? ' of ' . date('d M Y', strtotime($filter_date)) : '' }}
                                                    {{ $filter_from_date != '' ? ' from ' . date('d M Y', strtotime($filter_from_date)) : '' }}
                                                    {{ $filter_to_date != '' ? ' to ' . date('d M Y', strtotime($filter_to_date)) : '' }}</i>
                                            </h1>
                                        </th>
                                    </tr>
                                </thead>

                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Employement<br> Type</th>
                                        @if ($user_type != 'CEO')
                                            <th>Today's<br> Working<br> Minutes</th>
                                            <th>Today's<br> Working<br> Hours</th>
                                        @endif
                                        <th>Total<br> Minutes</th>
                                        <th>Total<br> Hours</th>
                                        @if ($user_type == 'CEO' || $user_type == 'HR' || $user_type == 'TEAM_LEAD')
                                            <th>Export</th>
                                        @endif
                                    </tr>
                                </thead>

                                @foreach ($punching_reports as $key => $punching_report)
                                    <!--$employment_type_working_hour = $punching_report->employment_type == 'FULL_TIME' ? 420 : 210;-->
                                    <?php $employment_type_working_hour = $punching_report->employment_type == 'FULL_TIME' ? 450 : 225; ?>

                                    <?php
                                    $bg = 'white';
                                    $fg = 'black';
                                    
                                    if ($punching_report->total_time_taken < $employment_type_working_hour || $punching_report->today_total_time_taken < $employment_type_working_hour) {
                                        $bg = '#c10034';
                                        $fg = 'white';
                                    }
                                    ?>
                                    <style>
                                        #tbd-{{ $key }} {
                                            background-color: {{ $bg }} !important;
                                        }

                                        #tbd-{{ $key }} td {
                                            color: {{ $fg }} !important;
                                        }
                                    </style>
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $punching_report->id }}</td>
                                            <td>{{ $punching_report->name }} ({{ $punching_report->user_type }})</td>
                                            <td>{{ $punching_report->employment_type }}</td>

                                            @if ($user_type != 'CEO')
                                                <?php
                                                $minutes_today1 = floor($punching_report->today_total_time_taken);
                                                $seconds_today1 = round(($punching_report->today_total_time_taken - $minutes_today1) * 60);
                                                $formatted_min_today1 = $minutes_today1 . ' min ' . $seconds_today1 . ' sec';
                                                ?>

                                                <td>{{ $punching_report->today_total_time_taken > 0 ? $formatted_min_today1 : 0 }}
                                                </td>

                                                <?php
                                                $hours_today2 = floor($punching_report->today_total_time_taken / 60);
                                                $minutes_today2 = $punching_report->today_total_time_taken % 60;
                                                $formatted_time_today2 = $hours_today2 . ' hr ' . $minutes_today2 . ' min';
                                                ?>
                                                <td>{{ $punching_report->today_total_time_taken > 0 ? $formatted_time_today2 : 0 }}
                                                </td>
                                            @endif

                                            <?php
                                            $minutes3 = floor($punching_report->total_time_taken);
                                            $seconds3 = round(($punching_report->total_time_taken - $minutes3) * 60);
                                            $formatted_min3 = $minutes3 . ' min ' . $seconds3 . ' sec';
                                            ?>

                                            <td>{{ $punching_report->total_time_taken > 0 ? $formatted_min3 : 0 }}</td>

                                            <?php
                                            $hours4 = floor($punching_report->total_time_taken / 60);
                                            $minutes4 = $punching_report->total_time_taken % 60;
                                            $formatted_time4 = $hours4 . ' hr ' . $minutes4 . ' min';
                                            ?>
                                            <td>{{ $punching_report->total_time_taken > 0 ? $formatted_time4 : 0 }}</td>

                                            @if ($user_type == 'CEO' || $user_type == 'HR' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                        href="{{ route('admin.punching_report.export_attendence', ['id' => $punching_report->id, 'filter_date' => $filter_date, 'filter_from_date' => $filter_from_date, 'filter_to_date' => $filter_to_date]) }}">
                                                        Export
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    @if (count($punching_reports) == 0)
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
