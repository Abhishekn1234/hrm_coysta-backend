@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('hire'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('hire') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="hire-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('hire_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $hires->total() }})</h5>
                                </div>
                            </div>
                            <div class="col-12 mt-1 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search_by_hire') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_gender" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search hire') }}" aria-label="Search orders"
                                            value="{{ $filter_gender }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-12 col-lg-12">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.hire.list') }}/?filter_gender='+this.value+''">
                                <option value="" {{ $filter_gender == '' ? 'selected' : '' }}>Select Gender</option>
                                <option value="Male" {{ $filter_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $filter_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Others" {{ $filter_gender == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                    </div>

                    <?php $user_id = auth('customer')->user()->id; ?>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Hourly Rate</th>
                                        <th>Monthly Rate</th>
                                        <th>Qualification</th>
                                        <th>Experience</th>
                                        <th>Expertise</th>

                                    </tr>
                                </thead>
                                @foreach ($hires as $key => $hire)
                                    <tbody>
                                        <tr>
                                            <td>{{ $hire->id }}</td>
                                            <td style="text-align:center;">
                                                <?php $count = DB::table('hire_request')
                                                    ->where(['client_id' => $user_id, 'staff_id' => $hire->id])
                                                    ->count(); ?>

                                                <?php if($count == 0) { ?>
                                                <a class="btn btn-success btn-sm hire_now"
                                                    title="{{ \App\CPU\translate('hire_now') }}" style="cursor: pointer;"
                                                    id="{{ $hire['id'] }}">Hire Now</a>
                                                <?php } else { ?>
                                                <b><i>Request Sent</i></b>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <img width="80"
                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/banner') }}/{{ $hire['image'] }}">
                                            </td>
                                            <?php $name = explode(' ', $hire->name); ?>
                                            <!--<td>{{ $name[0] }} <br>( {{ $hire->designation }} )</td>-->
                                            <td>{{ $name[0] }}</td>
                                            <td>{{ now()->diffInYears($hire->date_of_birth) }}</td>
                                            <td>{{ $hire->gender }}</td>
                                            <td>
                                                <h1><b><i>{{ $hire->hourly_rate }} $</i></b></h1>
                                            </td>
                                            <td>
                                                <h1><b><i>{{ $hire->monthly_rate }} $</i></b></h1>
                                            </td>
                                            <td>{{ $hire->qualification }}</td>
                                            <td>{{ $hire->experience }}</td>
                                            <td>{{ $hire->expertise }}</td>

                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $hires->links() }}
                    </div>

                    @if (count($hires) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
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

                reader.onload = function(e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function() {
            mbimagereadURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });
    </script>

    <script>
        $(document).on('click', '.hire_now', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_to_hire_this_staff') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('hire_now') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.hire.hire_now') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('hire_request_sent_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
