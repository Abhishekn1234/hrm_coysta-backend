@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('invoice'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('invoice')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('invoice_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.invoice.update',[$invoice['id']])}}" method="post" enctype="multipart/form-data" class="invoice_form">
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
                                            <select onchange="getRequest('{{url('/')}}/admin/invoice/get_quotation?client_id='+this.value,'quotation_select','select')" class="js-example-basic-multiple js-states js-example-responsive form-control" name="client_id" id="client_id" required>
                                                <option value="{{null}}" selected disabled>Select client</option>
                                                @foreach($client_list as $b)
                                                    <option value="{{$b['id']}}" {{ $invoice['client_id']==$b['id'] ? 'selected' : ''}}>{{$b['name']}} ({{$b['user_type']}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="quotation_id">{{\App\CPU\translate('quotation')}}</label>
                                            <select data-id="{{$invoice['quotation_id']}}" id="quotation_select" class="js-example-basic-multiple js-states js-example-responsive form-control" name="quotation_id" required>
                                                <!--@foreach($quotation_list as $b)-->
                                                <!--    <option value="{{$b['id']}}" {{ $invoice['quotation_id']==$b['id'] ? 'selected' : ''}}>{{$b['quotation_number']}}</option>-->
                                                <!--@endforeach-->
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="invoice_number">{{ \App\CPU\translate('invoice_number')}}</label>
                                            <input readonly placeholder="Enter invoice_number" type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{$invoice['invoice_number']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="start_date">{{ \App\CPU\translate('date')}}</label>
                                            <input placeholder="Enter date" type="date" name="start_date" class="form-control" id="start_date" value="{{$invoice['start_date']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="renewal_date">{{ \App\CPU\translate('renewal_date')}}</label>
                                            <input placeholder="Enter renewal_date" type="date" name="renewal_date" class="form-control" id="renewal_date" value="{{$invoice['renewal_date']}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.invoice.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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
                let quotation_select = $("#quotation_select").attr("data-id");
                getRequest('{{url('/')}}/admin/invoice/get_quotation?client_id=' + client_id + '&quotation_select=' + quotation_select, 'quotation_select', 'select');
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