@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('marks'))
@push('css_or_js')
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/back-end/css/custom.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('interview') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('marks') }}</li>
            </ol>
        </nav>
        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if (
            $user_type == 'PRODUCT_OWNER' ||
                $user_type == 'HR' ||
                $user_type == 'TEAM_LEAD' ||
                $user_type == 'TECHNICAL_LEAD' ||
                $user_type == 'STAFF' ||
                $user_type == 'CEO')
            <!-- Content Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.interview.add_marks', [$interview->id]) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="marks">{{ \App\CPU\translate('marks') }}</label>
                                                <input placeholder="Enter marks" type="text" name="marks"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <button type="submit"
                                        class="btn btn-primary float-right">{{ \App\CPU\translate('add') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ \App\CPU\translate('marks') }} {{ \App\CPU\translate('Table') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ \App\CPU\translate('sl') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('action') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('Candidate') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('Interviewer') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('marks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @foreach ($marks as $k => $de_p)
                                        <?php $total = $total + $de_p->marks; ?>
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>
                                                <?php $user_id = auth('customer')->user()->id; ?>

                                                <?php if($user_id == $de_p->user_id) { ?>
                                                <a title="{{ trans('Delete') }}" class="btn btn-danger btn-sm delete"
                                                    id="{{ $de_p->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>

                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.interview.edit_marks', [$interview->id, $de_p->id]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <b><i>{{ \App\Model\Candidate::where(['id' => $interview->candidate_id])->first()->name }}</i></b>
                                            </td>
                                            <td>
                                                <b><i>{{ \App\User::where(['id' => $de_p->user_id])->first()->name }}</i></b>
                                            </td>
                                            <td style="text-align:right;">{{ $de_p->marks }}</td>
                                        </tr>
                                    @endforeach

                                    <?php if(count($marks) > 0) { ?>
                                    <tr>
                                        <th scope="col" colspan="4" style="text-align:right;">Total Marks</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $total }}
                                        </th>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_marks') }}?",
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
                        url: "{{ route('admin.interview.delete_marks') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('marks_removed_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
