@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('inventory'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('inventory') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        <div class="row pt-4" id="main-inventory"
            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('inventory_view') }}
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table tree-table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name *</th>
                                        <th>HSN Code *</th>
                                        <th>Stock Category *</th>
                                        <th>Unit *</th>
                                        <th>Worth (₹)</th>
                                        <th>Vendor</th>
                                        <th>Base Inventory</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="inventoryBody">
                                    <tr class="main-item">
                                        <td>
                                            <input readonly type="text" class="form-control form-control-sm"
                                                name="item_name[]" value="{{ $inventory->item_name }}">
                                        </td>
                                        <td><input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm" value="{{ $inventory->hsn_code }}">
                                        </td>
                                        <td>
                                            <input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm"
                                                value="{{ $inventory->stock_category }}">
                                        </td>
                                        <td>
                                            <input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm" value="{{ $inventory->unit }}">
                                        </td>
                                        <td> <input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm"
                                                value="{{ $inventory->worth ?? 0.0 }}">
                                        </td>
                                        <td>
                                            <input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm"
                                                value="{{ $inventory->vendor ?? '' }}">
                                        </td>
                                        <td>
                                            <input readonly type="text" name="hsn_code[]"
                                                class="form-control form-control-sm"
                                                value="{{ $inventory->base_inventory->item_name ?? 'None' }}">
                                        </td>

                                        <td>
                                            <button id="#detailsModalButton" type="button" class="btn btn-sm btn-info"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal">More
                                                Details</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Details Modal -->
                    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Additional Inventory Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Description & Technical Specs -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $inventory->description ?? '' }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Technical Specifications</label>
                                                <div class="row g-2">
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->model_no ?? '' }}" name="model_no[]"
                                                            type="text" class="form-control">
                                                    </div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->brand_name ?? '' }}" name="brand_name[]"
                                                            type="text" class="form-control">
                                                    </div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->gm_code ?? '' }}" name="gm_code[]"
                                                            type="text" class="form-control"></div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->purchase_price ?? 0.0 }}"
                                                            name="purchase_price[]" type="number" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Physical Properties -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Physical Properties</label>
                                                <div class="row g-2">
                                                    <div class="col-4"><input readonly
                                                            value="{{ $inventory->length ?? 0.0 }}" name="length[]"
                                                            type="number" class="form-control"
                                                            placeholder="Length (cm)"></div>
                                                    <div class="col-4"><input readonly
                                                            value="{{ $inventory->height ?? 0.0 }}" name="height[]"
                                                            type="number" class="form-control"
                                                            placeholder="Height (cm)"></div>
                                                    <div class="col-4"><input readonly
                                                            value="{{ $inventory->width ?? 0.0 }}" name="width[]"
                                                            type="number" class="form-control" placeholder="Width (cm)">
                                                    </div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->weight ?? 0.0 }}" name="weight[]"
                                                            type="number" class="form-control"
                                                            placeholder="Weight (kg)"></div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->volume ?? 0.0 }}" name="volume[]"
                                                            type="number" class="form-control"
                                                            placeholder="Volume (m³)"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Electrical Specs & Rental -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Electrical Specifications</label>
                                                <div class="row g-2">
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->current ?? 0.0 }}" name="current[]"
                                                            type="number" class="form-control"
                                                            placeholder="Current (A)"></div>
                                                    <div class="col-6"><input readonly
                                                            value="{{ $inventory->power ?? 0.0 }}" name="power[]"
                                                            type="number" class="form-control" placeholder="Power (W)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Rental Information</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" readonly
                                                        value="{{ $inventory->rental_information ?? 0.0 }}"
                                                        name="rental_information[]" class="form-control"
                                                        placeholder="Price/Day">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="descriptionSaveButton" class="btn btn-primary"
                                        data-bs-dismiss="modal">Save</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    {{-- <div class="card-footer">
                        <a class="btn btn-secondary text-white cancel">{{ \App\CPU\translate('Cancel') }}</a>
                        <button id="add" type="submit"
                            class="btn btn-primary">{{ \App\CPU\translate('save') }}</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@push('script')
    <script>
        $.ready({
            $('#detailsModalButton').click(function(event) {
                event.preventDefault().
            });
            $('#descriptionSaveButton').click(function(event) {
                event.preventDefault().
            });

        })
    </script>
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
        $('#main-inventory-add').on('click', function() {
            $('#main-inventory').show();
        });

        $('.cancel').on('click', function() {
            $('.inventory_form').attr('action', "{{ route('admin.inventory.store') }}");
            $('#main-inventory').hide();
        });


        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_inventory') }}?",
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
                        url: "{{ route('admin.inventory.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('inventory_deleted_successfully') }}'
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
