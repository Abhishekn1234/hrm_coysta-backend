@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('point_setting'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('point_setting')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('point_setting_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.point_setting.update',[$point_setting->id])}}" method="post" enctype="multipart/form-data" class="point_setting_form">
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
                                            <label for="items">{{ \App\CPU\translate('items')}}</label>
                                            <input readonly placeholder="Enter items" type="text" name="items" class="form-control" value="{{$point_setting->items}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="type">{{\App\CPU\translate('type')}}</label>
                                            <select class="form-control" name="type" required>
                                                <option value="PLUS" {{ $point_setting->type=='PLUS' ? 'selected' : ''}}>PLUS</option>
                                                <option value="MINUS" {{ $point_setting->type=='MINUS' ? 'selected' : ''}}>MINUS</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="points">{{ \App\CPU\translate('points')}}</label>
                                            <input placeholder="Enter points" type="number" name="points" class="form-control" value="{{$point_setting->points}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.point_setting.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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