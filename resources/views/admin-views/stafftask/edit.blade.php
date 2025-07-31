@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('staff task'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('staff task')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('stafftask_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.stafftask.update',[$stafftask['id']])}}" method="post" enctype="multipart/form-data" class="stafftask_form">
                            @csrf
                            @method('put')
                            
                            <?php $user_type = auth('customer')->user()->user_type; ?>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="date">{{ \App\CPU\translate('date')}}</label>
                                            <input placeholder="Enter date" type="date" name="date" class="form-control" id="date" value="{{$stafftask['date']}}" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                        </div>
                                        
                                        <style>
                                            .select2-container {
                                                width: 100%!important;
                                            }
                                        </style>
                                        
                                        <div class="form-group">
                                            <label for="team_lead_id">{{\App\CPU\translate('Project')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="project_id" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                                @foreach($projects as $b)
                                                    <option value="{{$b['id']}}" {{ $stafftask['project_id']==$b['id'] ? 'selected' : ''}}>{{$b['project_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task_number">{{ \App\CPU\translate('task_number')}}</label>
                                            <input placeholder="Enter task_number" type="text" name="task_number" class="form-control" value="{{$stafftask['task_number']}}" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task_name">{{ \App\CPU\translate('task_name')}}</label>
                                            <input placeholder="Enter task_name" type="text" name="task_name" class="form-control" value="{{$stafftask['task_name']}}" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task_description">{{ \App\CPU\translate('task_description')}}</label>
                                            <textarea name="task_description" placeholder="Enter task_description" class="editor textarea" cols="30" rows="10" {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>{!!$stafftask['task_description']!!}</textarea>
                                        </div>
                                        
                                        <?php 
                                            $test_case = '<table align="center" border="1" cellpadding="1" cellspacing="1" style="width:100%">
                                            	<thead>
                                            		<tr>
                                            			<th scope="col">MODULE</th>
                                            			<th scope="col">TEST_CASE_TITLE</th>
                                            			<th scope="col">DESCRIPTION</th>
                                            			<th scope="col">TEST_STEPS</th>
                                            			<th scope="col">EXPECTED_RESULT</th>
                                            			<th scope="col">ACTUAL_RESULT</th>
                                            			<th scope="col">PASS/FAIL</th>
                                            		</tr>
                                            	</thead>
                                            	<tbody>
                                            		<tr>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            			<td>&nbsp;</td>
                                            		</tr>
                                            	</tbody>
                                            </table>';
                                            
                                            if($stafftask['test_case'] == '') {
                                                $test_edit_val = $test_case;
                                            } else {
                                                $test_edit_val = $stafftask['test_case'];
                                            }
                                        ?>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('test_case')}}</label>
                                            <textarea name="test_case" placeholder="Enter test_case" class="editor textarea" cols="30" rows="10" {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>{!!$test_edit_val!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="test_case_updated">{{ \App\CPU\translate('test_case_updated')}}</label>
                                            <select name="test_case_updated" class="form-control" id="test_case_updated" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                                <option value="1"{{ $stafftask['test_case_updated']=="1" ? 'selected' : ''}}>Yes</option>
                                                <option value="0"{{ $stafftask['test_case_updated']=="0" ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="agile_work_detail">{{ \App\CPU\translate('agile_work_detail')}}</label>
                                            <textarea name="agile_work_detail" placeholder="Enter agile_work_detail" class="editor textarea" cols="30" rows="10" {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>{!!$stafftask['agile_work_detail']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="estimated_time">{{ \App\CPU\translate('estimated_time')}} (in mins)</label>
                                            <input placeholder="Enter estimated_time" type="number" name="estimated_time" class="form-control" value="{{$stafftask['estimated_time']}}" required {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="col-md-12 mt-5 mb-5">
                                        <h2 style="text-align:center;">Technical lead Edit Section</h2>
                                        
                                        <div class="form-group">
                                            <label for="tech_lead_adjusted_time">{{ \App\CPU\translate('tech_lead_adjusted_time')}} (in mins)</label>
                                            <input placeholder="Enter tech_lead_adjusted_time" type="number" name="tech_lead_adjusted_time" class="form-control" value="{{$stafftask['tech_lead_adjusted_time']}}" {{ ($user_type!="TECHNICAL_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tech_lead_remarks">{{ \App\CPU\translate('tech_lead_remarks')}}</label>
                                            <textarea name="tech_lead_remarks" placeholder="Enter tech_lead_remarks" class="editor textarea" cols="30" rows="10" {{ ($user_type!="TECHNICAL_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>{!!$stafftask['tech_lead_remarks']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tech_lead_approval">{{ \App\CPU\translate('tech_lead_approval')}}</label>
                                            <select name="tech_lead_approval" class="form-control" id="tech_lead_approval" {{ ($user_type!="TECHNICAL_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                                <option value="1" {{ $stafftask['tech_lead_approval']=="1" ? 'selected' : ''}}>Approved</option>
                                                <option value="0" {{ $stafftask['tech_lead_approval']=="0" ? 'selected' : ''}}>Not Approved</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mt-5 mb-5">
                                        <h2 style="text-align:center;">Team lead Edit Section</h2>
                                        
                                        <div class="form-group">
                                            <label for="team_lead_remark">{{ \App\CPU\translate('team_lead_remark')}}</label>
                                            <textarea name="team_lead_remark" placeholder="Enter team_lead_remark" class="editor textarea" cols="30" rows="10"  {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>{!!$stafftask['team_lead_remark']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="team_lead_approval">{{ \App\CPU\translate('team_lead_approval')}}</label>
                                            <select name="team_lead_approval" class="form-control" id="team_lead_approval"  {{ ($user_type!="TEAM_LEAD" && $user_type!="CEO") ? 'disabled' : ''}}>
                                                <option value="1" {{ $stafftask['team_lead_approval']=="1" ? 'selected' : ''}}>Approved</option>
                                                <option value="0" {{ $stafftask['team_lead_approval']=="0" ? 'selected' : ''}}>Not Approved</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mt-5 mb-5">
                                        <h2 style="text-align:center;">CEO Edit Section</h2>
                                        
                                        <div class="form-group">
                                            <label for="ceo_approval">{{ \App\CPU\translate('ceo_approval')}}</label>
                                            <select name="ceo_approval" class="form-control" id="ceo_approval"  {{ ($user_type!="CEO") ? 'disabled' : ''}}>
                                                <option value="1" {{ $stafftask['ceo_approval']=="1" ? 'selected' : ''}}>Approved</option>
                                                <option value="0" {{ $stafftask['ceo_approval']=="0" ? 'selected' : ''}}>Not Approved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.stafftask.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
                                        <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function () {
            mbimagereadURL(this);
        });
        
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
@endpush