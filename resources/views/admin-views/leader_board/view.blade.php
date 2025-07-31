@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('leader_board'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('leader_board') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row" style="margin-top: 20px" id="leader_board-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('leader_board') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <label>From Date</label>
                            <input placeholder="Enter name" type="date" name="filter_from_date"
                                value="{{ $filter_from_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.leader_board.list') }}/?filter_from_date='+this.value+'&filter_to_date={{ $filter_to_date }}'">
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <label>To Date</label>
                            <input placeholder="Enter name" type="date" name="filter_to_date"
                                value="{{ $filter_to_date }}" class="form-control"
                                onchange="location.href='{{ route('admin.leader_board.list') }}/?filter_from_date={{ $filter_from_date }}&filter_to_date='+this.value+''">
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
                                        <th colspan="7">
                                            <h1 style="text-align:center;"><i>Leader Board
                                                    {{ $filter_from_date != '' ? ' from ' . date('d M Y', strtotime($filter_from_date)) : '' }}
                                                    {{ $filter_to_date != '' ? ' to ' . date('d M Y', strtotime($filter_to_date)) : '' }}</i>
                                            </h1>
                                        </th>
                                    </tr>
                                </thead>

                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Employement Type</th>
                                        <th>Days Worked</th>
                                        <th>Points</th>
                                    </tr>
                                </thead>

                                @foreach ($leader_boards as $key => $leader_board)
                                    <?php $employment_type_working_hour = $leader_board->employment_type == 'FULL_TIME' ? 420 : 210; ?>
                                    <?php
                                    $FULL_ATTENDENCE = DB::table('point_settings')
                                        ->where(['items' => 'FULL_ATTENDENCE'])
                                        ->first();
                                    $NEW_SKILLS = DB::table('point_settings')
                                        ->where(['items' => 'NEW_SKILLS'])
                                        ->first();
                                    $TASK_IN_TIME = DB::table('point_settings')
                                        ->where(['items' => 'TASK_IN_TIME'])
                                        ->first();
                                    $TASK_ON_TIME = DB::table('point_settings')
                                        ->where(['items' => 'TASK_ON_TIME'])
                                        ->first();
                                    $TASK_OVER_TIME = DB::table('point_settings')
                                        ->where(['items' => 'TASK_OVER_TIME'])
                                        ->first();
                                    $TEST_CASES_NOT_ADDED = DB::table('point_settings')
                                        ->where(['items' => 'TEST_CASES_NOT_ADDED'])
                                        ->first();
                                    $WARNING_LETTER = DB::table('point_settings')
                                        ->where(['items' => 'WARNING_LETTER'])
                                        ->first();
                                    ?>

                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $leader_board->name }} ({{ $leader_board->user_type }})</td>
                                            <td>{{ $leader_board->employment_type }}</td>

                                            <?php
                                            $days = floor($leader_board->total_time_taken / $employment_type_working_hour);
                                            $hours = round(($leader_board->total_time_taken / $employment_type_working_hour - $days) * 60);
                                            $formatted_day = $days . ' days ' . $hours . ' hours';
                                            ?>

                                            <td>
                                                <?php $f_points = floor($leader_board->total_time_taken / $employment_type_working_hour / 25) * $FULL_ATTENDENCE->points; ?>
                                                {{ $formatted_day }}
                                            </td>

                                            <?php $total_points = $FULL_ATTENDENCE->type == 'PLUS' ? +$f_points : -$f_points; ?>
                                            <td>{{ $total_points }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    @if (count($leader_boards) == 0)
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
