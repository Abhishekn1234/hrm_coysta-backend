@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('quotation'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('quotation') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="quotation-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('quotation_report_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $quotation_reports->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_quotation') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_status" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search quotation') }}"
                                            aria-label="Search orders" value="{{ $filter_status }}">

                                        <input id="" type="hidden" name="filter_client" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search quotation') }}"
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
                                onchange="location.href='{{ route('admin.quotation.list') }}/?filter_status='+this.value+'&filter_client={{ $filter_client }}'">
                                <option value="" {{ $filter_status == '' ? 'selected' : '' }}>Select Status</option>
                                <option value="PENDING" {{ $filter_status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                <option value="ACCEPTED" {{ $filter_status == 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED</option>
                                <option value="REJECTED" {{ $filter_status == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                            </select>
                        </div>

                        <div class="col-12 mt-1 col-md-6 col-lg-6">
                            <select name="qty_ordr_sort1"
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                onchange="location.href='{{ route('admin.quotation.list') }}/?filter_status={{ $filter_status }}&filter_client='+this.value+''">
                                <option value="0" {{ $filter_client == '' ? 'selected' : '' }}>Select Client</option>
                                @foreach ($client_list as $b)
                                    <option value="{{ $b['id'] }}" {{ $filter_client == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['name'] }}</option>
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
                                        <th>Client</th>
                                        <th>Proposal</th>
                                        <th>Quotation Number</th>
                                        <th>Quotation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($quotation_reports as $key => $quotation_report)
                                    <tbody>
                                        <tr>
                                            <td>{{ $quotation_report->id }}</td>
                                            <td>
                                                @if (\App\User::where(['id' => $quotation_report->client_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $quotation_report->client_id])->first()->name }}
                                                            ({{ \App\User::where(['id' => $quotation_report->client_id])->first()->user_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                @if (\App\Model\Proposal::where(['id' => $quotation_report->proposal_id])->first())
                                                    <b><i>{{ \App\Model\Proposal::where(['id' => $quotation_report->proposal_id])->first()->proposal_title }}</i></b>
                                                @endif
                                            </td>

                                            <td><b><i>{{ $quotation_report->quotation_number }}</i></b></td>
                                            <td>{{ date('d M Y', strtotime($quotation_report->quotation_date)) }}</td>
                                            <td>
                                                <b><i>{{ $quotation_report->status }}</i></b>
                                            </td>


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $quotation_reports->links() }}
                    </div>

                    @if (count($quotation_reports) == 0)
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
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

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
        $('#main-quotation-add').on('click', function() {
            $('#main-quotation').show();
        });

        $('.cancel').on('click', function() {
            $('.quotation_form').attr('action', "{{ route('admin.quotation.store') }}");
            $('#main-quotation').hide();
        });

        $(document).on('change', '.quotation_status_change', function() {
            var id = $(this).attr("id");
            var status = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.quotation.quotation_status_change') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    }
                    // location.reload();
                }
            });
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
                url: "{{ route('admin.quotation.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('quotation_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('quotation_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_quotation') }}?",
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
                        url: "{{ route('admin.quotation.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('quotation_deleted_successfully') }}'
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
