@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('task'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('task')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('task_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.task.update',[$task['id']])}}" method="post" enctype="multipart/form-data" class="task_form">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="date">{{ \App\CPU\translate('date')}}</label>
                                            <input placeholder="Enter date" type="date" name="date" class="form-control" id="date" value="{{$task['date']}}" required>
                                        </div>
                                        
                                        <style>
                                            .select2-container {
                                                width: 100%!important;
                                            }
                                        </style>
                                        
                                        <div class="form-group">
                                            <label for="team_lead_id">{{\App\CPU\translate('Project')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="project_id" required>
                                                @foreach($projects as $b)
                                                    <option value="{{$b['id']}}" {{ $task['project_id']==$b['id'] ? 'selected' : ''}}>{{$b['project_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task_name">{{ \App\CPU\translate('task_name')}}</label>
                                            <input placeholder="Enter task_name" type="text" name="task_name" class="form-control" value="{{$task['task_name']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task_description">{{ \App\CPU\translate('task_description')}}</label>
                                            <textarea name="task_description" placeholder="Enter task_description" class="editor textarea" cols="30" rows="10">{!!$task['task_description']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('ui_sample')}}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="ui_sample" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('database_file')}}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="database_file" class="custom-file-input" accept=".jpg, .sql, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
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
                                            
                                            if($task['test_case'] == '') {
                                                $test_edit_val = $test_case;
                                            } else {
                                                $test_edit_val = $task['test_case'];
                                            }
                                        ?>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('test_case')}}</label>
                                            <textarea name="test_case" placeholder="Enter test_case" class="editor textarea" cols="30" rows="10">{!!$test_edit_val!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="test_case_updated">{{ \App\CPU\translate('test_case_updated')}}</label>
                                            <select name="test_case_updated" class="form-control" id="test_case_updated" required>
                                                <option value="1"{{ $task['test_case_updated']=="1" ? 'selected' : ''}}>Yes</option>
                                                <option value="0"{{ $task['test_case_updated']=="0" ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="agile_work_detail">{{ \App\CPU\translate('agile_work_detail')}}</label>
                                            <textarea name="agile_work_detail" placeholder="Enter agile_work_detail" class="editor textarea" cols="30" rows="10">{!!$task['agile_work_detail']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="estimated_time">{{ \App\CPU\translate('estimated_time')}} (in mins)</label>
                                            <input readonly placeholder="Enter estimated_time" type="number" name="estimated_time" class="form-control" value="{{$task['estimated_time']}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.task.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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