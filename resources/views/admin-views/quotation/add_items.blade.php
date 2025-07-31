@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Items'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Quotation') }}</li>
                <li class="breadcrumb-item">{{ \App\CPU\translate('Item') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
            <!-- Content Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="h3 mb-0 text-black-50">{{ $quotation['quotation_number'] }}</h1>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.quotation.add_items', [$quotation['id']]) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="item_name">{{ \App\CPU\translate('item_name') }}</label>
                                                <input placeholder="Enter item_name" type="text" name="item_name"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="item_description">{{ \App\CPU\translate('item_description') }}</label>
                                                <input placeholder="Enter item_description" type="text"
                                                    name="item_description" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="quantity">{{ \App\CPU\translate('quantity') }}</label>
                                                <input placeholder="Enter quantity" type="number" name="quantity"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price">{{ \App\CPU\translate('price') }} $</label>
                                                <input placeholder="Enter price" type="text" name="price"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tax">{{ \App\CPU\translate('tax') }} %</label>
                                                <input placeholder="Enter tax" type="number" name="tax"
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
                        <h5>{{ \App\CPU\translate('Iiem') }} {{ \App\CPU\translate('Table') }}</h5>
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
                                        <th scope="col">{{ \App\CPU\translate('item_name') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('item_description') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('quantity') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('price') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('tax') }}</th>
                                        <th scope="col">{{ \App\CPU\translate('total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @foreach ($items as $k => $de_p)
                                        <?php $total = $total + $de_p->total; ?>
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
                                                        href="{{ route('admin.quotation.edit_items', [$quotation['id'], $de_p->id]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $de_p->item_name }}</td>
                                            <td>{{ $de_p->item_description }}</td>
                                            <td>{{ $de_p->quantity }} $</td>
                                            <td style="text-align:right;">{{ $de_p->price }} $</td>
                                            <td>{{ $de_p->tax }} %</td>
                                            <td style="text-align:right;">{{ $de_p->total }} $</td>
                                        </tr>
                                    @endforeach

                                    <?php
                                    if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD') {
                                        $cospan = 7;
                                    } else {
                                        $cospan = 6;
                                    }
                                    ?>

                                    <?php if(count($items) > 0) { ?>
                                    <tr>
                                        <th scope="col" colspan="{{ $cospan }}" style="text-align:right;">Grand
                                            Total</th>
                                        <th scope="col" colspan="1" style="text-align:right;">{{ $total }} $
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
                title: "{{ \App\CPU\translate('Are_you_sure_remove_this_item') }}?",
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
                        url: "{{ route('admin.quotation.delete_items') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            toastr.success(
                                '{{ \App\CPU\translate('item_removed_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
