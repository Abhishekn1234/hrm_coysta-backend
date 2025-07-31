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
                        {{ \App\CPU\translate('inventory_update_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.inventory.update', $inventory->id) }}" method="post"
                            class="inventory_form">
                            @csrf
                            @method('PUT')
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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inventoryBody">
                                        <tr class="main-item">
                                            <td>
                                                <span class="toggle-icon">▼</span>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="item_name[]"
                                                    placeholder="Item Name"value="{{ $inventory->item_name }}" required>
                                            </td>
                                            <td><input value="{{ $inventory->hsn_code }}" type="text" name="hsn_code[]"
                                                    class="form-control form-control-sm" required></td>
                                            <td>
                                                <select name="stock_category[]" class="form-select form-select-sm" required>
                                                    <option value="">Select</option>
                                                    <option
                                                        {{ $inventory->stock_category == 'electronics' ? 'selected' : '' }}
                                                        value="electronics">Electronics</option>
                                                    <option
                                                        {{ $inventory->stock_category == 'furniture' ? 'selected' : '' }}
                                                        value="furniture">Furniture</option>
                                                    <option
                                                        {{ $inventory->stock_category == 'machinery' ? 'selected' : '' }}
                                                        value="machinery">Machinery</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="unit[]" class="form-select form-select-sm" required>
                                                    <option {{ $inventory->unit == 'piece' ? 'selected' : '' }}
                                                        value="piece">Piece</option>
                                                    <option {{ $inventory->unit == 'set' ? 'selected' : '' }}
                                                        value="set">Set</option>
                                                    <option {{ $inventory->unit == 'kg' ? 'selected' : '' }}
                                                        value="kg">Kg</option>
                                                    <option {{ $inventory->unit == 'liter' ? 'selected' : '' }}
                                                        value="liter">Liter</option>
                                                </select>
                                            </td>
                                            <td><input name="worth[]" type="number" class="form-control form-control-sm"
                                                    value="{{ $inventory->worth }}">
                                            </td>
                                            <td>
                                                <select name="vendor[]" class="form-select form-select-sm vendor-select">
                                                    <option value="">Select Vendor</option>
                                                    <option {{ $inventory->vendor == 'vendor_a' ? 'selected' : '' }}
                                                        value="vendor_a">Vendor A</option>
                                                    <option {{ $inventory->vendor == 'vendor_b' ? 'selected' : '' }}
                                                        value="vendor_b">Vendor B</option>
                                                    <option {{ $inventory->vendor == 'vendor_c' ? 'selected' : '' }}
                                                        value="vendor_c">Vendor C</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button id="#detailsModalButton" type="button" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal" data-bs-target="#detailsModal">More
                                                    Details</button>
                                                <button type="button" class="btn btn-sm btn-success add-sub">+ Sub</button>
                                                <button class="btn btn-sm btn-danger delete">×</button>
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
                                                <textarea name="description[]" class="form-control" rows="3">{{ $inventory->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Technical Specifications</label>
                                                <div class="row g-2">
                                                    <div class="col-6"><input value="{{ $inventory->model_no }}"
                                                            name="model_no[]" type="text" class="form-control"
                                                            placeholder="Model No"></div>
                                                    <div class="col-6"><input name="brand_name[]"
                                                            value="{{ $inventory->brand_name }}" type="text"
                                                            class="form-control" placeholder="Brand Name"></div>
                                                    <div class="col-6"><input value="{{ $inventory->gm_code }}"
                                                            name="gm_code[]" type="text" class="form-control"
                                                            placeholder="GM Code"></div>
                                                    <div class="col-6"><input name="purchase_price[]"
                                                            value="{{ $inventory->purchase_price }}" type="number"
                                                            class="form-control" placeholder="Purchase Price"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Physical Properties -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Physical Properties</label>
                                                <div class="row g-2">
                                                    <div class="col-4"><input name="length[]"
                                                            value="{{ $inventory->length }}" type="number"
                                                            class="form-control" placeholder="Length (cm)"></div>
                                                    <div class="col-4"><input value="{{ $inventory->height }}"
                                                            name="height[]" type="number" class="form-control"
                                                            placeholder="Height (cm)"></div>
                                                    <div class="col-4"><input name="width[]"
                                                            value="{{ $inventory->width }}" type="number"
                                                            class="form-control" placeholder="Width (cm)"></div>
                                                    <div class="col-6"><input name="weight[]"
                                                            value="{{ $inventory->weight }}" type="number"
                                                            class="form-control" placeholder="Weight (kg)"></div>
                                                    <div class="col-6"><input name="volume[]"
                                                            value="{{ $inventory->volume }}" type="number"
                                                            class="form-control" placeholder="Volume (m³)"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Electrical Specs & Rental -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Electrical Specifications</label>
                                                <div class="row g-2">
                                                    <div class="col-6"><input value="{{ $inventory->current }}"
                                                            name="current[]" type="number" class="form-control"
                                                            placeholder="Current (A)"></div>
                                                    <div class="col-6"><input name="power[]"
                                                            value="{{ $inventory->power }}" type="number"
                                                            class="form-control" placeholder="Power (W)"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Rental Information</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" name="rental_information[]"
                                                        class="form-control" value="{{ $inventory->rental_information }}"
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
                    <script>
                        // Add Sub-Item
                        document.addEventListener('click', (e) => {
                            if (e.target.classList.contains('add-sub')) {
                                e.preventDefault();
                                const row = e.target.closest('tr');
                                const newRow = document.createElement('tr');
                                const level = row.classList.contains('main-item') ? 1 : 2;

                                newRow.innerHTML = `
        <td class="level-${level}">
          <input type="text" name="item_name[]" class="form-control form-control-sm" placeholder="Sub-item Name" required>
        </td>
        <td><input type="text" name="hsn_code[]" class="form-control form-control-sm"></td>
        <td>
          <select name="stock_category[]" class="form-select form-select-sm">
            <option value="electronics">Electronics</option>
            <option value="furniture">Furniture</option>
            <option value="machinery">Machinery</option>
          </select>
        </td>
        <td>
          <select name="unit[]" class="form-select form-select-sm">
            <option value="piece">Piece</option>
            <option value="set">Set</option>
            <option value="kg">Kg</option>
            <option value="liter">Liter</option>          
          </select>
        </td>
        <td><input type="number" name="worth[]" class="form-control form-control-sm"></td>
        <td>
          <select name="vendor[]" class="form-select form-select-sm vendor-select">
            <option value="">Select Vendor</option>
            <option value="vendor_a">Vendor A</option>
            <option value="vendor_b">Vendor B</option>
            <option value="vendor_c">Vendor C</option>
          </select>
        </td>
        <td>
          ${level < 2 ? '<button type="button" class="btn btn-sm btn-success add-sub">+ Sub</button>' : ''}
          <button type="button" id="#detailsModalButton" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal">More Details</button>
          <button type="button" class="btn btn-sm btn-danger delete">×</button>
        </td>
      `;
                                row.parentNode.insertBefore(newRow, row.nextSibling);
                            }
                        });

                        // Delete Row
                        document.addEventListener('click', (e) => {
                            if (e.target.classList.contains('delete')) {
                                e.target.closest('tr').remove();
                            }
                        });

                        // Toggle Sub-Items
                        document.addEventListener('click', (e) => {
                            if (e.target.classList.contains('toggle-icon')) {
                                const icon = e.target;
                                icon.textContent = icon.textContent === '▼' ? '▶' : '▼';
                                // Future: Add show/hide logic here
                            }
                        });
                    </script>
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
