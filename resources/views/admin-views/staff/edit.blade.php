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
                    <a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('staff')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('staff_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.staff.update',[$staff['id']])}}" method="post" enctype="multipart/form-data" class="staff_form">
                            @csrf
                            @method('put')
                            
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
                                    width: 100%!important;
                                }
                            </style>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="join_date">{{ \App\CPU\translate('join_date')}}</label>
                                            <input placeholder="Enter join_date" type="date" name="join_date" value="{{$staff['join_date']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('Image')}}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="image" id="mbimageFileUploader" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label" for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="user_type">{{ \App\CPU\translate('user_type')}}</label>
                                            <select name="user_type" class="form-control" id="type_form" value="{{$staff['user_type']}}" required>
                                                <option value="CEO" {{ $staff['user_type']=='CEO' ? 'selected' : ''}}>CEO</option>
                                                <option value="SCRUM_MASTER" {{ $staff['user_type']=='SCRUM_MASTER' ? 'selected' : ''}}>SCRUM_MASTER</option>
                                                <option value="HR" {{ $staff['user_type']=='HR' ? 'selected' : ''}}>HR</option>
                                                <option value="PRODUCT_OWNER" {{ $staff['user_type']=='PRODUCT_OWNER' ? 'selected' : ''}}>PRODUCT_OWNER</option>
                                                <option value="TEAM_LEAD" {{ $staff['user_type']=='TEAM_LEAD' ? 'selected' : ''}}>TEAM_LEAD</option>
                                                <option value="TECHNICAL_LEAD" {{ $staff['user_type']=='TECHNICAL_LEAD' ? 'selected' : ''}}>TECHNICAL_LEAD</option>
                                                <option value="QAQC" {{ $staff['user_type']=='QAQC' ? 'selected' : ''}}>QAQC</option>
                                                <option value="MARKETING_MANAGER" {{ $staff['user_type']=='MARKETING_MANAGER' ? 'selected' : ''}}>MARKETING_MANAGER</option>
                                                <option value="STAFF" {{ $staff['user_type']=='STAFF' ? 'selected' : ''}}>STAFF</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('name')}}</label>
                                            <input placeholder="Enter name" type="text" name="name" class="form-control" id="name" value="{{$staff['name']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">{{ \App\CPU\translate('email')}}</label>
                                            <input placeholder="Enter email" type="email" name="email" class="form-control" id="email" value="{{$staff['email']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">{{ \App\CPU\translate('password')}}</label>
                                            <input placeholder="Enter password" type="password" name="password" class="form-control" id="password" autocomplete="new-password">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="phone">{{ \App\CPU\translate('phone')}}</label>
                                            <input placeholder="Enter phone" type="text" name="phone" class="form-control" id="phone" value="{{$staff['phone']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="place">{{ \App\CPU\translate('place')}}</label>
                                            <input placeholder="Enter place" type="text" name="place" class="form-control" id="place" value="{{$staff['place']}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">{{ \App\CPU\translate('address')}}</label>
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10" required>{!! $staff['address'] !!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="gender">{{ \App\CPU\translate('gender')}}</label>
                                            <select name="gender" class="form-control" id="type_form" value="{{$staff['gender']}}" required>
                                                <option value="Male" {{ $staff['gender']=='Male' ? 'selected' : ''}}>Male</option>
                                                <option value="Female" {{ $staff['gender']=='Female' ? 'selected' : ''}}>Female</option>
                                                <option value="Others" {{ $staff['gender']=='Others' ? 'selected' : ''}}>Others</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="work_location">{{ \App\CPU\translate('work_location')}}</label>
                                            <input placeholder="Enter work_location" type="text" name="work_location" value="{{$staff['work_location']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="employment_type">{{ \App\CPU\translate('employment_type')}}</label>
                                            <select name="employment_type" class="form-control" id="type_form" required>
                                                <option value="FULL_TIME" {{ $staff['employment_type']=='FULL_TIME' ? 'selected' : ''}}>FULL_TIME</option>
                                                <option value="PARTIAL" {{ $staff['employment_type']=='PARTIAL' ? 'selected' : ''}}>PARTIAL</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hourly_rate">{{ \App\CPU\translate('hourly_rate in $')}}</label>
                                            <input placeholder="Enter hourly_rate in $" type="number" name="hourly_rate" class="form-control" id="hourly_rate" value="{{$staff['hourly_rate']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="monthly_rate">{{ \App\CPU\translate('monthly_rate in $')}}</label>
                                            <input placeholder="Enter monthly_rate in $" type="number" name="monthly_rate" class="form-control" id="monthly_rate" value="{{$staff['monthly_rate']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="annual_ctc">{{ \App\CPU\translate('annual_ctc')}}</label>
                                            <input placeholder="Enter annual_ctc" type="number" name="annual_ctc" value="{{$staff['annual_ctc']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="basic_salary">{{ \App\CPU\translate('basic_salary')}}</label>
                                            <input placeholder="Enter basic_salary" type="number" name="basic_salary" value="{{$staff['basic_salary']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hra">{{ \App\CPU\translate('hra')}}</label>
                                            <input placeholder="Enter hra" type="number" name="hra" value="{{$staff['hra']}}" class="form-control" required> 
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="special_allowances">{{ \App\CPU\translate('special_allowances')}}</label>
                                            <input placeholder="Enter special_allowances" type="number" name="special_allowances" value="{{$staff['special_allowances']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="probation_period">{{ \App\CPU\translate('probation_period')}}</label>
                                            <input placeholder="Enter probation_period" type="text" name="probation_period" value="{{$staff['probation_period']}}" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="date_of_birth">{{ \App\CPU\translate('date_of_birth')}}</label>
                                            <input placeholder="Enter date_of_birth" type="date" name="date_of_birth" class="form-control" id="date_of_birth" value="{{$staff['date_of_birth']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification')}}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification" class="form-control" id="qualification" value="{{$staff['qualification']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience')}}</label>
                                            <input placeholder="Enter experience" type="text" name="experience" class="form-control" id="experience" value="{{$staff['experience']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="expertise">{{ \App\CPU\translate('expertise')}}</label>
                                            <input placeholder="Enter expertise" type="text" name="expertise" class="form-control" id="expertise" value="{{$staff['expertise']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="designation">{{ \App\CPU\translate('designation')}}</label>
                                            <input placeholder="Enter designation" type="text" name="designation" class="form-control" id="designation" value="{{$staff['designation']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="role">{{ \App\CPU\translate('role')}}</label>
                                            <input placeholder="Enter role" type="text" name="role" class="form-control" id="role" value="{{$staff['role']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="reports_to">{{\App\CPU\translate('Reports To')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="reports_to" required>
                                                @foreach($reports_to as $b)
                                                    <option value="{{$b['id']}}" {{ $staff->reports_to==$b['id'] ? 'selected' : ''}}>{{$b['name']}} ( {{$b['user_type']}} )</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <center>
                                            <img style="width: auto;border: 1px solid; border-radius: 10px; max-width:400px;" id="mbImageviewer" src="{{asset('storage/app/public/banner')}}/{{$staff['image']}}" alt=""/>
                                        </center>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.staff.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
                                        <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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

                reader.onload = function (e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function () {
            mbimagereadURL(this);
        });
        
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
@endpush