@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('lead'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('lead')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('lead_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.lead.update',[$lead['id']])}}" method="post" enctype="multipart/form-data" class="lead_form">
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
                                            <label for="assigned_user_id">{{\App\CPU\translate('Assigned To')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="assigned_user_id" required>
                                                @foreach($staff_list as $b)
                                                    <option value="{{$b['id']}}" {{ $lead['assigned_user_id']==$b['id'] ? 'selected' : ''}}>{{$b['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lead_name">{{ \App\CPU\translate('lead_name')}}</label>
                                            <input placeholder="Enter lead_name" type="text" name="lead_name" class="form-control" value="{{$lead['lead_name']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lead_email">{{ \App\CPU\translate('lead_email')}}</label>
                                            <input placeholder="Enter lead_email" type="email" name="lead_email" class="form-control" value="{{$lead['lead_email']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lead_phone">{{ \App\CPU\translate('lead_phone')}}</label>
                                            <input placeholder="Enter lead_phone" type="text" name="lead_phone" class="form-control" value="{{$lead['lead_phone']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lead_sourse">{{ \App\CPU\translate('lead_sourse')}}</label>
                                            <input placeholder="Enter lead_sourse" type="text" name="lead_sourse" class="form-control" value="{{$lead['lead_sourse']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lead_notes">{{ \App\CPU\translate('lead_notes')}}</label>
                                            <input placeholder="Enter lead_notes" type="text" name="lead_notes" class="form-control" value="{{$lead['lead_notes']}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.lead.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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