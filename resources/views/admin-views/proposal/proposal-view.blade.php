@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('proposal Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.proposal.list')}}">
                                    {{\App\CPU\translate('proposals')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('proposal details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Proposal ID')}} #{{$proposal['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($proposal['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($proposal)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('proposal info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Client : 
                                    @if($proposal->client != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $proposal->client])->first()->name}} ({{\App\User::where(['status' => '1','id' => $proposal->client])->first()->user_type}})</i></b>
                                    @endif
                                </li>
                                <li>Proposal Title : <b><i>{{$proposal['proposal_title']}}</i></b></li>
                                <li>Proposal Date : <b><i>{{date('d M Y',strtotime($proposal['proposal_date']))}}</i></b></li>
                                <li>Proposal Description : <b><i>{!!$proposal['proposal_description']!!}</i></b></li>
                                <li>Min Expected Amount : <b><i>{{$proposal['min_expected_amount']}}</i></b></li>
                                <li>Max Expected Amount : <b><i>{{$proposal['max_expected_amount']}}</i></b></li>
                                <li>Direct Pdf : <?php if($proposal['direct_pdf'] != '') { ?><a target="_blank" class="btn btn-primary btn-sm edit" href="{{asset('storage/app/public/banner')}}/{{$proposal['direct_pdf']}}" download="{{ $proposal['direct_pdf'] }}">Pdf</a><?php } ?></li>
                                <li>Status : <b><i>{{$proposal['status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection