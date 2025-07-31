@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('lead Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.lead.list')}}">
                                    {{\App\CPU\translate('leads')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('lead details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('lead ID')}} #{{$lead['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($lead['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($lead)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('lead info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Name : <b><i>{{$lead['lead_name']}}</i></b></li>
                                <li>Email : <b><i>{{$lead['lead_email']}}</i></b></li>
                                <li>Phone : <b><i>{{$lead['lead_phone']}}</i></b></li>
                                <li>Sourse : <b><i>{{$lead['lead_sourse']}}</i></b></li>
                                <li>Notes : <b><i>{{$lead['lead_notes']}}</i></b></li>
                                <li>Added By : <b><i>{{\App\User::where(['status' => '1','id' => $lead->user_id])->first()->name}}</i></b></li>
                                <li>Assigned To : 
                                    @if($lead->assigned_user_id != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $lead->assigned_user_id])->first()->name}}</i></b>
                                    @endif
                                </li>
                                <li>Status : <b><i>{{$lead->lead_status}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection