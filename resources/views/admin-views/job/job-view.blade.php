@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('job Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.job.list')}}">
                                    {{\App\CPU\translate('jobs')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('job details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('job ID')}} #{{$job['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($job['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($job)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('job info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Title : <b><i>{{$job['job_title']}}</i></b></li>
                                <li>Description : <b><i>{!!$job['job_description']!!}</i></b></li>
                                <li>Type : <b><i>{{$job['job_type']}}</i></b></li>
                                <li>Qualification Required : <b><i>{{$job['qualification_required']}}</i></b></li>
                                <li>Experience Required : <b><i>{{$job['experience_required']}}</i></b></li>
                                <li>Minimum Salary : <b><i>{{$job['min_salary']}}</i></b></li>
                                <li>Maximum Salary : <b><i>{{$job['max_salary']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection