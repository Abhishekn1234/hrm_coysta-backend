@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('candidate'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('candidate') }}</li>
            </ol>
        </nav>

        <?php $user_type = auth('customer')->user()->user_type; ?>
        <?php $user_id = auth('customer')->user()->id; ?>

        <div class="row">
            <div class="col-md-12" id="candidate-btn">
                <button id="main-candidate-add" class="btn btn-primary"><i class="tio-add-circle"></i>
                    {{ \App\CPU\translate('add_candidate') }}</button>
                @if ($user_type != 'CLIENT')
                    <a href="{{ route('admin.candidate.bulk-import') }}"
                        class="btn btn-success">{{ \App\CPU\translate('Import candidates') }}</a>
                @endif

                @if ($user_type == 'TEAM_LEAD' || $user_type == 'CEO')
                    <button id="main_candidate_send" class="btn btn-danger">Whatsapp/Email</button>

                    <div class="row pt-4" id="candidate_send"
                        style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Bulk Message
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" id="multi_candidates_id" hidden
                                                name="multi_candidates_id">
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

        <div class="row pt-4" id="main-candidate"
            style="display: none;text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('candidate_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.candidate.store') }}" method="post" enctype="multipart/form-data"
                            class="candidate_form">
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
                                            <textarea name="address" placeholder="Enter address" class="editor textarea" cols="30" rows="10" required></textarea>
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
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="qualification">{{ \App\CPU\translate('qualification') }}</label>
                                            <input placeholder="Enter qualification" type="text" name="qualification"
                                                class="form-control" required>
                                        </div>




                                        <div class="form-group">
                                            <label for="job_id">{{ \App\CPU\translate('Job') }}</label>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="job_id" required>
                                                <option value="{{ null }}" selected disabled>Select Job</option>
                                                @foreach ($job as $b)
                                                    <option value="{{ $b['id'] }}">{{ $b['job_title'] }} (
                                                        {{ $b['job_type'] }} )</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="position">{{ \App\CPU\translate('position') }}</label>
                                            <input placeholder="Enter position" type="text" name="position"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="experience">{{ \App\CPU\translate('experience') }}</label>
                                            <input placeholder="Enter experience" type="text" name="experience"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="skills">{{ \App\CPU\translate('skills') }}</label>
                                            <input placeholder="Enter skills" type="text" name="skills"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="tenth_mark_percentage">10th mark (%)</label>
                                            <input placeholder="Enter tenth_mark_percentage" type="number"
                                                name="tenth_mark_percentage" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="twelveth_mark_percentage">12th mark (%)</label>
                                            <input placeholder="Enter twelveth_mark_percentage" type="number"
                                                name="twelveth_mark_percentage" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="degree_mark_percentage">Degree mark (%)</label>
                                            <input placeholder="Enter degree_mark_percentage" type="number"
                                                name="degree_mark_percentage" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="portfolio_link">{{ \App\CPU\translate('Portfolio Link') }}</label>
                                            <input placeholder="Enter portfolio_link" type="text"
                                                name="portfolio_link" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label
                                                for="last_qualification_certificate">{{ \App\CPU\translate('Last Qualification Certificate') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="last_qualification_certificate"
                                                    class="custom-file-input" accept=".pdf">
                                                <label class="custom-file-label">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="resume">{{ \App\CPU\translate('resume') }}</label>
                                            <div class="custom-file" style="text-align: left">
                                                <input type="file" name="resume" id="mbimageFileUploader"
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

        <div class="row" style="margin-top: 20px" id="candidate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('candidate_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $candidates->total() }})</h5>
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
                                            placeholder="{{ \App\CPU\translate('Search_by_candidate') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>

                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                    </div>
                                </form>
                            </div>
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

                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Job</th>
                                        <th>Position</th>
                                        <th>Resume</th>
                                        <th>Personality Test</th>
                                        <th>Accademics</th>
                                        <th>Interviews</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($candidates as $key => $candidate)
                                    <tbody id="tbd-{{ $key }}">
                                        <tr>
                                            <td>{{ $candidates->firstItem() + $key }}</td>
                                            <td style="text-align:center;">
                                                <input class="form-control-sm candidate-checkbox" style="width:20px;"
                                                    type="checkbox" name="candidate_checkbox[]"
                                                    value="{{ $candidate['id'] }}">
                                            </td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.candidate.view', [$candidate['id']]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.candidate.edit', [$candidate['id']]) }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $candidate['id'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>

                                                <br>

                                                <?php if($candidate['is_staff'] == 0) { ?>
                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Interview') }}"
                                                    class="btn btn-success btn-sm"
                                                    href="{{ route('admin.candidate.add_interview', [$candidate['id']]) }}">
                                                    Interview
                                                </a>
                                                <?php }  ?>

                                                <?php if($candidate['is_staff'] == 0) { ?>
                                                <br>
                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Interview') }}"
                                                    class="btn btn-info btn-sm"
                                                    href="{{ route('admin.candidate.staff_convert', [$candidate['id']]) }}">
                                                    Convert to Staff
                                                </a>
                                                <?php }  ?>
                                            </td>

                                            <td>{{ $candidate->name }}</td>
                                            <td>{{ $candidate->email }}</td>
                                            <td>{{ $candidate->phone }}</td>

                                            <td>
                                                @if (\App\Model\Job::where(['id' => $candidate->job_id])->first())
                                                    <b><i>{{ \App\Model\Job::where(['id' => $candidate->job_id])->first()->job_title }}
                                                            ({{ \App\Model\Job::where(['id' => $candidate->job_id])->first()->job_type }})</i></b>
                                                @endif
                                            </td>

                                            <td>{{ $candidate->position }}</td>
                                            <td>
                                                <?php if($candidate['resume'] != '') { ?>
                                                <a target="_blank" class="btn btn-primary btn-sm edit"
                                                    href="{{ asset('storage/app/public/banner') }}/{{ $candidate['resume'] }}">Resume</a>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <?php if($candidate['is_test_done'] == 1) { ?>
                                                Type : <b><i>{{ $candidate['result_typeindex'] }} -
                                                        {{ $candidate['result_type'] }}</i></b><br>
                                                Score : <b><i>{{ $candidate['result_highestScore'] }}</i></b>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <?php
                                                $weight10th = 20; // 20% for 10th
                                                $weight12th = 30; // 30% for 12th
                                                $weightDegree = 50; // 50% for Degree
                                                
                                                $score = ($candidate['tenth_mark_percentage'] * $weight10th) / 100 + ($candidate['twelveth_mark_percentage'] * $weight12th) / 100 + ($candidate['degree_mark_percentage'] * $weightDegree) / 100;
                                                ?>
                                                <b><i>{{ $score != 0 ? $score . '%' : '' }}</i></b>
                                            </td>

                                            <td>
                                                <?php $interviews_count = DB::table('interviews')
                                                    ->where(['candidate_id' => $candidate['id']])
                                                    ->orderBy('id', 'DESC')
                                                    ->count(); ?>
                                                <?php $interviews_score = DB::table('interview_marks')
                                                    ->where(['candidate_id' => $candidate['id']])
                                                    ->sum('marks'); ?>

                                                Count : <b><i>{{ $interviews_count }}</i></b><br>
                                                Avg Score :
                                                <b><i>{{ $interviews_score / ($interviews_count > 0 ? $interviews_count : 1) }}</i></b>
                                            </td>

                                            <td>
                                                <?php if($candidate['is_staff'] == 1) { ?>
                                                <b><i>Hired</i></b>
                                                <?php } else { ?>
                                                <b><i>Processing</i></b>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $candidates->links() }}
                    </div>

                    @if (count($candidates) == 0)
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
        $('#main-candidate-add').on('click', function() {
            $('#main-candidate').show();
        });

        $('.cancel').on('click', function() {
            $('.candidate_form').attr('action', "{{ route('admin.candidate.store') }}");
            $('#main-candidate').hide();
        });

        $('#main_candidate_send').on('click', function() {
            $('#candidate_send').show();
        });

        $('.cancel_send').on('click', function() {
            $('#candidate_send').hide();
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_candidate') }}?",
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
                        url: "{{ route('admin.candidate.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('candidate_deleted_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', '.multi_Whatsapp', function() {
            var valid = validateForm();
            if (valid == true) {
                var multi_candidates_id = $('#multi_candidates_id').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected candidates ?",
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
                            url: "{{ route('admin.candidate.processAction') }}",
                            method: 'POST',
                            data: {
                                multi_candidates_id: multi_candidates_id,
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

                                $('#candidate_send').hide();

                                // Swal.fire({
                                //     title: '{{ \App\CPU\translate('Message Sent') }}',
                                //     text: '{{ \App\CPU\translate('The messages are being sent to selected candidates.') }}',
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
                var multi_candidates_id = $('#multi_candidates_id').val();
                var bulk_message = $('#bulk_message').val();

                Swal.fire({
                    title: "Are you sure to sent messages to selected candidates ?",
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
                            url: "{{ route('admin.candidate.send_email') }}",
                            method: 'POST',
                            data: {
                                multi_candidates_id: multi_candidates_id,
                                bulk_message: bulk_message
                            },
                            success: function(data) {
                                $('#candidate_send').hide();

                                Swal.fire({
                                    title: '{{ \App\CPU\translate('Email Sent') }}',
                                    text: '{{ \App\CPU\translate('The messages are being sent to selected candidates.') }}',
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
            var checkboxes = document.querySelectorAll('input[name="candidate_checkbox[]"]');
            var checked = Array.prototype.slice.call(checkboxes).some(x => x.checked);
            if (!checked) {
                alert('Please select at least one candidate.');
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('input[name="candidate_checkbox[]"]');
            const checkedCandidateIdsTextBox = document.getElementById('multi_candidates_id');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCheckboxes = document.querySelectorAll(
                        'input[name="candidate_checkbox[]"]:checked');

                    const checkedIds = Array.from(checkedCheckboxes).map(function(checkbox) {
                        return checkbox.value;
                    });
                    checkedCandidateIdsTextBox.value = checkedIds.join(',');
                });
            });
        });
    </script>

    <script>
        // Get the "Select All" checkbox
        const selectAllCheckbox = document.getElementById('selectAll');

        // Get all individual checkboxes with the class 'candidate-checkbox'
        const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');

        // Get the hidden input field for storing selected candidate IDs
        const multiCandidatesIdInput = document.getElementById('multi_candidates_id');

        // Event listener for the "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            // Set all checkboxes to the same state as the "Select All" checkbox
            candidateCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update the multi_candidates_id field with the selected candidate IDs
            updateSelectedCandidateIds(); // Make sure this is called when Select All is toggled
        });

        // Event listener for individual candidate checkboxes
        candidateCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Update the multi_candidates_id field whenever any checkbox is clicked
                updateSelectedCandidateIds();

                // Check if all checkboxes are selected or not
                selectAllCheckbox.checked = [...candidateCheckboxes].every(checkbox => checkbox.checked);

                // Indeterminate state if some but not all are selected
                selectAllCheckbox.indeterminate = [...candidateCheckboxes].some(checkbox => checkbox
                    .checked) && !selectAllCheckbox.checked;
            });
        });

        // Function to update the hidden input with selected candidate IDs
        function updateSelectedCandidateIds() {
            // Get all checked checkboxes
            const checkedCheckboxes = document.querySelectorAll('input[name="candidate_checkbox[]"]:checked');

            // Get the values (candidate IDs) of all checked checkboxes
            const checkedIds = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

            // Join the IDs into a comma-separated string
            multiCandidatesIdInput.value = checkedIds.join(','); // Update the input field
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
