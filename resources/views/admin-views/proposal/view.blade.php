@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('proposal'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('proposal') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
            <div class="row">
                <div class="col-md-12" id="proposal-btn">
                    <button id="main-proposal-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                        {{ \App\CPU\translate('add_proposal') }}</button>
                </div>
            </div>
        @endif

        <div class="row pt-4" id="main-proposal"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('proposal_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.proposal.store') }}" method="post" enctype="multipart/form-data"
                            class="proposal_form">
                            @csrf

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
                                    width: 100% !important;
                                }
                            </style>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="client_id">{{ \App\CPU\translate('Client') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="client_id" required>
                                                <option value="{{ null }}" selected disabled>Select Client</option>
                                                @foreach ($client_list as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }}
                                                        ({{ $b['user_type'] }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="proposal_title">{{ \App\CPU\translate('proposal_title') }}</label>
                                            <input placeholder="Enter proposal_title" type="text" name="proposal_title"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="proposal_date">{{ \App\CPU\translate('proposal_date') }}</label>
                                            <input placeholder="Enter proposal_date" type="date" name="proposal_date"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="proposal_description">{{ \App\CPU\translate('proposal_description') }}</label>
                                            <textarea name="proposal_description" placeholder="Enter proposal_description" class="editor textarea" cols="30"
                                                rows="10" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="min_expected_amount">{{ \App\CPU\translate('min_expected_amount in $') }}</label>
                                            <input placeholder="Enter min_expected_amount in $" type="text"
                                                name="min_expected_amount" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="max_expected_amount">{{ \App\CPU\translate('max_expected_amount in $') }}</label>
                                            <input placeholder="Enter max_expected_amount in $" type="text"
                                                name="max_expected_amount" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="direct_pdf">{{ \App\CPU\translate('direct_pdf') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="direct_pdf" id="mbimageFileUploader"
                                                    class="custom-file-input" accept=".pdf">
                                                <label class="custom-file-label"
                                                    for="mbimageFileUploader">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <a class="btn btn-secondary text-white cancel">{{ \App\CPU\translate('Cancel') }}</a>
                                <button id="add" type="submit"
                                    class="btn btn-primary">{{ \App\CPU\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px" id="proposal-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('proposal_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $proposals->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_proposal') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search proposal') }}"
                                            aria-label="Search orders" value="{{ $filter_status }}">

                                        <input id="" type="hidden" name="filter_client" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search proposal') }}"
                                            aria-label="Search orders" value="{{ $filter_client }}">

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.proposal.list') }}/?filter_status='+this.value+'&filter_client={{ $filter_client }}'">
                                <option value="" {{ $filter_status == '' ? 'selected' : '' }}>Select Status</option>
                                <option value="DRAFT" {{ $filter_status == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                                <option value="FINALIZED" {{ $filter_status == 'FINALIZED' ? 'selected' : '' }}>FINALIZED
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.proposal.list') }}/?filter_status={{ $filter_status }}&filter_client='+this.value+''">
                                <option value="0" {{ $filter_client == '' ? 'selected' : '' }}>Select Client</option>
                                @foreach ($client_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_client == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['name'] }} ({{ $b['user_type'] }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Direct Pdf</th>
                                        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th>Share</th>
                                        @endif
                                        <th>Client</th>
                                        <th>Proposal Title</th>
                                        <th>Proposal Date</th>
                                        <th>Min Expected Amount</th>
                                        <th>Max Expected Amount</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                @foreach ($proposals as $key => $proposal)
                                    <tbody>
                                        <tr>
                                            <td>{{ $proposal->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.proposal.view', [$proposal['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.proposal.edit', [$proposal['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $proposal['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>

                                                    <br>

                                                    <a class="btn btn-primary btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank"
                                                        title="{{ \App\CPU\translate('Generate Proposal') }}"
                                                        href="{{ route('admin.proposal.generate_proposal', [$proposal['id']]) }}">
                                                        <i class="tio-download"></i> Generate Proposal
                                                    </a>
                                                @endif
                                            </td>

                                            <td>
                                                <?php if($proposal['direct_pdf'] != '') { ?>
                                                <a target="_blank" class="btn btn-primary btn-sm edit"
                                                    href="{{ asset('storage/app/public/banner') }}/{{ $proposal['direct_pdf'] }}"
                                                    download="{{ $proposal['direct_pdf'] }}">
                                                    Pdf
                                                </a>
                                                <?php } else { ?>
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.proposal.edit', [$proposal['id']]) }}#direct_pdf"
                                                    style="cursor: pointer;">
                                                    Add
                                                </a>
                                                <?php } ?>
                                            </td>

                                            @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    @php($clientss = \App\User::where(['id' => $proposal->client_id])->first())
                                                    <?php
                                                    if ($clientss) {
                                                        $number = $clientss->phone;
                                                    } else {
                                                        $number = '9747625648';
                                                    }
                                                    $message = 'Proposal Pdf';
                                                    $url = "https://web.whatsapp.com/send?phone=$number&text=$message";
                                                    
                                                    if ($clientss) {
                                                        $email = $clientss->email;
                                                    } else {
                                                        $email = 'rishikeshr850@gmail.com';
                                                    }
                                                    
                                                    $subject = 'Proposal Pdf';
                                                    $body = 'Proposal Pdf';
                                                    $url2 = "mailto:$email?subject=$subject&body=$body";
                                                    ?>

                                                    <a class="btn btn-success btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank"
                                                        title="{{ \App\CPU\translate('Generate Proposal') }}"
                                                        href="{{ $url }}">
                                                        <i class="tio-whatsapp"></i>
                                                    </a>

                                                    <a class="btn btn-danger btn-sm mr-1" style="margin-top:10px;"
                                                        target="_blank"
                                                        title="{{ \App\CPU\translate('Generate Proposal') }}"
                                                        href="{{ $url2 }}">
                                                        <i class="tio-google"></i>
                                                    </a>
                                                </td>
                                            @endif
                                            <td>
                                                @if (\App\User::where(['id' => $proposal->client_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $proposal->client_id])->first()->name }}
                                                            ({{ \App\User::where(['id' => $proposal->client_id])->first()->user_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>{{ $proposal->proposal_title }}</td>
                                            <td>{{ date('d M Y', strtotime($proposal->proposal_date)) }}</td>
                                            <td>{{ $proposal->min_expected_amount }} $</td>
                                            <td>{{ $proposal->max_expected_amount }} $</td>
                                            <td><b><i>{{ $proposal->status }}</i></b></td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $proposals->links() }}
                    </div>

                    @if (count($proposals) == 0)
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
        $('#main-proposal-add').on('click', function() {
            $('#main-proposal').show();
        });

        $('.cancel').on('click', function() {
            $('.proposal_form').attr('action', "{{ route('admin.proposal.store') }}");
            $('#main-proposal').hide();
        });

        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.proposal.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('proposal_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('proposal_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_proposal') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
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
                        url: "{{ route('admin.proposal.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('proposal_deleted_successfully') }}'
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
