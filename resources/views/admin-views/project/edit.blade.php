@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('project'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('project')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('project_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.project.update',[$project['id']])}}" method="post" enctype="multipart/form-data" class="project_form">
                            @csrf
                            @method('put')
                            
                            <style>
                                .select2-container--default .select2-selection--multiple .select2-selection__choice {
                                    background-color: #177bbb;
                                    border: 1px solid #177bbb;
                                    border-radius: 3px;
                                    color: #fff;
                                }
                                
                                .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                                    color: inherit;
                                }
                                
                                .select2-container {
                                    width: 100%!important;
                                }
                            </style>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="project_name">{{ \App\CPU\translate('project_name')}}</label>
                                            <input placeholder="Enter project_name" type="text" name="project_name" class="form-control" id="project_name" value="{{$project['project_name']}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="project_description">{{ \App\CPU\translate('project_description')}}</label>
                                            <textarea name="project_description" placeholder="Enter project_description" class="editor textarea" cols="30" rows="10" required>{!! $project['project_description'] !!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="project_starting_date">{{ \App\CPU\translate('project_starting_date')}}</label>
                                            <input placeholder="Enter project_starting_date" type="date" name="project_starting_date" class="form-control" id="project_starting_date" value="{{$project['project_starting_date']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="expected_release_date">{{ \App\CPU\translate('expected_release_date')}}</label>
                                            <input placeholder="Enter expected_release_date" type="date" name="expected_release_date" class="form-control" id="expected_release_date" value="{{$project['expected_release_date']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="deadline">{{ \App\CPU\translate('deadline')}}</label>
                                            <input placeholder="Enter deadline" type="date" name="deadline" class="form-control" id="deadline" value="{{$project['deadline']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="product_owner_id">{{\App\CPU\translate('Product Owner')}}</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="product_owner_id" required>
                                                @foreach($owner as $b)
                                                    <option value="{{$b['id']}}" {{ $project->product_owner_id==$b['id'] ? 'selected' : ''}}>{{$b['name']}} ( {{$b['designation']}} )</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="staffs" style="padding-bottom: 3px">Staffs</label>
                                            <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="choice_staffs[]" id="choice_staffs" multiple="multiple" required>
                                                @foreach ($staff_list as $key => $a)
                                                    @if($project['staff_ids']!='null' && $project['staff_ids']!='')
                                                        <option value="{{ $a['id']}}"  {{in_array($a->id,json_decode($project['staff_ids'],true))?'selected':''}}>{{$a['name']}} ( {{$a['user_type']}} )</option>
                                                    @else
                                                        <option value="{{ $a['id']}}">{{$a['name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.project.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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