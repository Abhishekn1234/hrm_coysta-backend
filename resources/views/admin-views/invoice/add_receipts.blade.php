@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('receipts'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('invoice') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('Receipts') }}</li>
            </ol>
        </nav>
        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
            <!-- Content Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="h3 mb-0 text-black-50">{{ $invoice['invoice_number'] }}</h1>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.invoice.add_receipts', [$invoice['id']]) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="receipt_number">{{ \App\CPU\translate('receipt_number') }}</label>
                                                <input value="{{ $receipt_number }}" readonly
                                                    placeholder="Enter receipt_number" type="text" name="receipt_number"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <?php $today_date = date('Y-m-d'); ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="receipt_date">{{ \App\CPU\translate('receipt_date') }}</label>
                                                <input value="{{ $today_date }}" placeholder="Enter receipt_date"
                                                    type="date" name="receipt_date" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="amount_paid">{{ \App\CPU\translate('amount_paid') }} $</label>
                                                <input placeholder="Enter amount_paid" type="text" name="amount_paid"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="payment_method">{{ \App\CPU\translate('payment_method') }}</label>
                                                <select class="form-control" name="payment_method">
                                                    <option value="CASH">CASH</option>
                                                    <option value="CARD">CARD</option>
                                                    <option value="ONLINE">ONLINE</option>
                                                </select>
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
                        <h5>{{ \App\CPU\translate('receipt') }} {{ \App\CPU\translate('Table') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ \App\CPU\translate('sl') }}</th>
                                        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th scope="col">{{ \App\CPU\translate('action') }}</th>
                                        @endif
                                        <th scope="col">{{ \App\CPU\translate('receipt_number') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('receipt_date') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('payment_method') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('amount_paid') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @foreach ($receipts as $k => $de_p)
                                        <?php $total = $total + $de_p->amount_paid; ?>
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    <a title="{{ trans('Delete') }}" class="btn btn-danger btn-sm delete"
                                                        id="{{ $de_p->id }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>

                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.invoice.edit_receipts', [$invoice['id'], $de_p->id]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>

                                                    <a class="btn btn-info btn-sm mr-1" target="_blank"
                                                        title="{{ \App\CPU\translate('Generate receipt') }}"
                                                        href="{{ route('admin.invoice.generate_receipt', [$de_p->id]) }}">
                                                        <i class="tio-download"></i> Generate
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $de_p->receipt_number }}</td>
                                            <td>{{ date('d M Y', strtotime($de_p->receipt_date)) }}</td>
                                            <td>{{ $de_p->payment_method }}</td>
                                            <td style="text-align:right;">{{ $de_p->amount_paid }} $</td>
                                        </tr>
                                    @endforeach

                                    <?php
                                    if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD') {
                                        $cospan = 5;
                                    } else {
                                        $cospan = 4;
                                    }
                                    ?>

                                    <?php if(count($receipts) > 0) { ?>
                                    <tr>
                                        <th scope="col" colspan="{{ $cospan }}" style="text-align:right;">Grand
                                            Total</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $total }} $
                                        </th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="{{ $cospan }}" style="text-align:right;">Amount
                                            to be paid</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $items_total }} $
                                        </th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="{{ $cospan }}" style="text-align:right;">
                                            Balance</th>
                                        <th scope="col" colspan="1" style="text-align:right;">
                                            {{ $items_total - $total }} $</th>
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
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_receipts') }}?",
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
                        url: "{{ route('admin.invoice.delete_receipts') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('receipts_removed_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
