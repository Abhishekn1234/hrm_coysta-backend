@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('invoice Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.invoice.list')}}">
                                    {{\App\CPU\translate('invoices')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('invoice details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('invoice ID')}} #{{$invoice['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($invoice['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($invoice)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('invoice info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Client : 
                                    @if($invoice->client != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $invoice->client])->first()->name}}</i></b>
                                    @endif
                                </li>
                                
                                <li>Quotation: 
                                    @if(\App\Model\Quotation::where(['id' => $invoice->quotation_id])->first())
                                        <b><i>{{\App\Model\Quotation::where(['id' => $invoice->quotation_id])->first()->quotation_number}}</i></b>
                                    @endif
                                </li>
                                <li>Invoice Number : <b><i>{{$invoice['invoice_number']}}</i></b></li>
                                <li>Date : <b><i>{{date('d M Y',strtotime($invoice['start_date']))}}</i></b></li>
                                <li>Renewal Date : <b><i>{{date('d M Y',strtotime($invoice['renewal_date']))}}</i></b></li>
                                <li>Status : <b><i>{{$invoice['status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection