@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('proposal'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('proposal')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('proposal_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.proposal.update',[$proposal['id']])}}" method="post" enctype="multipart/form-data" class="proposal_form">
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
                                            <label for="client_id">{{\App\CPU\translate('Client')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="client_id" required>
                                                @foreach($client_list as $b)
                                                    <option value="{{$b['id']}}" {{ $proposal['client_id']==$b['id'] ? 'selected' : ''}}>{{$b['name']}}  ({{$b['user_type']}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="proposal_title">{{ \App\CPU\translate('proposal_title')}}</label>
                                            <input placeholder="Enter proposal_title" type="text" name="proposal_title" class="form-control" id="proposal_title" value="{{$proposal['proposal_title']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="proposal_date">{{ \App\CPU\translate('proposal_date')}}</label>
                                            <input placeholder="Enter proposal_date" type="date" name="proposal_date" class="form-control" id="proposal_date" value="{{$proposal['proposal_date']}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="proposal_description">{{ \App\CPU\translate('proposal_description')}}</label>
                                            <textarea name="proposal_description" placeholder="Enter proposal_description" class="editor textarea" cols="30" rows="10" required>{!! $proposal['proposal_description'] !!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="min_expected_amount">{{ \App\CPU\translate('min_expected_amount in $')}}</label>
                                            <input placeholder="Enter min_expected_amount in $" type="text" name="min_expected_amount" class="form-control" id="min_expected_amount" value="{{$proposal['min_expected_amount']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="max_expected_amount">{{ \App\CPU\translate('max_expected_amount in $')}}</label>
                                            <input placeholder="Enter max_expected_amount in $" type="text" name="max_expected_amount" class="form-control" id="max_expected_amount" value="{{$proposal['max_expected_amount']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="status">{{ \App\CPU\translate('status')}}</label>
                                            <select name="status" class="form-control" id="type_form" value="{{$proposal['status']}}" required>
                                                <option value="DRAFT" {{ $proposal['status']=='DRAFT' ? 'selected' : ''}}>DRAFT</option>
                                                <option value="FINALIZED" {{ $proposal['status']=='FINALIZED' ? 'selected' : ''}}>FINALIZED</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="direct_pdf">
                                            <label for="direct_pdf">{{ \App\CPU\translate('direct_pdf')}}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="direct_pdf" id="mbimageFileUploader" class="custom-file-input" accept=".pdf">
                                                <label class="custom-file-label" for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.proposal.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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