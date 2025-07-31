@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('interview'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('interview') }}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px" id="interview-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('interview_table') }}</h5>
                                </div>
                                <div class="mx-1">
                                    <h5 style="color: red;">({{ $interviews->total() }})</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $user_id = auth('customer')->user()->id; ?>

                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th style="width: 50px" class="action_div">{{ \App\CPU\translate('action') }}</th>
                                        <th>Candidate</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Interviewers</th>
                                        <th>Link</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($interviews as $key => $interview)
                                    <tbody>
                                        <tr>
                                            <td>{{ $interview->id }}</td>
                                            <td style="text-align:center;">
                                                <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                    href="{{ route('admin.interview.view', [$interview->id]) }}">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                <br>

                                                <a style="margin-top:10px;color:white;width:100%;"
                                                    title="{{ \App\CPU\translate('Marks') }}"
                                                    class="btn btn-warning btn-sm"
                                                    href="{{ route('admin.interview.add_marks', [$interview->id]) }}">
                                                    Marks
                                                </a>
                                            </td>
                                            <td>
                                                @if (\App\Model\Candidate::where(['id' => $interview->candidate_id])->first())
                                                    <b><i>{{ \App\Model\Candidate::where(['id' => $interview->candidate_id])->first()->name }}</i></b>
                                                @endif
                                            </td>
                                            <td>
                                                @if (\App\Model\Candidate::where(['id' => $interview->candidate_id])->first())
                                                    <b><i>{{ \App\Model\Candidate::where(['id' => $interview->candidate_id])->first()->phone }}</i></b>
                                                @endif
                                            </td>
                                            <td>{{ date('d M Y', strtotime($interview->interview_date)) }}</td>
                                            <td>{{ date('h:i:s A', strtotime($interview->interview_time)) }}</td>
                                            <td>
                                                <ul>
                                                    @foreach (json_decode($interview->interviewer_ids) as $key => $a)
                                                        <li><b><i>{{ \App\User::where(['status' => '1', 'id' => $a])->first()->name }}
                                                                    ({{ \App\User::where(['status' => '1', 'id' => $a])->first()->user_type }})</i></b>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <?php if($interview->google_meet_link != "" && $interview->interview_status == "SCHEDULED") { ?>
                                                <a title="{{ \App\CPU\translate('link') }}" class="btn btn-primary btn-sm"
                                                    target="_blank" href="{{ $interview->google_meet_link }}">
                                                    Link
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td><b><i>{{ $interview->interview_status }}</i></b></td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $interviews->links() }}
                    </div>

                    @if (count($interviews) == 0)
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

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
@endpush
