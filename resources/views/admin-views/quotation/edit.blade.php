@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('quotation'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('quotation')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('quotation_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.quotation.update',[$quotation['id']])}}" method="post" enctype="multipart/form-data" class="quotation_form">
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
                                            <label for="client_id">{{\App\CPU\translate('client')}}</label>
                                            <select onchange="getRequest('{{url('/')}}/admin/quotation/get_proposal?client_id='+this.value,'proposal_select','select')" class="js-example-basic-multiple js-states js-example-responsive form-control" name="client_id" id="client_id" required>
                                                <option value="{{null}}" selected disabled>Select client</option>
                                                @foreach($client_list as $b)
                                                    <option value="{{$b['id']}}" {{ $quotation['client_id']==$b['id'] ? 'selected' : ''}}>{{$b['name']}} ({{$b['user_type']}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="proposal_id">{{\App\CPU\translate('Proposal')}}</label>
                                            <select data-id="{{$quotation['proposal_id']}}" id="proposal_select" class="js-example-basic-multiple js-states js-example-responsive form-control" name="proposal_id" required>
                                                <!--@foreach($proposal_list as $b)-->
                                                <!--    <option value="{{$b['id']}}" {{ $quotation['proposal_id']==$b['id'] ? 'selected' : ''}}>{{$b['proposal_title']}}</option>-->
                                                <!--@endforeach-->
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="quotation_number">{{ \App\CPU\translate('quotation_number')}}</label>
                                            <input readonly placeholder="Enter quotation_number" type="text" name="quotation_number" class="form-control" id="quotation_number" value="{{$quotation['quotation_number']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="quotation_date">{{ \App\CPU\translate('quotation_date')}}</label>
                                            <input placeholder="Enter quotation_date" type="date" name="quotation_date" class="form-control" id="quotation_date" value="{{$quotation['quotation_date']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="due_date">{{ \App\CPU\translate('due_date')}}</label>
                                            <input placeholder="Enter due_date" type="date" name="due_date" class="form-control" id="due_date" value="{{$quotation['due_date']}}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="notes">{{ \App\CPU\translate('notes')}}</label>
                                            <input placeholder="Enter notes" type="text" name="notes" class="form-control" id="notes" value="{{$quotation['notes']}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.quotation.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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
        $(document).ready(function () {
            setTimeout(function () {
                let client_id = $("#client_id").val();
                let proposal_select = $("#proposal_select").attr("data-id");
                getRequest('{{url('/')}}/admin/quotation/get_proposal?client_id=' + client_id + '&proposal_select=' + proposal_select, 'proposal_select', 'select');
            }, 100)
        });
        
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }
        
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