@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('interview Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.interview.list')}}">
                                    {{\App\CPU\translate('interview')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('interview details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('interview ID')}} #{{$interview->id}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Joined At')}} : {{date('d M Y H:i:s',strtotime($interview->created_at))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($interview)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('interview info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Date : <b><i>{{date('d M Y',strtotime($interview->interview_date))}}</i></b></li>
                                <li>Time : <b><i>{{date('h:i:s A',strtotime($interview->interview_time))}}</i></b></li>
                                <li>Interviewers : 
                                    <b><i>
                                        <ul>
                                            @foreach (json_decode($interview->interviewer_ids) as $key => $a)
                                                <li><b><i>{{\App\User::where(['status' => '1','id' => $a])->first()->name}} ({{\App\User::where(['status' => '1','id' => $a])->first()->user_type}})</i></b></li>
                                            @endforeach
                                        </ul>
                                    </i></b>
                                </li>
                                <li>Link : 
                                    <b><i>
                                        <?php if($interview->google_meet_link != "" && $interview->interview_status == "SCHEDULED") { ?>
                                            <a  title="{{\App\CPU\translate('link')}}" class="btn btn-primary btn-sm" target="_blank" href="{{$interview->google_meet_link}}">
                                                Link
                                            </a>
                                        <?php } ?>
                                    </i></b>
                                </li>
                                <li>Status : <b><i>{{$interview->interview_status}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
                
                <div class="card mt-5">
                    @if($interview)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Candidate info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Name : <b><i>@if(\App\Model\Candidate::where(['id' => $interview->candidate_id])->first()) {{\App\Model\Candidate::where(['id' => $interview->candidate_id])->first()->name}} @endif</i></b></li>
                                <li>Phone : <b><i>@if(\App\Model\Candidate::where(['id' => $interview->candidate_id])->first()) {{\App\Model\Candidate::where(['id' => $interview->candidate_id])->first()->phone}} @endif</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection