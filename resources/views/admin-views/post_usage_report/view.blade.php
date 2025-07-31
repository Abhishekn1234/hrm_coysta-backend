@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('post_usage_report'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('post_usage_report') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row" style="margin-top: 20px" id="post_usage_report-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('post_usage_report') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.post_usage_report.list') }}/?filter_project='+this.value+'&filter_from_date={{ $filter_from_date }}&filter_to_date={{ $filter_to_date }}'">
                                <option value="0" {{ $filter_project == '' ? 'selected' : '' }}>Select Project</option>
                                @foreach ($projects as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_project == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <input placeholder="Enter name" type="date" name="filter_from_date"
                                value="{{ $filter_from_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.post_usage_report.list') }}/?filter_project={{ $filter_project }}&filter_from_date='+this.value+'&filter_to_date={{ $filter_to_date }}'">
                        </div>

                        <div class="col-12 mt-1 col-md-4 col-lg-4">
                            <input placeholder="Enter name" type="date" name="filter_to_date"
                                value="{{ $filter_to_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.post_usage_report.list') }}/?filter_project={{ $filter_project }}&filter_from_date={{ $filter_from_date }}&filter_to_date='+this.value+''">
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
                                        <th colspan="6">
                                            <h1 style="text-align:center;"><i>Post Usage Report
                                                    {{ $filter_from_date != '' ? ' from ' . date('d M Y', strtotime($filter_from_date)) : '' }}
                                                    {{ $filter_to_date != '' ? ' to ' . date('d M Y', strtotime($filter_to_date)) : '' }}</i>
                                            </h1>
                                        </th>
                                    </tr>
                                </thead>

                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('sl') }}</th>
                                        <th>Project Name</th>
                                        <th>Total Minutes</th>
                                        <th>Total Hours</th>
                                        <th>Total Cost in $</th>
                                    </tr>
                                </thead>

                                @foreach ($post_usage_reports as $key => $post_usage_report)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $post_usage_reports->firstItem() + $key }}</td>
                                            <td>{{ $post_usage_report->project_name }}</td>

                                            <?php
                                            $minutes = floor($post_usage_report->total_tracked_actual_time_taken);
                                            $seconds = round(($post_usage_report->total_tracked_actual_time_taken - $minutes) * 60);
                                            $formatted_min = $minutes . ' min ' . $seconds . ' sec';
                                            ?>

                                            <td>{{ $formatted_min }}</td>

                                            <!--<td>{{ $post_usage_report->total_tracked_actual_time_taken }}</td>-->

                                            <?php
                                            $hours = floor($post_usage_report->total_tracked_actual_time_taken / 60);
                                            $minutes = $post_usage_report->total_tracked_actual_time_taken % 60;
                                            $formatted_time = $hours . ' hr ' . $minutes . ' min';
                                            ?>
                                            <td>{{ $formatted_time }}</td>

                                            @php(
    $tasksss = \App\Model\Task::select('tasks.*', 'users.hourly_rate', DB::raw('(tasks.tracked_actual_time_taken /60 * users.hourly_rate) as total_cost'))->leftJoin('users', 'users.id', '=', 'tasks.user_id')->where(['task_status' => 'Completed', 'project_id' => $post_usage_report->project_id]),
)

                                            <?php
                                            if ($filter_from_date != '') {
                                                $tasksss = $tasksss->where('date', '>=', $filter_from_date);
                                            }
                                            
                                            if ($filter_to_date != '') {
                                                $tasksss = $tasksss->where('date', '<=', $filter_to_date);
                                            }
                                            
                                            $total_cost = 0;
                                            foreach ($tasksss->get() as $f) {
                                                $total_cost = $total_cost + $f->total_cost;
                                            }
                                            ?>

                                            <td>{{ number_format($total_cost, 2) }} $</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $post_usage_reports->links() }}
                    </div>

                    @if (count($post_usage_reports) == 0)
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
