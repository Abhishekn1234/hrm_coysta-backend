@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('lead'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('lead') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row">
            <div class="col-md-12" id="lead-btn">
                <button id="main-lead-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                    {{ \App\CPU\translate('add_lead') }}</button>

                @if ($user_type != 'CLIENT' && $user_type != 'PRODUCT_OWNER')
                    <a href="{{ route('admin.lead.bulk-import') }}"
                        class="btn btn-success">{{ \App\CPU\translate('Import leads') }}</a>
                @endif

                @if ($user_type == 'TEAM_LEAD' || $user_type == 'CEO')
                    <button id="main_lead_send" class="btn btn-danger">Whatsapp/Email</button>

                    <div class="row pt-4" id="lead_send"
                        style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Bulk Message
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
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

                @if ($user_type != 'CLIENT' && $user_type != 'PRODUCT_OWNER')
                    <form action="" method="POST">
                        <div class="col-md-12" style="margin-bottom:10px;margin-top:10px;">
                            <input type="text" class="form-control" id="multi_leads_id" hidden name="multi_leads_id">
                        </div>

                        <div class="" style="margin-bottom:10px;margin-top:10px;">
                            <a class="btn btn-danger btn-sm multi_DELETE" title="DELETE" style="cursor: pointer;">DELETE</a>
                            <a class="btn btn-success btn-sm multi_NEW" title="NEW" style="cursor: pointer;">NEW</a>
                            <a class="btn btn-info btn-sm multi_PROGRESS" title="PROGRESS"
                                style="cursor: pointer;">PROGRESS</a>
                            <a class="btn btn-danger btn-sm multi_CLOSED" title="CLOSED" style="cursor: pointer;">CLOSED</a>
                            <a class="btn btn-warning btn-sm multi_LOST" title="LOST"
                                style="cursor: pointer;color:white;">LOST</a>
                        </div>
                    </form>
                @endif


            </div>
        </div>

        <div class="row pt-4" id="main-lead"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('lead_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.lead.store') }}" method="post" enctype="multipart/form-data"
                            class="lead_form">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <style>
                                            .select2-container {
                                                width: 100% !important;
                                            }
                                        </style>

                                        <div class="form-group">
                                            <label for="assigned_user_id">{{ \App\CPU\translate('Assigned To') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="assigned_user_id" required>
                                                <option value="{{ null }}" selected disabled>Select Assigned To
                                                </option>
                                                @foreach ($staff_list as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_name">{{ \App\CPU\translate('lead_name') }}</label>
                                            <input placeholder="Enter lead_name" type="text" name="lead_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_email">{{ \App\CPU\translate('lead_email') }}</label>
                                            <input placeholder="Enter lead_email" type="email" name="lead_email"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_phone">{{ \App\CPU\translate('lead_phone') }}</label>
                                            <input placeholder="Enter lead_phone" type="text" name="lead_phone"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_sourse">{{ \App\CPU\translate('lead_sourse') }}</label>
                                            <input placeholder="Enter lead_sourse" type="text" name="lead_sourse"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lead_notes">{{ \App\CPU\translate('lead_notes') }}</label>
                                            <input placeholder="Enter lead_notes" type="text" name="lead_notes"
                                                class="form-control">
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

        <div class="row" style="margin-top: 20px" id="lead-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('lead_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $leads->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_lead') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <input id="" type="hidden" name="filter_staff_list"
                                            class="form-control" placeholder="{{ \App\CPU\translate('Search lead') }}"
                                            aria-label="Search orders" value="{{ $filter_staff_list }}">

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
                                onchange="location.href='{{ route('admin.lead.list') }}/?filter_staff_list='+this.value+''">
                                <option value="0" {{ $filter_staff_list == '' ? 'selected' : '' }}>Select Assigned To
                                </option>
                                @foreach ($staff_list as $b)
                                    <option value="{{ $b['id'] }}"
                                        {{ $filter_staff_list == $b['id'] ? 'selected' : '' }}>{{ $b['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <style>
                        ::-webkit-scrollbar {
                            height: 15px !important;
                        }
                    </style>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive" style="transform: rotateX(180deg);">
                            <table id="columnSearchDatatable" style="transform: rotateX(180deg);"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('sl') }}</th>

                                        <th style="text-align:center;">
                                            Select All
                                            <br>
                                            <input class="form-control-sm" style="width:20px;" type="checkbox"
                                                id="selectAll">
                                        </th>

                                        @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                            <th>CEO Approval</th>
                                        @endif
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th>Added By</th>
                                        <th>Assigned To</th>
                                        <th>Approval</th>
                                    </tr>
                                </thead>
                                @foreach ($leads as $key => $lead)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $leads->firstItem() + $key }}</td>
                                            <td style="text-align:center;">
                                                <input class="form-control-sm lead-checkbox" style="width:20px;"
                                                    type="checkbox" name="lead_checkbox[]" value="{{ $lead['id'] }}">
                                            </td>

                                            @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="ceo_approval"
                                                            id="{{ $lead->id }}" <?php if ($lead->ceo_approval == 1) {
                                                                echo 'checked';
                                                            } ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            @endif
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.lead.view', [$lead['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                @if ($user_id == $lead->user_id || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                                    <a class="btn btn-primary btn-sm edit"
                                                        title="{{ \App\CPU\translate('Edit') }}"
                                                        href="{{ route('admin.lead.edit', [$lead['id']]) }}"
                                                        style="cursor: pointer;">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete"
                                                        title="{{ \App\CPU\translate('Delete') }}"
                                                        style="cursor: pointer;" id="{{ $lead['id'] }}">
                                                        <i class="tio-add-to-trash"></i>
                                                    </a>
                                                @endif
                                            </td>

                                            <td>{{ $lead->lead_name }}</td>
                                            <td>{{ $lead->lead_email }}</td>
                                            <td>{{ $lead->lead_phone }}</td>
                                            <td>
                                                <?php if($user_type != 'PRODUCT_OWNER' && $user_type != 'CLIENT' && $lead['ceo_approval'] == 1){ ?>
                                                <!--<b><i id="ddd">{{ $lead->lead_status }}</i></b>-->

                                                <select style="width:150px;" id="{{ $lead->id }}" name="lead_status"
                                                    class="lead_status_change form-control" id="type_form"
                                                    value="{{ $lead['lead_status'] }}">
                                                    <option value="NEW"
                                                        {{ $lead['lead_status'] == 'NEW' ? 'selected' : '' }}>NEW</option>
                                                    <option value="PROGRESS"
                                                        {{ $lead['lead_status'] == 'PROGRESS' ? 'selected' : '' }}>PROGRESS
                                                    </option>
                                                    <option value="CLOSED"
                                                        {{ $lead['lead_status'] == 'CLOSED' ? 'selected' : '' }}>CLOSED
                                                    </option>
                                                    <option value="LOST"
                                                        {{ $lead['lead_status'] == 'LOST' ? 'selected' : '' }}>LOST</option>
                                                </select>
                                                <?php } else { ?>
                                                <b><i>{{ $lead->lead_status }}</i></b>
                                                <?php } ?>
                                            </td>
                                            <td>{{ $lead->lead_sourse }}</td>
                                            <td>
                                                <b><i>{{ \App\User::where(['id' => $lead->user_id])->first()->name }}</i></b>
                                            </td>
                                            <td>
                                                @if (\App\User::where(['id' => $lead->assigned_user_id])->first())
                                                    <b><i>{{ \App\User::where(['id' => $lead->assigned_user_id])->first()->name }}</i></b>
                                                @endif
                                            </td>

                                            <td>
                                                CEO :
                                                <?php if($lead['ceo_approval'] == 1){ ?>
                                                <b style="color:green;"><i>Approved</i></b>
                                                <?php } else { ?>
                                                <b style="color:red;"><i>Not Approved</i></b>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $leads->links() }}
                    </div>

                    @if (count($leads) == 0)
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
        function validateForm() {
            var checkboxes = document.querySelectorAll('input[name="lead_checkbox[]"]');
            var checked = Array.prototype.slice.call(checkboxes).some(x => x.checked);
            if (!checked) {
                alert('Please select at least one lead.');
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('input[name="lead_checkbox[]"]');
            const checkedLeadIdsTextBox = document.getElementById('multi_leads_id');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCheckboxes = document.querySelectorAll(
                        'input[name="lead_checkbox[]"]:checked');

                    const checkedIds = Array.from(checkedCheckboxes).map(function(checkbox) {
                        return checkbox.value;
                    });
                    checkedLeadIdsTextBox.value = checkedIds.join(',');
                });
            });
        });
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
        $(document).on('change', '.lead_status_change', function() {
            var id = $(this).attr("id");
            var lead_status = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.lead.lead_status_change') }}",
                method: 'POST',
                data: {
                    id: id,
                    lead_status: lead_status
                },
                success: function(data) {
                    $('#ddd').empty();
                    $('#ddd').text(data);
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('status_successfully') }}');
                    }
                    // location.reload();
                }
            });
        });

        $('#main-lead-add').on('click', function() {
            $('#main-lead').show();
        });

        $('.cancel').on('click', function() {
            $('.lead_form').attr('action', "{{ route('admin.lead.store') }}");
            $('#main-lead').hide();
        });

        $('#main_lead_send').on('click', function() {
            $('#lead_send').show();
        });

        $('.cancel_send').on('click', function() {
            $('#lead_send').hide();
        });

        $(document).on('click', '.multi_Whatsapp', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected leads ?",
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
                            url: "{{ route('admin.lead.send_whatsapp') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
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

                                $('#lead_send').hide();

                                // Swal.fire({
                                //     title: '{{ \App\CPU\translate('Message Sent') }}',
                                //     text: '{{ \App\CPU\translate('The messages are being sent to selected leads.') }}',
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
                var multi_leads_id = $('#multi_leads_id').val();
                var bulk_message = $('#bulk_message').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected leads ?",
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
                            url: "{{ route('admin.lead.send_email') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                bulk_message: bulk_message
                            },
                            success: function(data) {
                                $('#lead_send').hide();

                                Swal.fire({
                                    title: '{{ \App\CPU\translate('Email Sent') }}',
                                    text: '{{ \App\CPU\translate('The messages are being sent to selected leads.') }}',
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

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_lead') }}?",
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
                        url: "{{ route('admin.lead.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('lead_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.multi_DELETE', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();
                var type = "DELETE";
                Swal.fire({
                    title: "Are you sure to delete all the selected leads ?",
                    text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
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
                            url: "{{ route('admin.lead.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                type: type
                            },
                            success: function() {
                                toastr.success(
                                    '{{ \App\CPU\translate('leads_deleted_successfully') }}'
                                    );
                                location.reload();
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_NEW', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();
                var type = "NEW";
                Swal.fire({
                    title: "Are you sure to change status of all the selected leads as 'NEW'?",
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
                            url: "{{ route('admin.lead.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                type: type
                            },
                            success: function() {
                                toastr.success(
                                    '{{ \App\CPU\translate('successfully changed') }}');
                                location.reload();
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_PROGRESS', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();
                var type = "PROGRESS";
                Swal.fire({
                    title: "Are you sure to change status of all the selected leads as 'PROGRESS'?",
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
                            url: "{{ route('admin.lead.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                type: type
                            },
                            success: function() {
                                toastr.success(
                                    '{{ \App\CPU\translate('successfully changed') }}');
                                location.reload();
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_CLOSED', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();
                var type = "CLOSED";
                Swal.fire({
                    title: "Are you sure to change status of all the selected leads as 'CLOSED'?",
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
                            url: "{{ route('admin.lead.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                type: type
                            },
                            success: function() {
                                toastr.success(
                                    '{{ \App\CPU\translate('successfully changed') }}');
                                location.reload();
                            }
                        });
                    }
                })
            }
        });

        $(document).on('click', '.multi_LOST', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_leads_id = $('#multi_leads_id').val();
                var type = "LOST";
                Swal.fire({
                    title: "Are you sure to change status of all the selected leads as 'LOST'?",
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
                            url: "{{ route('admin.lead.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_leads_id: multi_leads_id,
                                type: type
                            },
                            success: function() {
                                toastr.success(
                                    '{{ \App\CPU\translate('successfully changed') }}');
                                location.reload();
                            }
                        });
                    }
                })
            }
        });

        $(document).on('change', '.ceo_approval', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var ceo_approval = 1;
            } else if ($(this).prop("checked") == false) {
                var ceo_approval = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.lead.ceo_approval') }}",
                method: 'POST',
                data: {
                    id: id,
                    ceo_approval: ceo_approval
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('ceo_approval_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('ceo_approval_successfully') }}');
                    }
                    // location.reload();
                }
            });
        });
    </script>

    <script>
        // Get the "Select All" checkbox
        const selectAllCheckbox = document.getElementById('selectAll');

        // Get all individual checkboxes with the class 'lead-checkbox'
        const leadCheckboxes = document.querySelectorAll('.lead-checkbox');

        // Get the hidden input field for storing selected lead IDs
        const multiLeadsIdInput = document.getElementById('multi_leads_id');

        // Event listener for the "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            // Set all checkboxes to the same state as the "Select All" checkbox
            leadCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update the multi_leads_id field with the selected lead IDs
            updateSelectedLeadIds(); // Make sure this is called when Select All is toggled
        });

        // Event listener for individual lead checkboxes
        leadCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Update the multi_leads_id field whenever any checkbox is clicked
                updateSelectedLeadIds();

                // Check if all checkboxes are selected or not
                selectAllCheckbox.checked = [...leadCheckboxes].every(checkbox => checkbox.checked);

                // Indeterminate state if some but not all are selected
                selectAllCheckbox.indeterminate = [...leadCheckboxes].some(checkbox => checkbox.checked) &&
                    !selectAllCheckbox.checked;
            });
        });

        // Function to update the hidden input with selected lead IDs
        function updateSelectedLeadIds() {
            // Get all checked checkboxes
            const checkedCheckboxes = document.querySelectorAll('input[name="lead_checkbox[]"]:checked');

            // Get the values (lead IDs) of all checked checkboxes
            const checkedIds = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

            // Join the IDs into a comma-separated string
            multiLeadsIdInput.value = checkedIds.join(','); // Update the input field
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
