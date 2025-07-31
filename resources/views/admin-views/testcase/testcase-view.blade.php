@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('testcase Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.testcase.list')}}">
                                    {{\App\CPU\translate('testcase')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('testcase details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('testcase ID')}} #{{$testcase['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($testcase['created_at']))}}
                        </span>
                    </div>
                    
                    <a class="btn btn-primary btn-sm edit" title="{{ \App\CPU\translate('Edit')}}" href="{{route('admin.testcase.edit',[$testcase['tsid']])}}" style="cursor: pointer;"> 
                        <i class="tio-edit"></i> Edit Task
                    </a>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($testcase)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('task info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Date : <b><i>{{date('d M Y',strtotime($testcase['date']))}}</i></b></li>
                                <li>User Name : <b><i>{{$testcase['user_name']}}</i></b></li>
                                <li>User Type : <b><i>{{$testcase['user_user_type']}}</i></b></li>
                                <li>Project : <b><i>{{$testcase['project_name']}}</i></b></li>
                                <li>Number : <b><i>{{$testcase['task_number']}}</i></b></li>
                                <li>Name : <b><i>{{$testcase['task_name']}}</i></b></li>
                                <li>Description : <b><i>{!!$testcase['task_description']!!}</i></b></li>
                                <li>UI Sample : 
                                    <b><i>
                                        <?php if($testcase['ui_sample'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$testcase->ui_sample)}}">Click here to display UI Sample</a>
                                        <?php } else { ?>
                                            UI Sample Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Database File : 
                                    <b><i>
                                        <?php if($testcase['database_file'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$testcase->database_file)}}">Click here to display Database File</a>
                                        <?php } else { ?>
                                            Database File Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Test Case : <b><i>{!!$testcase['test_case']!!}</i></b></li>
                                <li>Test Case Updated : 
                                    <?php if($testcase['test_case_updated'] == 1){ ?> 
                                        <b style="color:green;"><i>Yes</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>No</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Tested By : 
                                    @if($testcase->tested_by != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $testcase->tested_by])->first()->name}} ({{\App\User::where(['status' => '1','id' => $testcase->tested_by])->first()->user_type}})</i></b>
                                    @endif
                                </li>
                                <li>Tester Remark : <b><i>{!!$testcase['tester_remark']!!}</i></b></li>
                                <li>Test Status : <b><i>{{$testcase['test_status']}}</i></b></li>
                                <li>Tested Date : <b><i>{{date('d M Y',strtotime($testcase['test_date_time']))}}</i></b></li>
                                
                                <li>Agile Work Detail : <b><i>{!!$testcase['agile_work_detail']!!}</i></b></li>
                                <li>Estimated Time (in mins) : <b><i>{{$testcase['estimated_time']}}</i></b></li>
                                <li>Reviewed Tech Lead Name : <b><i>{{$testcase['reviewed_tech_lead_name']}}</i></b></li>
                                <li>Tech Lead Adjusted Time (in mins) : <b><i>{{$testcase['tech_lead_adjusted_time']}}</i></b></li>
                                <li>Tech Lead Remarks : <b><i>{!!$testcase['tech_lead_remarks']!!}</i></b></li>
                                <li>Tech Lead Approval : 
                                    <?php if($testcase['tech_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Team Lead Name : <b><i>{{$testcase['team_lead_name']}}</i></b></li>
                                <li>Team Lead Remark : <b><i>{!!$testcase['team_lead_remark']!!}</i></b></li>
                                <li>Team Lead Approval : 
                                    <?php if($testcase['team_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Ceo Approval : 
                                    <?php if($testcase['ceo_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Expected Completion Date : <b><i>{{$testcase['expected_completion_date']}}</i></b></li>
                                <li>Actual Completion Date : <b><i>{{$testcase['actual_completion_date']}}</i></b></li>
                                <li>Actual Completion Time (in mins) : <b><i>{{$testcase['actual_completion_time']}}</i></b></li>
                                <li>Task Status : <b><i>{{$testcase['task_status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection