@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('task Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{route('admin.task.list')}}">
                                    {{\App\CPU\translate('tasks')}}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('task details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Task ID')}} #{{$task['id']}}</h1>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i>
                            {{\App\CPU\translate('Created At')}} : {{date('d M Y H:i:s',strtotime($task['created_at']))}}
                        </span>
                    </div>
                    
                    <a class="btn btn-primary btn-sm edit" title="{{ \App\CPU\translate('Edit')}}" href="{{route('admin.task.edit',[$task['tsid']])}}" style="cursor: pointer;"> 
                        <i class="tio-edit"></i> Edit Task
                    </a>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if($task)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('task info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Date : <b><i>{{date('d M Y',strtotime($task['date']))}}</i></b></li>
                                <li>Project : <b><i>{{$task['project_name']}}</i></b></li>
                                <li>Name : <b><i>{{$task['task_name']}}</i></b></li>
                                <li>Description : <b><i>{!!$task['task_description']!!}</i></b></li>
                                <li>UI Sample : 
                                    <b><i>
                                        <?php if($task['ui_sample'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$task->ui_sample)}}">Click here to display UI Sample</a>
                                        <?php } else { ?>
                                            UI Sample Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Database File : 
                                    <b><i>
                                        <?php if($task['database_file'] != ''){ ?> 
                                            <a href="{{asset('storage/app/public/banner/'.$task->database_file)}}">Click here to display Database File</a>
                                        <?php } else { ?>
                                            Database File Empty
                                        <?php } ?>
                                    </i></b>
                                </li>
                                
                                <li>Test Case : <b><i>{!!$task['test_case']!!}</i></b></li>
                                <li>Test Case Updated : 
                                    <?php if($task['test_case_updated'] == 1){ ?> 
                                        <b style="color:green;"><i>Yes</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>No</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Tested By : 
                                    @if($task->tested_by != 0)
                                        <b><i>{{\App\User::where(['status' => '1','id' => $task->tested_by])->first()->name}} ({{\App\User::where(['status' => '1','id' => $task->tested_by])->first()->user_type}})</i></b>
                                    @endif
                                </li>
                                <li>Tester Remark : <b><i>{!!$task['tester_remark']!!}</i></b></li>
                                <li>Test Status : <b><i>{{$task['test_status']}}</i></b></li>
                                <li>Tested Date : <b><i>{{date('d M Y',strtotime($task['test_date_time']))}}</i></b></li>
                                
                                <li>Agile Work Detail : <b><i>{!!$task['agile_work_detail']!!}</i></b></li>
                                <li>Estimated Time (in mins) : <b><i>{{$task['estimated_time']}}</i></b></li>
                                <li>Reviewed Tech Lead Name : <b><i>{{$task['reviewed_tech_lead_name']}}</i></b></li>
                                <li>Tech Lead Adjusted Time (in mins) : <b><i>{{$task['tech_lead_adjusted_time']}}</i></b></li>
                                <li>Tech Lead Remarks : <b><i>{!!$task['tech_lead_remarks']!!}</i></b></li>
                                <li>Tech Lead Approval : 
                                    <?php if($task['tech_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Team Lead Name : <b><i>{{$task['team_lead_name']}}</i></b></li>
                                <li>Team Lead Remark : <b><i>{!!$task['team_lead_remark']!!}</i></b></li>
                                <li>Team Lead Approval : 
                                    <?php if($task['team_lead_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Ceo Approval : 
                                    <?php if($task['ceo_approval'] == 1){ ?> 
                                        <b style="color:green;"><i>Approved</i></b>
                                    <?php } else { ?>
                                        <b style="color:red;"><i>Not Approved</i></b>
                                    <?php } ?><br>
                                </li>
                                <li>Task Status : <b><i>{{$task['task_status']}}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-12 mt-5">
                <div class="card">
                    @if($task)
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Punching System Data')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2"> 
                                <li>Tracking Status : <b><i>{{$task['task_tracking_status']}}</i></b></li>
                                <li>Started Date : <b><i>{{$task['task_started_date'] != NULL ? date('d M Y',strtotime($task['task_started_date'])) : ''}}</i></b></li>
                                <li>Started Time : <b><i>{{$task['task_started_time'] != NULL ? date('h:i:s A',strtotime($task['task_started_time'])) : ''}}</i></b></li>
                                <li>Ended Time : <b><i>{{$task['task_ended_time'] != NULL ? date('h:i:s A',strtotime($task['task_ended_time'])) : ''}}</i></b></li>
                                <li>Ended Date : <b><i>{{$task['task_ended_date'] != NULL ? date('d M Y',strtotime($task['task_ended_date'])) : ''}}</i></b></li>
                                
                                <?php
                                    $minutes = floor($task['tracked_actual_time_taken']);
                                    $seconds = round(($task['tracked_actual_time_taken'] - $minutes) * 60);
                                    $formatted_time = $task['tracked_actual_time_taken'] == NULL ? '' : ($minutes . " min " . $seconds . " sec");
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