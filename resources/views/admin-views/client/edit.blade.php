@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('client'))
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
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('client')}}</li>
            </ol>
        </nav>
        
        <div class="row pt-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('client_update_form')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.client.update',[$client['id']])}}" method="post" enctype="multipart/form-data" class="client_form">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('Image')}}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="image" id="mbimageFileUploader" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label" for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('name')}}</label>
                                            <input placeholder="Enter name" type="text" name="name" class="form-control" id="name" value="{{$client['name']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">{{ \App\CPU\translate('email')}}</label>
                                            <input placeholder="Enter email" type="email" name="email" class="form-control" id="email" value="{{$client['email']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">{{ \App\CPU\translate('password')}}</label>
                                            <input placeholder="Enter password" type="password" name="password" class="form-control" id="password" autocomplete="new-password">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="phone">{{ \App\CPU\translate('phone')}}</label>
                                            <input placeholder="Enter phone" type="text" name="phone" class="form-control" id="phone" value="{{$client['phone']}}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="place">{{ \App\CPU\translate('place')}}</label>
                                            <input placeholder="Enter place" type="text" name="place" class="form-control" id="place" value="{{$client['place']}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">{{ \App\CPU\translate('address')}}</label>
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10" required>{!! $client['address'] !!}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="gender">{{ \App\CPU\translate('gender')}}</label>
                                            <select name="gender" class="form-control" id="type_form" value="{{$client['gender']}}" required>
                                                <option value="Male" {{ $client['gender']=='Male' ? 'selected' : ''}}>Male</option>
                                                <option value="Female" {{ $client['gender']=='Female' ? 'selected' : ''}}>Female</option>
                                                <option value="Others" {{ $client['gender']=='Others' ? 'selected' : ''}}>Others</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="date_of_birth">{{ \App\CPU\translate('date_of_birth')}}</label>
                                            <input placeholder="Enter date_of_birth" type="date" name="date_of_birth" class="form-control" id="date_of_birth" value="{{$client['date_of_birth']}}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification')}}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification" class="form-control" id="qualification" value="{{$client['qualification']}}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience')}}</label>
                                            <input placeholder="Enter experience" type="text" name="experience" class="form-control" id="experience" value="{{$client['experience']}}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="expertise">{{ \App\CPU\translate('expertise')}}</label>
                                            <input placeholder="Enter expertise" type="text" name="expertise" class="form-control" id="expertise" value="{{$client['expertise']}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <center>
                                            <img style="width: auto;border: 1px solid; border-radius: 10px; max-width:400px;" id="mbImageviewer" src="{{asset('storage/app/public/banner')}}/{{$client['image']}}" alt=""/>
                                        </center>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a class="btn btn-secondary text-white cancel" href="{{route('admin.client.list')}}">{{ \App\CPU\translate('Cancel')}}</a>
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