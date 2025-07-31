@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('candidate Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.candidate.list')}}">
                                    {{\App\CPU\translate('candidates')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('candidate details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('candidate ID')}} #{{$candidate['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($candidate['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($candidate)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('candidate info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Name : <b><i>{{$candidate['name']}}</i></b></li>
                                <li>Email : <b><i>{{$candidate['email']}}</i></b></li>
                                <li>Phone : <b><i>{{$candidate['phone']}}</i></b></li>
                                <li>Place : <b><i>{{$candidate['place']}}</i></b></li>
                                <li>Address : <b><i>{!!$candidate['address']!!}</i></b></li>
                                <li>Gender : <b><i>{{$candidate['gender']}}</i></b></li>
                                <li>DOB : <b><i>{{date('d M Y',strtotime($candidate['date_of_birth']))}}</i></b></li>
                                <li>Age : <b><i>{{now()->diffInYears($candidate['date_of_birth'])}}</i></b></li>
                                <li>Qualification : <b><i>{{$candidate['qualification']}}</i></b></li>
                                <li>Position : <b><i>{{$candidate['position']}}</i></b></li>
                                
                                <li>Job : 
                                    @if(\App\Model\Job::where(['id' => $candidate->job_id])->first())
                                        <b><i>{{\App\Model\Job::where(['id' => $candidate->job_id])->first()->job_title}} ({{\App\Model\Job::where(['id' => $candidate->job_id])->first()->job_type}})</i></b>
                                    @endif
                                </li>
                                
                                <li>Experience : <b><i>{{$candidate['experience']}}</i></b></li>
                                <li>Skills : <b><i>{{$candidate['skills']}}</i></b></li>
                                <li>10th mark (%) : <b><i>{{$candidate['tenth_mark_percentage']}}</i></b></li>
                                <li>12th mark (%) : <b><i>{{$candidate['twelveth_mark_percentage']}}</i></b></li>
                                <li>Degree mark (%) : <b><i>{{$candidate['degree_mark_percentage']}}</i></b></li>
                                <li>Portfolio Link : <b><i>{{$candidate['portfolio_link']}}</i></b></li>
                                <li>Resume : <?php if($candidate['resume'] != '') { ?><a target="_blank" class="btn btn-primary btn-sm edit" href="{{asset('storage/app/public/banner')}}/{{$candidate['resume']}}">Resume</a><?php } ?></li>
                                <li>Last Qualification Certificate : <?php if($candidate['last_qualification_certificate'] != '') { ?><a target="_blank" class="btn btn-primary btn-sm edit" href="{{asset('storage/app/public/banner')}}/{{$candidate['last_qualification_certificate']}}">Certificate</a><?php } ?></li>
                                <li>
                                    Academics :
                                    <?php
                                        $weight10th = 20;  // 20% for 10th
                                        $weight12th = 30;  // 30% for 12th
                                        $weightDegree = 50;  // 50% for Degree
                                        
                                        $score = ($candidate['tenth_mark_percentage'] * $weight10th / 100) + ($candidate['twelveth_mark_percentage'] * $weight12th / 100) + ($candidate['degree_mark_percentage'] * $weightDegree / 100);
                                    ?>
                                    <b><i>{{ $score != 0 ? $score . '%' : '' }}</i></b>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                
                <?php if($candidate['is_test_done'] == 1) { ?>
                    <div class="card" style="margin-top:30px;">
                        @if($candidate)
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>{{\App\CPU\translate('Personality Test Result')}}</h5>
                                </div>
    
                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>Type : <b><i>{{$candidate['result_typeindex']}} - {{$candidate['result_type']}}</i></b></li>
                                    <li>Score : <b><i>{{$candidate['result_highestScore']}}</i></b></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                <?php } ?>
                
                <div class="card" style="margin-top:30px;">
                    @if($candidate)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('HR Questions')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Q1 : What are your strengths? <br>Answer : <b><i>{!! $candidate['strengths'] !!}</i></b></li>
                                <li>Q2 : What are your weaknesses? <br>Answer : <b><i>{!! $candidate['weaknesses'] !!}</i></b></li>
                                <li>Q3 : Where do you see yourself in 5 years? <br>Answer : <b><i>{!! $candidate['goals'] !!}</i></b></li>
                                <li>Q4 : Are you willing to relocate for this position if required? <br>Answer : <b><i>{!! $candidate['willingness_to_relocate'] !!}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection