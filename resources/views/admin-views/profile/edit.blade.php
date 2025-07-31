@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Profile Settings'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{ \App\CPU\translate('Settings') }}</h1>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">
                        <i class="tio-home mr-1"></i> {{ \App\CPU\translate('Dashboard') }}
                    </a>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-3">
                <!-- Navbar -->
                <div class="navbar-vertical navbar-expand-lg mb-3 mb-lg-5">
                    <!-- Navbar Toggle -->
                    <button type="button" class="navbar-toggler btn btn-block btn-white mb-3"
                        aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu"
                        data-toggle="collapse" data-target="#navbarVerticalNavMenu">
                        <span class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">{{ \App\CPU\translate('Nav menu') }}</span>

                            <span class="navbar-toggle-default">
                                <i class="tio-menu-hamburger"></i>
                            </span>

                            <span class="navbar-toggle-toggled">
                                <i class="tio-clear"></i>
                            </span>
                        </span>
                    </button>
                    <!-- End Navbar Toggle -->

                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <!-- Navbar Nav -->
                        <ul id="navbarSettings"
                            class="js-sticky-block js-scrollspy navbar-nav navbar-nav-lg nav-tabs card card-navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:" id="generalSection" style="color: black">
                                    <i class="tio-user-outlined nav-icon"></i>{{ \App\CPU\translate('Basic') }}
                                    {{ \App\CPU\translate('information') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:" id="passwordSection" style="color: black">
                                    <i class="tio-lock-outlined nav-icon"></i> {{ \App\CPU\translate('Password') }}
                                </a>
                            </li>
                        </ul>
                        <!-- End Navbar Nav -->
                    </div>
                </div>
                <!-- End Navbar -->
            </div>

            <div class="col-lg-9">
                <form action="{{ route('admin.profile.update', [$data->id]) }}" method="post" enctype="multipart/form-data"
                    id="admin-profile-form">
                    @csrf
                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5" id="generalDiv">
                        <!-- Profile Cover -->
                        <div class="profile-cover">
                            <div class="profile-cover-img-wrapper"></div>
                        </div>
                        <!-- End Profile Cover -->

                        <!-- Avatar -->
                        <label class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                            for="avatarUploader">
                            <img id="viewer" onerror="this.src='{{ asset('assets/back-end/img/160x160/img1.jpg') }}'"
                                class="avatar-img" src="{{ asset('storage/app/public/banner') }}/{{ $data->image }}"
                                alt="Image">
                        </label>
                        <!-- End Avatar -->
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h2 class="card-title h4">{{ \App\CPU\translate('Basic') }}
                                {{ \App\CPU\translate('information') }}</h2>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->
                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('name') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="{{ \App\CPU\translate('Your name') }}"
                                            value="{{ $data->name }}">
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="phoneLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('Phone') }}</label>

                                <div class="col-sm-9">
                                    <input type="text" class="js-masked-input form-control" name="phone"
                                        id="phoneLabel" placeholder="+x(xxx)xxx-xx-xx" aria-label="+(xxx)xx-xxx-xxxxx"
                                        value="{{ $data->phone }}"
                                        data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }'>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="row form-group">
                                <label for="newEmailLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('Email') }}</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="newEmailLabel"
                                        value="{{ $data->email }}"
                                        placeholder="{{ \App\CPU\translate('Enter new email address') }}"
                                        aria-label="Enter new email address">
                                </div>
                            </div>





                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('place') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="place"
                                            placeholder="{{ \App\CPU\translate('Your place') }}"
                                            value="{{ $data->place }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('address') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <textarea name="address" placeholder="Enter address" class="editor textarea form-control" cols="30"
                                            rows="10">{!! $data['address'] !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('gender') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <select name="gender" class="form-control" id="type_form"
                                            value="{{ $data['gender'] }}">
                                            <option value="Male" {{ $data['gender'] == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ $data['gender'] == 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="Others" {{ $data['gender'] == 'Others' ? 'selected' : '' }}>
                                                Others</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('date_of_birth') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="date" class="form-control" name="date_of_birth"
                                            placeholder="{{ \App\CPU\translate('Your date_of_birth') }}"
                                            value="{{ $data->date_of_birth }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('qualification') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="qualification"
                                            placeholder="{{ \App\CPU\translate('Your qualification') }}"
                                            value="{{ $data->qualification }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('experience') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="experience"
                                            placeholder="{{ \App\CPU\translate('Your experience') }}"
                                            value="{{ $data->experience }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="firstNameLabel"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('expertise') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="expertise"
                                            placeholder="{{ \App\CPU\translate('Your expertise') }}"
                                            value="{{ $data->expertise }}">
                                    </div>
                                </div>
                            </div>




                            <div class="row form-group">
                                <label for="bank_name"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('bank_name') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="bank_name"
                                            placeholder="{{ \App\CPU\translate('Your bank_name') }}"
                                            value="{{ $data->bank_name }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="account_holder_name"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('account_holder_name') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="account_holder_name"
                                            placeholder="{{ \App\CPU\translate('Your account_holder_name') }}"
                                            value="{{ $data->account_holder_name }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="account_number"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('account_number') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="account_number"
                                            placeholder="{{ \App\CPU\translate('Your account_number') }}"
                                            value="{{ $data->account_number }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="ifsc_code"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('ifsc_code') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="ifsc_code"
                                            placeholder="{{ \App\CPU\translate('Your ifsc_code') }}"
                                            value="{{ $data->ifsc_code }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label for="branch"
                                    class="col-sm-3 col-form-label input-label">{{ \App\CPU\translate('branch') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm-down-break">
                                        <input type="text" class="form-control" name="branch"
                                            placeholder="{{ \App\CPU\translate('Your branch') }}"
                                            value="{{ $data->branch }}">
                                    </div>
                                </div>
                            </div>










                            <div class="row">
                                <div class="col-md-3 col-form-label">
                                </div>
                                <div class="form-group col-md-9" id="select-img">
                                    <span class="badge badge-soft-danger">( {{ \App\CPU\translate('ratio') }} 1:1 )</span>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload"
                                            class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">Profile
                                            {{ \App\CPU\translate('image') }} {{ \App\CPU\translate('Upload') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button"
                                    onclick="{{ env('APP_MODE') != 'demo' ? "form_alert('admin-profile-form','Want to update user info ?')" : 'call_demo()' }}"
                                    class="btn btn-primary">{{ \App\CPU\translate('Save changes') }}</button>
                            </div>
                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                </form>

                <!-- Card -->
                <div id="passwordDiv" class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h4 class="card-title">{{ \App\CPU\translate('Change') }} {{ \App\CPU\translate('your') }}
                            {{ \App\CPU\translate('password') }}</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form id="changePasswordForm" action="{{ route('admin.profile.settings-password') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="newPassword" class="col-sm-3 col-form-label input-label">
                                    {{ \App\CPU\translate('New') }}
                                    {{ \App\CPU\translate('password') }}</label>

                                <div class="col-sm-9">
                                    <input type="password" class="js-pwstrength form-control" name="password"
                                        id="newPassword" placeholder="{{ \App\CPU\translate('Enter new password') }}"
                                        aria-label="Enter new password"
                                        data-hs-pwstrength-options='{
                                           "ui": {
                                             "container": "#changePasswordForm",
                                             "viewports": {
                                               "progress": "#passwordStrengthProgress",
                                               "verdict": "#passwordStrengthVerdict"
                                             }
                                           }
                                         }'>

                                    <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                    <div id="passwordStrengthProgress"></div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label input-label">
                                    {{ \App\CPU\translate('Confirm') }}
                                    {{ \App\CPU\translate('password') }} </label>

                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirmNewPasswordLabel"
                                            placeholder="{{ \App\CPU\translate('Confirm your new password') }}"
                                            aria-label="Confirm your new password">
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="d-flex justify-content-end">
                                <button type="button"
                                    onclick="{{ env('APP_MODE') != 'demo' ? "form_alert('changePasswordForm','Want to update user password ?')" : 'call_demo()' }}"
                                    class="btn btn-primary">{{ \App\CPU\translate('Save') }}
                                    {{ \App\CPU\translate('changes') }}</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Sticky Block End Point -->
                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function() {
            readURL(this);
        });
    </script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
    <script>
        $("#generalSection").click(function() {
            $("#passwordSection").removeClass("active");
            $("#generalSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#generalDiv").offset().top
            }, 2000);
        });

        $("#passwordSection").click(function() {
            $("#generalSection").removeClass("active");
            $("#passwordSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#passwordDiv").offset().top
            }, 2000);
        });
    </script>
@endpush

@push('script')
@endpush
