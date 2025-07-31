@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('project Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.project.list')}}">
                                    {{\App\CPU\translate('projects')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('project details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('project ID')}} #{{$project['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($project['created_at']))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($project)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('project info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Name : <b><i>{{$project['project_name']}}</i></b></li>
                                <li>Description : <b><i>{!!$project['project_description']!!}</i></b></li>
                                <li>Starting Date : <b><i>{{$project['project_starting_date']}}</i></b></li>
                                <li>Expected Release Date : <b><i>{{$project['expected_release_date']}}</i></b></li>
                                <li>Deadline : <b><i>{{$project['deadline']}}</i></b></li>
                                <li>Product Owner : <b><i>{{$project['owner_name']}}</i></b></li>
                                
                                <li>Staffs worked/working under the project
                                    <ul>
                                        @foreach (json_decode($project['staff_ids']) as $key => $a)
                                            <li><b><i>{{\App\User::where(['status' => '1','id' => $a])->first()->name}} ({{\App\User::where(['status' => '1','id' => $a])->first()->user_type}} : {{\App\User::where(['status' => '1','id' => $a])->first()->designation}})</i></b></li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection