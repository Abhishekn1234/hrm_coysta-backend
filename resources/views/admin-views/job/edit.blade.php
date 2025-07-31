@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('job'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('job')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('job_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.job.update',[$job['id']])}}" method="post" enctype="multipart/form-data" class="job_form">
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="job_title">{{ \App\CPU\translate('Job Title')}}</label>
                                            <input placeholder="Enter job_title" type="text" name="job_title" class="form-control" id="job_title" value="{{$job['job_title']}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="job_description">{{ \App\CPU\translate('Job Description')}}</label>
                                            <textarea name="job_description" placeholder="Enter job_description" class="editor textarea" cols="30" rows="10" required>{!! $job['job_description'] !!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="job_type" style="padding-bottom: 3px">Job Type</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="job_type" id="job_type" required>
                                                <option value="FULL_TIME" {{ $job->job_type=="FULL_TIME" ? 'selected' : ''}}>FULL_TIME</option>
                                                <option value="PART_TIME" {{ $job->job_type=="PART_TIME" ? 'selected' : ''}}>PART_TIME</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="qualification_required">{{ \App\CPU\translate('Qualification Required')}}</label>
                                            <input placeholder="Enter qualification_required" type="text" name="qualification_required" class="form-control" id="qualification_required" value="{{$job['qualification_required']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="experience_required">{{ \App\CPU\translate('Experience Required')}}</label>
                                            <input placeholder="Enter experience_required" type="text" name="experience_required" class="form-control" id="experience_required" value="{{$job['experience_required']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="min_salary">{{ \App\CPU\translate('Minimum Salary')}}</label>
                                            <input placeholder="Enter min_salary" type="text" name="min_salary" class="form-control" id="min_salary" value="{{$job['min_salary']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="max_salary">{{ \App\CPU\translate('Maximum Salary')}}</label>
                                            <input placeholder="Enter max_salary" type="text" name="max_salary" class="form-control" id="max_salary" value="{{$job['max_salary']}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.job.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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