@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('backlog'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('backlog')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('backlog_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.backlog.update',[$backlog['id']])}}" method="post" enctype="multipart/form-data" class="backlog_form">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <style>
                                            .select2-container {
                                                width: 100%!important;
                                            }
                                        </style>
                                        
                                        <div class="form-group">
                                            <label for="project_id">{{\App\CPU\translate('Project')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="project_id" required>
                                                @foreach($projects as $b)
                                                    <option value="{{$b['id']}}" {{ $backlog['project_id']==$b['id'] ? 'selected' : ''}}>{{$b['project_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="backlog_assigned_user_id">{{\App\CPU\translate('Assigned To')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="backlog_assigned_user_id" required>
                                                @foreach($staff_list as $b)
                                                    <option value="{{$b['id']}}" {{ $backlog['backlog_assigned_user_id']==$b['id'] ? 'selected' : ''}}>{{$b['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sprint_name">{{ \App\CPU\translate('sprint_name')}}</label>
                                            <input placeholder="Enter sprint_name" value="{{$backlog['sprint_name']}}" type="text" name="sprint_name" id="sprint_name" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="backlog_name">{{ \App\CPU\translate('backlog_name')}}</label>
                                            <input placeholder="Enter backlog_name" type="text" name="backlog_name" class="form-control" value="{{$backlog['backlog_name']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="backlog_description">{{ \App\CPU\translate('backlog_description')}}</label>
                                            <textarea name="backlog_description" placeholder="Enter backlog_description" class="editor textarea" cols="30" rows="10">{!!$backlog['backlog_description']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="estimated_time">{{ \App\CPU\translate('estimated_time')}} (in mins)</label>
                                            <input placeholder="Enter estimated_time" type="number" name="estimated_time" class="form-control" value="{{$backlog['estimated_time']}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.backlog.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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
        
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().val(data.sprint_name);
                },
            });
        }
    </script>

    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
@endpush