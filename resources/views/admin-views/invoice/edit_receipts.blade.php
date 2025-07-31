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
                <li class="breadcrumb-item">{{ \App\CPU\translate('Edit receipts') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 text-black-50">{{ $invoice['invoice_number'] }}</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.invoice.edit_receipts', [$invoice['id'], $receipts->id]) }}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="receipt_number">{{ \App\CPU\translate('receipt_number') }}</label>
                                            <input value="{{ $receipts->receipt_number }}" readonly
                                                placeholder="Enter receipt_number" type="text" name="receipt_number"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <?php $today_date = date('Y-m-d'); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="receipt_date">{{ \App\CPU\translate('receipt_date') }}</label>
                                            <input value="{{ $receipts->receipt_date }}" placeholder="Enter receipt_date"
                                                type="date" name="receipt_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount_paid">{{ \App\CPU\translate('amount_paid') }} $</label>
                                            <input value="{{ $receipts->amount_paid }}" placeholder="Enter amount_paid"
                                                type="text" name="amount_paid" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method">{{ \App\CPU\translate('payment_method') }}</label>
                                            <select class="form-control" name="payment_method">
                                                <option value="CASH"
                                                    {{ $receipts->payment_method == 'CASH' ? 'selected' : '' }}>CASH</option>
                                                <option value="CARD"
                                                    {{ $receipts->payment_method == 'CARD' ? 'selected' : '' }}>CARD</option>
                                                <option value="ONLINE"
                                                    {{ $receipts->payment_method == 'ONLINE' ? 'selected' : '' }}>ONLINE
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('Edit') }}</button>
                            </div>
                        </form>
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
