@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('quotation Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.quotation.list')}}">
                                    {{\App\CPU\translate('quotations')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('quotation details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('quotation ID')}} #{{$quotation['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($quotation['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($quotation)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('quotation info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Client : 
                                    @if($quotation->client != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $quotation->client])->first()->name}}</i></b>
                                    @endif
                                </li>
                                
                                <li>Proposal: 
                                    @if(\App\Model\Proposal::where(['id' => $quotation->proposal_id])->first())
                                        <b><i>{{\App\Model\Proposal::where(['id' => $quotation->proposal_id])->first()->proposal_title}}</i></b>
                                    @endif
                                </li>
                                <li>Quotation Number : <b><i>{{$quotation['quotation_number']}}</i></b></li>
                                <li>Quotation Date : <b><i>{{date('d M Y',strtotime($quotation['quotation_date']))}}</i></b></li>
                                <li>Due Date : <b><i>{{date('d M Y',strtotime($quotation['due_date']))}}</i></b></li>
                                <li>Notes : <b><i>{{$quotation['notes']}}</i></b></li>
                                <li>Status : <b><i>{{$quotation['status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection