@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Staff'))
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
                <li class="breadcrumb-item">{{ \App\CPU\translate('Convert to Staff') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 text-black-50">{{ $candidate['name'] }}</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.candidate.staff_convert', [$candidate['id']]) }}"
                            enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <?php $today_date = date('Y-m-d'); ?>
                                    <?php $today_time = date('h:i:s'); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="join_date">{{ \App\CPU\translate('join_date') }}</label>
                                            <input placeholder="Enter join_date" type="date" name="join_date"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('Image') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="image" id="mbimageFileUploader"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="mbimageFileUploader">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="user_type">{{ \App\CPU\translate('User Type') }}</label>
                                            <select name="user_type" class="form-control" id="type_form" required>
                                                <option value="" selected disabled>Select User Type</option>
                                                <option value="CEO">CEO</option>
                                                <option value="SCRUM_MASTER">SCRUM_MASTER</option>
                                                <option value="HR">HR</option>
                                                <option value="PRODUCT_OWNER">PRODUCT_OWNER</option>
                                                <option value="TEAM_LEAD">TEAM_LEAD</option>
                                                <option value="TECHNICAL_LEAD">TECHNICAL_LEAD</option>
                                                <option value="QAQC">QAQC</option>
                                                <option value="MARKETING_MANAGER">MARKETING_MANAGER</option>
                                                <option value="STAFF">STAFF</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('name') }}</label>
                                            <input placeholder="Enter name" type="text" name="name"
                                                value="{{ $candidate['name'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">{{ \App\CPU\translate('email') }}</label>
                                            <input placeholder="Enter email" type="email" name="email"
                                                value="{{ $candidate['email'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">{{ \App\CPU\translate('password') }}</label>
                                            <input placeholder="Enter password" type="password" name="password"
                                                class="form-control" autocomplete="new-password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">{{ \App\CPU\translate('phone') }}</label>
                                            <input placeholder="Enter phone" type="text" name="phone"
                                                value="{{ $candidate['phone'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="place">{{ \App\CPU\translate('place') }}</label>
                                            <input placeholder="Enter place" type="text" name="place"
                                                value="{{ $candidate['place'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">{{ \App\CPU\translate('address') }}</label>
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10" required>{!! $candidate['address'] !!}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">{{ \App\CPU\translate('gender') }}</label>
                                            <select name="gender" class="form-control" id="type_form"
                                                value="{{ $candidate['gender'] }}" required>
                                                <option value="Male"
                                                    {{ $candidate['gender'] == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female"
                                                    {{ $candidate['gender'] == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Others"
                                                    {{ $candidate['gender'] == 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="work_location">{{ \App\CPU\translate('work_location') }}</label>
                                            <input placeholder="Enter work_location" type="text" name="work_location"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="employment_type">{{ \App\CPU\translate('employment_type') }}</label>
                                            <select name="employment_type" class="form-control" id="type_form" required>
                                                <option value="" selected disabled>Select employment_type</option>
                                                <option value="FULL_TIME">FULL_TIME</option>
                                                <option value="PARTIAL">PARTIAL</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="hourly_rate">{{ \App\CPU\translate('hourly_rate in $') }}</label>
                                            <input placeholder="Enter hourly_rate in $" type="number" name="hourly_rate"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="monthly_rate">{{ \App\CPU\translate('monthly_rate in $') }}</label>
                                            <input placeholder="Enter monthly_rate in $" type="number"
                                                name="monthly_rate" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="annual_ctc">{{ \App\CPU\translate('annual_ctc') }}</label>
                                            <input placeholder="Enter annual_ctc" type="number" name="annual_ctc"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="basic_salary">{{ \App\CPU\translate('basic_salary') }}</label>
                                            <input placeholder="Enter basic_salary" type="number" name="basic_salary"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="hra">{{ \App\CPU\translate('hra') }}</label>
                                            <input placeholder="Enter hra" type="number" name="hra"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="special_allowances">{{ \App\CPU\translate('special_allowances') }}</label>
                                            <input placeholder="Enter special_allowances" type="number"
                                                name="special_allowances" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="probation_period">{{ \App\CPU\translate('probation_period') }}</label>
                                            <input placeholder="Enter probation_period" type="text"
                                                name="probation_period" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="date_of_birth">{{ \App\CPU\translate('date_of_birth') }}</label>
                                            <input placeholder="Enter date_of_birth" type="date" name="date_of_birth"
                                                value="{{ $candidate['date_of_birth'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification') }}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification"
                                                value="{{ $candidate['qualification'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience') }}</label>
                                            <input placeholder="Enter experience" type="text" name="experience"
                                                value="{{ $candidate['experience'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="expertise">{{ \App\CPU\translate('expertise') }}</label>
                                            <input placeholder="Enter expertise" type="text" name="expertise"
                                                value="{{ $candidate['skills'] }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="designation">{{ \App\CPU\translate('designation') }}</label>
                                            <input placeholder="Enter designation" type="text" name="designation"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="role">{{ \App\CPU\translate('role') }}</label>
                                            <input placeholder="Enter role" type="text" name="role"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="reports_to">{{ \App\CPU\translate('Reports To') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="reports_to" required>
                                                <option value="{{ null }}" selected disabled>Select Reports To
                                                </option>
                                                @foreach ($reports_to as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }} (
                                                        {{ $b['user_type'] }} )</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <center>
                                            <img style="width: auto;border: 1px solid; border-radius: 10px; max-width:400px;"
                                                id="mbImageviewer"
                                                src="{{ asset('public\assets\back-end\img\400x400\img1.jpg') }}"
                                                alt="banner image" />
                                        </center>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('Convert to Staff') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
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
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
