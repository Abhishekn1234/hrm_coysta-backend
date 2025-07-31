@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('staff task Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.stafftask.list')}}">
                                    {{\App\CPU\translate('staff tasks')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('staff task details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('staff task ID')}} #{{$stafftask['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($stafftask['created_at']))}}
                        </span>
                    </div>
                    
                    <a class="btn btn-primary btn-sm edit" title="{{ \App\CPU\translate('Edit')}}" href="{{route('admin.stafftask.edit',[$stafftask['tsid']])}}" style="cursor: pointer;"> 
                        <i class="tio-edit"></i> Edit Task
                    </a>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($stafftask)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('task info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Date : <b><i>{{date('d M Y',strtotime($stafftask['date']))}}</i></b></li>
                                <li>User Name : <b><i>{{$stafftask['user_name']}}</i></b></li>
                                <li>User Type : <b><i>{{$stafftask['user_user_type']}}</i></b></li>
                                <li>Project : <b><i>{{$stafftask['project_name']}}</i></b></li>
                                <li>Number : <b><i>{{$stafftask['task_number']}}</i></b></li>
                                <li>Name : <b><i>{{$stafftask['task_name']}}</i></b></li>
                                <li>Description : <b><i>{!!$stafftask['task_description']!!}</i></b></li>
                                <li>UI Sample : 
                                    <b><i>
                                        <?php if($stafftask['ui_sample'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$stafftask->ui_sample)}}">Click here to display UI Sample</a>
                                        <?php } else { ?>
                                            UI Sample Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Database File : 
                                    <b><i>
                                        <?php if($stafftask['database_file'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$stafftask->database_file)}}">Click here to display Database File</a>
                                        <?php } else { ?>
                                            Database File Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Test Case : <b><i>{!!$stafftask['test_case']!!}</i></b></li>
                                <li>Test Case Updated : 
                                    <?php if($stafftask['test_case_updated'] == 1){ ?> 
                                        <b style="color:green;"><i>Yes</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>No</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Tested By : 
                                    @if($stafftask->tested_by != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $stafftask->tested_by])->first()->name}} ({{\App\User::where(['status' => '1','id' => $stafftask->tested_by])->first()->user_type}})</i></b>
                                    @endif
                                </li>
                                <li>Tester Remark : <b><i>{!!$stafftask['tester_remark']!!}</i></b></li>
                                <li>Test Status : <b><i>{{$stafftask['test_status']}}</i></b></li>
                                <li>Tested Date : <b><i>{{date('d M Y',strtotime($stafftask['test_date_time']))}}</i></b></li>
                                
                                <li>Agile Work Detail : <b><i>{!!$stafftask['agile_work_detail']!!}</i></b></li>
                                <li>Estimated Time (in mins) : <b><i>{{$stafftask['estimated_time']}}</i></b></li>
                                <li>Reviewed Tech Lead Name : <b><i>{{$stafftask['reviewed_tech_lead_name']}}</i></b></li>
                                <li>Tech Lead Adjusted Time (in mins) : <b><i>{{$stafftask['tech_lead_adjusted_time']}}</i></b></li>
                                <li>Tech Lead Remarks : <b><i>{!!$stafftask['tech_lead_remarks']!!}</i></b></li>
                                <li>Tech Lead Approval : 
                                    <?php if($stafftask['tech_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Team Lead Name : <b><i>{{$stafftask['team_lead_name']}}</i></b></li>
                                <li>Team Lead Remark : <b><i>{!!$stafftask['team_lead_remark']!!}</i></b></li>
                                <li>Team Lead Approval : 
                                    <?php if($stafftask['team_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Ceo Approval : 
                                    <?php if($stafftask['ceo_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Task Status : <b><i>{{$stafftask['task_status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-12 mt-5">
                <div class="card">
                    @if($stafftask)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Punching System Data')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Tracking Status : <b><i>{{$stafftask['task_tracking_status']}}</i></b></li>
                                <li>Started Date : <b><i>{{$stafftask['task_started_date'] != NULL ? date('d M Y',strtotime($stafftask['task_started_date'])) : ''}}</i></b></li>
                                <li>Started Time : <b><i>{{$stafftask['task_started_time'] != NULL ? date('h:i:s A',strtotime($stafftask['task_started_time'])) : ''}}</i></b></li>
                                <li>Ended Time : <b><i>{{$stafftask['task_ended_time'] != NULL ? date('h:i:s A',strtotime($stafftask['task_ended_time'])) : ''}}</i></b></li>
                                <li>Ended Date : <b><i>{{$stafftask['task_ended_date'] != NULL ? date('d M Y',strtotime($stafftask['task_ended_date'])) : ''}}</i></b></li>
                                
                                <?php
                                    $minutes = floor($stafftask['tracked_actual_time_taken']);
                                    $seconds = round(($stafftask['tracked_actual_time_taken'] - $minutes) * 60);
                                    $formatted_time = $stafftask['tracked_actual_time_taken'] == NULL ? '' : ($minutes . " min " . $seconds . " sec");
                                ?>
                                            
                                <li>Tracked Completion Time (in mins after reducing pause time): <b><i>{{$formatted_time}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection