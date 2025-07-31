@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('staff'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('staff') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row">
            <div class="col-md-12" id="staff-btn">
                <button id="main-staff-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                    {{ \App\CPU\translate('add_staff') }}</button>

                @if ($user_type == 'TEAM_LEAD' || $user_type == 'CEO')
                    <button id="main_staff_send" class="btn btn-danger">Whatsapp/Email</button>

                    <div class="row pt-4" id="staff_send"
                        style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Bulk Message
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" id="multi_staffs_id" hidden
                                                name="multi_staffs_id">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="bulk_message">{{ \App\CPU\translate('Message') }}</label>
                                            <textarea name="bulk_message" id="bulk_message" placeholder="Enter message" class="form-control" cols="30"
                                                rows="5" required></textarea>
                                        </div>

                                        <div class="card-footer">
                                            <a class="btn btn-success multi_Whatsapp" title="Whatsapp"
                                                style="cursor: pointer;">Whatsapp</a>
                                            <a class="btn btn-primary multi_Email" title="Email"
                                                style="cursor: pointer;">Email</a>
                                            <a
                                                class="btn btn-secondary text-white cancel_send">{{ \App\CPU\translate('Cancel') }}</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row pt-4" id="main-staff"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('staff_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.staff.store') }}" method="post" enctype="multipart/form-data"
                            class="staff_form">
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
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">{{ \App\CPU\translate('email') }}</label>
                                            <input placeholder="Enter email" type="email" name="email"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">{{ \App\CPU\translate('password') }}</label>
                                            <input placeholder="Enter password" type="password" name="password"
                                                class="form-control" autocomplete="new-password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">{{ \App\CPU\translate('phone') }}</label>
                                            <input placeholder="Enter phone" type="text" name="phone"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="place">{{ \App\CPU\translate('place') }}</label>
                                            <input placeholder="Enter place" type="text" name="place"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">{{ \App\CPU\translate('address') }}</label>
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10"
                                                required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">{{ \App\CPU\translate('gender') }}</label>
                                            <select name="gender" class="form-control" id="type_form" required>
                                                <option value="" selected disabled>Select gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Others</option>
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
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification') }}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience') }}</label>
                                            <input placeholder="Enter experience" type="text" name="experience"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="expertise">{{ \App\CPU\translate('expertise') }}</label>
                                            <input placeholder="Enter expertise" type="text" name="expertise"
                                                class="form-control" required>
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

        <div class="row" style="margin-top: 20px" id="staff-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('staff_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $staffs->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_staff') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_user_type"
                                            class="form-control" placeholder="{{ \App\CPU\translate('Search staff') }}"
                                            aria-label="Search orders" value="{{ $filter_user_type }}">

                                        <input id="" type="hidden" name="filter_gender" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search staff') }}"
                                            aria-label="Search orders" value="{{ $filter_gender }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.staff.list') }}/?filter_user_type='+this.value+'&filter_gender={{ $filter_gender }}'">
                                <option value="" {{ $filter_user_type == '' ? 'selected' : '' }}>Select User Type
                                </option>
                                <option value="CEO" {{ $filter_user_type == 'CEO' ? 'selected' : '' }}>CEO</option>
                                <option value="SCRUM_MASTER" {{ $filter_user_type == 'SCRUM_MASTER' ? 'selected' : '' }}>
                                    SCRUM_MASTER</option>
                                <option value="HR" {{ $filter_user_type == 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="PRODUCT_OWNER" {{ $filter_user_type == 'PRODUCT_OWNER' ? 'selected' : '' }}>
                                    PRODUCT_OWNER</option>
                                <option value="TEAM_LEAD" {{ $filter_user_type == 'TEAM_LEAD' ? 'selected' : '' }}>TEAM_LEAD
                                </option>
                                <option value="TECHNICAL_LEAD" {{ $filter_user_type == 'TECHNICAL_LEAD' ? 'selected' : '' }}>
                                    TECHNICAL_LEAD</option>
                                <option value="QAQC" {{ $filter_user_type == 'QAQC' ? 'selected' : '' }}>QAQC</option>
                                <option value="MARKETING_MANAGER"
                                    {{ $filter_user_type == 'MARKETING_MANAGER' ? 'selected' : '' }}>MARKETING_MANAGER</option>
                                <option value="STAFF" {{ $filter_user_type == 'STAFF' ? 'selected' : '' }}>STAFF</option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.staff.list') }}/?filter_user_type={{ $filter_user_type }}&filter_gender='+this.value+''">
                                <option value="" {{ $filter_gender == '' ? 'selected' : '' }}>Select Gender</option>
                                <option value="Male" {{ $filter_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $filter_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Others" {{ $filter_gender == 'Others' ? 'selected' : '' }}>Others</option>
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

                                        <th style="text-align:center;">
                                            Select All
                                            <br>
                                            <input class="form-control-sm" style="width:20px;" type="checkbox"
                                                id="selectAll">
                                        </th>

                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Status</th>
                                        <th>Letters</th>
                                        <th>Certificates</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Join Type</th>
                                        <th>Employment Type</th>
                                        <th>Reports To</th>


                                    </tr>
                                </thead>
                                @foreach ($staffs as $key => $staff)
                                    <tbody>
                                        <tr>
                                            <td>{{ $staff->id }}</td>
                                            <td style="text-align:center;">
                                                <input class="form-control-sm staff-checkbox" style="width:20px;"
                                                    type="checkbox" name="staff_checkbox[]" value="{{ $staff['id'] }}">
                                            </td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.staff.view', [$staff['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.staff.edit', [$staff['id']]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $staff['id'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="status" id="{{ $staff->id }}"
                                                        <?php if ($staff->status == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" target="_blank"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-success btn-sm"
                                                    href="{{ route('admin.staff.add_offer_letters', [$staff['id']]) }}">
                                                    Offer Letter
                                                </a>

                                                <br>

                                                <a title="{{ \App\CPU\translate('View') }}" target="_blank"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-info btn-sm"
                                                    href="{{ route('admin.staff.add_releiving_letters', [$staff['id']]) }}">
                                                    Releiving Letter
                                                </a>

                                                <br>

                                                <a title="{{ \App\CPU\translate('View') }}" target="_blank"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-warning btn-sm"
                                                    href="{{ route('admin.staff.add_warning_letters', [$staff['id']]) }}">
                                                    Warning Letter
                                                </a>

                                                <br>

                                                <a title="{{ \App\CPU\translate('View') }}" target="_blank"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-danger btn-sm"
                                                    href="{{ route('admin.staff.add_termination_letters', [$staff['id']]) }}">
                                                    Termination Letter
                                                </a>


                                            </td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-info btn-sm"
                                                    href="{{ route('admin.staff.add_experiences', [$staff['id']]) }}">
                                                    Experience Certificate
                                                </a>

                                                <br>

                                                <a title="{{ \App\CPU\translate('View') }}"
                                                    style="color:white;margin-top:10px;width:100%;"
                                                    class="btn btn-primary btn-sm"
                                                    href="{{ route('admin.staff.add_certificates', [$staff['id']]) }}">
                                                    Add Certificate
                                                </a>
                                            </td>
                                            <td>
                                                <img width="80"
                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/banner') }}/{{ $staff['image'] }}">
                                            </td>
                                            <td>{{ $staff->name }} <br>( {{ $staff->user_type }} )</td>
                                            <td>{{ $staff->email }}</td>
                                            <td>{{ $staff->phone }}</td>
                                            <td>{{ $staff->join_type }}</td>
                                            <td>{{ $staff->employment_type }}</td>

                                            @php($reports_to = \App\User::where(['status' => '1', 'id' => $staff->reports_to])->first())

                                            <?php
                                            if ($reports_to) {
                                                $reports_to_name = $reports_to->name . ' ( ' . $reports_to->user_type . ' )';
                                            } else {
                                                $reports_to_name = '';
                                            }
                                            ?>

                                            <td><b><i>{{ $reports_to_name }}</i></b></td>




                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $staffs->links() }}
                    </div>

                    @if (count($staffs) == 0)
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
        $('#main-staff-add').on('click', function() {
            $('#main-staff').show();
        });

        $('.cancel').on('click', function() {
            $('.staff_form').attr('action', "{{ route('admin.staff.store') }}");
            $('#main-staff').hide();
        });

        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.staff.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('Staff_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('Staff_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_staff') }}?",
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
                        url: "{{ route('admin.staff.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('staff_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $('#main_staff_send').on('click', function() {
            $('#staff_send').show();
        });

        $('.cancel_send').on('click', function() {
            $('#staff_send').hide();
        });

        $(document).on('click', '.multi_Whatsapp', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_staffs_id = $('#multi_staffs_id').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected staffs ?",
                    // text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Proceed') }}!',
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
                            url: "{{ route('admin.staff.send_whatsapp') }}",
                            method: 'POST',
                            data: {
                                multi_staffs_id: multi_staffs_id,
                            },
                            success: function(data) {

                                var bulk_message = $('#bulk_message').val();

                                const numbers = data
                                .numbers; // PHP numbers array passed to JavaScript
                                const names = data
                                .names; // PHP numbers array passed to JavaScript
                                const message =
                                bulk_message; // PHP message string passed to JavaScript
                                const delay = 5000; // Delay between sending messages


                                const sendMessage = async (number, message) => {
                                    const url =
                                        `https://web.whatsapp.com/send?phone=${number}&text=${encodeURIComponent(message)}`;
                                    const newWindow = window.open(url, '_blank');

                                    return new Promise((resolve, reject) => {
                                        if (!newWindow) {
                                            reject(new Error(
                                                'Popup blocked, please allow popups.'
                                                ));
                                            return;
                                        }

                                        const waitForSendButton = setInterval(
                                        () => {
                                                // const sendButton = document.querySelector('[data-tab="11"]');
                                                const sendButton = newWindow
                                                    .document.querySelector(
                                                        '[data-tab="11"]'
                                                        ); // Correctly target the new window's document

                                                if (sendButton) {
                                                    sendButton.click();
                                                    clearInterval(
                                                        waitForSendButton
                                                        );
                                                    resolve();
                                                }
                                            }, 1000);

                                        setTimeout(() => {
                                            clearInterval(
                                                waitForSendButton);
                                            reject(new Error(
                                                'Timed out waiting for send button.'
                                                ));
                                        }, 5000);
                                    });
                                };

                                (async () => {
                                    // Loop through each number in the numbers array
                                    for (let i = 0; i < numbers.length; i++) {
                                        try {
                                            const number = numbers[
                                            i]; // Get the current number from JavaScript array
                                            const name = names[i];
                                            // const personalizedMessage = `Hi Mr. ${name}, <br> ${message}`;
                                            const personalizedMessage =
                                                `Hi Mr. ${name},\n${message}`;

                                            await sendMessage(number,
                                                personalizedMessage
                                                ); // Send message to each number
                                            console.log(`Message sent to ${number}`);
                                        } catch (error) {
                                            console.error(
                                                `Failed to send message to ${numbers[i]}:`,
                                                error);
                                        }
                                        await new Promise(res => setTimeout(res,
                                        delay)); // Wait before sending the next message
                                    }
                                })();

                                $('#staff_send').hide();

                                // Swal.fire({
                                //     title: '{{ \App\CPU\translate('Message Sent') }}',
                                //     text: '{{ \App\CPU\translate('The messages are being sent to selected staffs.') }}',
                                //     icon: 'success',
                                //     confirmButtonColor: '#3085d6',
                                //     confirmButtonText: '{{ \App\CPU\translate('OK') }}'
                                // });
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_Email', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_staffs_id = $('#multi_staffs_id').val();
                var bulk_message = $('#bulk_message').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected staffs ?",
                    // text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Proceed') }}!',
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
                            url: "{{ route('admin.staff.send_email') }}",
                            method: 'POST',
                            data: {
                                multi_staffs_id: multi_staffs_id,
                                bulk_message: bulk_message
                            },
                            success: function(data) {
                                $('#staff_send').hide();

                                Swal.fire({
                                    title: '{{ \App\CPU\translate('Email Sent') }}',
                                    text: '{{ \App\CPU\translate('The messages are being sent to selected staffs.') }}',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: '{{ \App\CPU\translate('OK') }}'
                                });
                            }
                        });
                    }
                })
            }
        });
    </script>

    <script>
        function validateForm() {
            var checkboxes = document.querySelectorAll('input[name="staff_checkbox[]"]');
            var checked = Array.prototype.slice.call(checkboxes).some(x => x.checked);
            if (!checked) {
                alert('Please select at least one staff.');
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('input[name="staff_checkbox[]"]');
            const checkedStaffIdsTextBox = document.getElementById('multi_staffs_id');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCheckboxes = document.querySelectorAll(
                        'input[name="staff_checkbox[]"]:checked');

                    const checkedIds = Array.from(checkedCheckboxes).map(function(checkbox) {
                        return checkbox.value;
                    });
                    checkedStaffIdsTextBox.value = checkedIds.join(',');
                });
            });
        });
    </script>

    <script>
        // Get the "Select All" checkbox
        const selectAllCheckbox = document.getElementById('selectAll');

        // Get all individual checkboxes with the class 'staff-checkbox'
        const staffCheckboxes = document.querySelectorAll('.staff-checkbox');

        // Get the hidden input field for storing selected staff IDs
        const multiStaffsIdInput = document.getElementById('multi_staffs_id');

        // Event listener for the "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            // Set all checkboxes to the same state as the "Select All" checkbox
            staffCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update the multi_staffs_id field with the selected staff IDs
            updateSelectedStaffIds(); // Make sure this is called when Select All is toggled
        });

        // Event listener for individual staff checkboxes
        staffCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Update the multi_staffs_id field whenever any checkbox is clicked
                updateSelectedStaffIds();

                // Check if all checkboxes are selected or not
                selectAllCheckbox.checked = [...staffCheckboxes].every(checkbox => checkbox.checked);

                // Indeterminate state if some but not all are selected
                selectAllCheckbox.indeterminate = [...staffCheckboxes].some(checkbox => checkbox.checked) &&
                    !selectAllCheckbox.checked;
            });
        });

        // Function to update the hidden input with selected staff IDs
        function updateSelectedStaffIds() {
            // Get all checked checkboxes
            const checkedCheckboxes = document.querySelectorAll('input[name="staff_checkbox[]"]:checked');

            // Get the values (staff IDs) of all checked checkboxes
            const checkedIds = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

            // Join the IDs into a comma-separated string
            multiStaffsIdInput.value = checkedIds.join(','); // Update the input field
        }
    </script>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
