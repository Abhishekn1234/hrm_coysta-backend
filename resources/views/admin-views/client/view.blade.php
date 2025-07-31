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
                    <a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('client') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row">
            <div class="col-md-12" id="client-btn">
                <button id="main-client-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                    {{ \App\CPU\translate('add_client') }}</button>

                @if ($user_type == 'TEAM_LEAD' || $user_type == 'CEO')
                    <button id="main_client_send" class="btn btn-danger">Whatsapp/Email</button>

                    <div class="row pt-4" id="client_send"
                        style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Bulk Message
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" id="multi_clients_id" hidden
                                                name="multi_clients_id">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="bulk_message">{{ \App\CPU\translate('Message') }}</label>
                                            <textarea name="bulk_message" id="bulk_message" placeholder="Enter message" class="form-control" cols="30"
                                                rows="5" required></textarea>
                                        </div>

                                        <div class="card-footer">
                                            <a class="btn btn-success multi_Whatsapp" title="Whatsapp"
                                                style="cursor: pointer;">Whatsapp</a>
                                            <a class="btn btn-primary multi_Email" title="Email"
                                                style="cursor: pointer;">Email</a>
                                            <a
                                                class="btn btn-secondary text-white cancel_send">{{ \App\CPU\translate('Cancel') }}</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row pt-4" id="main-client"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('client_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.client.store') }}" method="post" enctype="multipart/form-data"
                            class="client_form">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('Image') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="image" id="mbimageFileUploader"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="mbimageFileUploader">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">{{ \App\CPU\translate('name') }}</label>
                                            <input placeholder="Enter name" type="text" name="name"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">{{ \App\CPU\translate('email') }}</label>
                                            <input placeholder="Enter email" type="email" name="email"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">{{ \App\CPU\translate('password') }}</label>
                                            <input placeholder="Enter password" type="password" name="password"
                                                class="form-control" autocomplete="new-password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">{{ \App\CPU\translate('phone') }}</label>
                                            <input placeholder="Enter phone" type="text" name="phone"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="place">{{ \App\CPU\translate('place') }}</label>
                                            <input placeholder="Enter place" type="text" name="place"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">{{ \App\CPU\translate('address') }}</label>
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10"
                                                required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">{{ \App\CPU\translate('gender') }}</label>
                                            <select name="gender" class="form-control" id="type_form" required>
                                                <option value="" selected disabled>Select gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="date_of_birth">{{ \App\CPU\translate('date_of_birth') }}</label>
                                            <input placeholder="Enter date_of_birth" type="date" name="date_of_birth"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification') }}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience') }}</label>
                                            <input placeholder="Enter experience" type="text" name="experience"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="expertise">{{ \App\CPU\translate('expertise') }}</label>
                                            <input placeholder="Enter expertise" type="text" name="expertise"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <center>
                                            <img style="width: auto;border: 1px solid; border-radius: 10px; max-width:400px;"
                                                id="mbImageviewer"
                                                src="{{ asset('public\assets\back-end\img\400x400\img1.jpg') }}"
                                                alt="banner image" />
                                        </center>
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

        <div class="row" style="margin-top: 20px" id="client-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('client_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $clients->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_client') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_gender" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search client') }}"
                                            aria-label="Search orders" value="{{ $filter_gender }}">

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
                                onchange="location.href='{{ route('admin.client.list') }}/?filter_gender='+this.value+''">
                                <option value="" {{ $filter_gender == '' ? 'selected' : '' }}>Select Gender</option>
                                <option value="Male" {{ $filter_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $filter_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Others" {{ $filter_gender == 'Others' ? 'selected' : '' }}>Others</option>
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
                                        <th style="text-align:center;">
                                            Select All
                                            <br>
                                            <input class="form-control-sm" style="width:20px;" type="checkbox"
                                                id="selectAll">
                                        </th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                @foreach ($clients as $key => $client)
                                    <tbody>
                                        <tr>
                                            <td>{{ $client->id }}</td>
                                            <td style="text-align:center;">
                                                <input class="form-control-sm client-checkbox" style="width:20px;"
                                                    type="checkbox" name="client_checkbox[]"
                                                    value="{{ $client['id'] }}">
                                            </td>
                                            <td>
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.client.view', [$client['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.client.edit', [$client['id']]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $client['id'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <img width="80"
                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/banner') }}/{{ $client['image'] }}">
                                            </td>
                                            <td>{{ $client->name }} <br>( {{ $client->user_type }} )</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->phone }}</td>

                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="status" id="{{ $client->id }}"
                                                        <?php if ($client->status == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>


                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $clients->links() }}
                    </div>

                    @if (count($clients) == 0)
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
        $('#main-client-add').on('click', function() {
            $('#main-client').show();
        });

        $('.cancel').on('click', function() {
            $('.client_form').attr('action', "{{ route('admin.client.store') }}");
            $('#main-client').hide();
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
                url: "{{ route('admin.client.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('client_active_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('client_inactive_successfully') }}');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_client') }}?",
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
                        url: "{{ route('admin.client.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('client_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $('#main_client_send').on('click', function() {
            $('#client_send').show();
        });

        $('.client_send').on('click', function() {
            $('#client_send').hide();
        });

        $(document).on('click', '.multi_Whatsapp', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_clients_id = $('#multi_clients_id').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected clients ?",
                    // text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Proceed') }}!',
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
                            url: "{{ route('admin.client.send_whatsapp') }}",
                            method: 'POST',
                            data: {
                                multi_clients_id: multi_clients_id,
                            },
                            success: function(data) {

                                var bulk_message = $('#bulk_message').val();

                                const numbers = data
                                .numbers; // PHP numbers array passed to JavaScript
                                const names = data
                                .names; // PHP numbers array passed to JavaScript
                                const message =
                                bulk_message; // PHP message string passed to JavaScript
                                const delay = 5000; // Delay between sending messages


                                const sendMessage = async (number, message) => {
                                    const url =
                                        `https://web.whatsapp.com/send?phone=${number}&text=${encodeURIComponent(message)}`;
                                    const newWindow = window.open(url, '_blank');

                                    return new Promise((resolve, reject) => {
                                        if (!newWindow) {
                                            reject(new Error(
                                                'Popup blocked, please allow popups.'
                                                ));
                                            return;
                                        }

                                        const waitForSendButton = setInterval(
                                        () => {
                                                // const sendButton = document.querySelector('[data-tab="11"]');
                                                const sendButton = newWindow
                                                    .document.querySelector(
                                                        '[data-tab="11"]'
                                                        ); // Correctly target the new window's document

                                                if (sendButton) {
                                                    sendButton.click();
                                                    clearInterval(
                                                        waitForSendButton
                                                        );
                                                    resolve();
                                                }
                                            }, 1000);

                                        setTimeout(() => {
                                            clearInterval(
                                                waitForSendButton);
                                            reject(new Error(
                                                'Timed out waiting for send button.'
                                                ));
                                        }, 5000);
                                    });
                                };

                                (async () => {
                                    // Loop through each number in the numbers array
                                    for (let i = 0; i < numbers.length; i++) {
                                        try {
                                            const number = numbers[
                                            i]; // Get the current number from JavaScript array
                                            const name = names[i];
                                            // const personalizedMessage = `Hi Mr. ${name}, <br> ${message}`;
                                            const personalizedMessage =
                                                `Hi Mr. ${name},\n${message}`;

                                            await sendMessage(number,
                                                personalizedMessage
                                                ); // Send message to each number
                                            console.log(`Message sent to ${number}`);
                                        } catch (error) {
                                            console.error(
                                                `Failed to send message to ${numbers[i]}:`,
                                                error);
                                        }
                                        await new Promise(res => setTimeout(res,
                                        delay)); // Wait before sending the next message
                                    }
                                })();

                                $('#client_send').hide();

                                // Swal.fire({
                                //     title: '{{ \App\CPU\translate('Message Sent') }}',
                                //     text: '{{ \App\CPU\translate('The messages are being sent to selected client.') }}',
                                //     icon: 'success',
                                //     confirmButtonColor: '#3085d6',
                                //     confirmButtonText: '{{ \App\CPU\translate('OK') }}'
                                // });
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_Email', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_clients_id = $('#multi_clients_id').val();
                var bulk_message = $('#bulk_message').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected clients ?",
                    // text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('Proceed') }}!',
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
                            url: "{{ route('admin.client.send_email') }}",
                            method: 'POST',
                            data: {
                                multi_clients_id: multi_clients_id,
                                bulk_message: bulk_message
                            },
                            success: function(data) {
                                $('#client_send').hide();

                                Swal.fire({
                                    title: '{{ \App\CPU\translate('Email Sent') }}',
                                    text: '{{ \App\CPU\translate('The messages are being sent to selected clients.') }}',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: '{{ \App\CPU\translate('OK') }}'
                                });
                            }
                        });
                    }
                })
            }
        });
    </script>

    <script>
        function validateForm() {
            var checkboxes = document.querySelectorAll('input[name="client_checkbox[]"]');
            var checked = Array.prototype.slice.call(checkboxes).some(x => x.checked);
            if (!checked) {
                alert('Please select at least one client.');
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('input[name="client_checkbox[]"]');
            const checkedClientIdsTextBox = document.getElementById('multi_clients_id');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCheckboxes = document.querySelectorAll(
                        'input[name="client_checkbox[]"]:checked');

                    const checkedIds = Array.from(checkedCheckboxes).map(function(checkbox) {
                        return checkbox.value;
                    });
                    checkedClientIdsTextBox.value = checkedIds.join(',');
                });
            });
        });
    </script>

    <script>
        // Get the "Select All" checkbox
        const selectAllCheckbox = document.getElementById('selectAll');

        // Get all individual checkboxes with the class 'client-checkbox'
        const clientCheckboxes = document.querySelectorAll('.client-checkbox');

        // Get the hidden input field for storing selected client IDs
        const multiClientsIdInput = document.getElementById('multi_clients_id');

        // Event listener for the "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            // Set all checkboxes to the same state as the "Select All" checkbox
            clientCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update the client field with the selected client IDs
            updateSelectedClientIds(); // Make sure this is called when Select All is toggled
        });

        // Event listener for individual client checkboxes
        clientCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Update the multi_clients_id field whenever any checkbox is clicked
                updateSelectedClientIds();

                // Check if all checkboxes are selected or not
                selectAllCheckbox.checked = [...clientCheckboxes].every(checkbox => checkbox.checked);

                // Indeterminate state if some but not all are selected
                selectAllCheckbox.indeterminate = [...clientCheckboxes].some(checkbox => checkbox
                    .checked) && !selectAllCheckbox.checked;
            });
        });

        // Function to update the hidden input with selected client IDs
        function updateSelectedClientIds() {
            // Get all checked checkboxes
            const checkedCheckboxes = document.querySelectorAll('input[name="client_checkbox[]"]:checked');

            // Get the values (client IDs) of all checked checkboxes
            const checkedIds = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

            // Join the IDs into a comma-separated string
            multiClientsIdInput.value = checkedIds.join(','); // Update the input field
        }
    </script>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
