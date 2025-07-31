@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('testcase'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('testcase')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('testcase_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.testcase.update',[$testcase['id']])}}" method="post" enctype="multipart/form-data" class="testcase_form">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
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
                                            
                                            if($testcase['test_case'] == '') {
                                                $test_edit_val = $test_case;
                                            } else {
                                                $test_edit_val = $testcase['test_case'];
                                            }
                                        ?>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('test_case')}}</label>
                                            <textarea name="test_case" placeholder="Enter test_case" class="editor textarea" cols="30" rows="10" required>{!!$test_edit_val!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tester_remark">{{ \App\CPU\translate('tester_remark')}}</label>
                                            <textarea name="tester_remark" placeholder="Enter tester_remark" class="editor textarea" cols="30" rows="10">{!!$testcase['tester_remark  ']!!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="test_status">{{ \App\CPU\translate('test_status')}}</label>
                                            <select name="test_status" class="form-control" id="test_case_updated" required>
                                                <option value="TESTING"{{ $testcase['test_status']=="TESTING" ? 'selected' : ''}}>TESTING</option>
                                                <option value="FAILED"{{ $testcase['test_status']=="FAILED" ? 'selected' : ''}}>FAILED</option>
                                                <option value="SUCCESS"{{ $testcase['test_status']=="SUCCESS" ? 'selected' : ''}}>SUCCESS</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.testcase.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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