@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        th {
            font-size: 18px !important;
            font-weight: bolder !important;
        }

        td {
            font-size: 18px !important;
            font-weight: bolder !important;
        }

        .grid-card {
            border: 2px solid #00000012;
            border-radius: 10px;
            padding: 10px;
        }

        .label_1 {
            /*position: absolute;*/
            font-size: 10px;
            background: #370665;
            color: #ffffff;
            width: 80px;
            padding: 2px;
            font-weight: bold;
            border-radius: 6px;
            text-align: center;
        }

        .center-div {
            text-align: center;
            border-radius: 6px;
            padding: 6px;
            border: 2px solid #EEEEEE;
        }

        .icon-card {
            background-color: #22577A;
            border-width: 30px;
            border-style: solid;
            border-color: red;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header"
            style="padding-bottom: 0!important;border-bottom: 0!important;margin-bottom: 1.25rem!important;">
            <div class="flex-between align-items-center">
                <div>
                    <h1 class="page-header-title" style="">{{ \App\CPU\translate('Dashboard') }}</h1>
                    <p>{{ \App\CPU\translate('Welcome_message') }}.</p>
                </div>
            </div>
        </div>

        <?php $user_type = auth('customer')->user()->user_type; ?>

        @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'SCRUM_MASTER' || $user_type == 'HR')
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gx-2 gx-lg-3" id="order_stats">
                        <div class="col-sm-6 col-lg-6 mb-3 mb-lg-5">
                            <a class="card card-hover-shadow h-100" href="{{ route('admin.project.list') }}"
                                style="background: #FFFFFF">
                                <div class="card-body">
                                    <div class="flex-between align-items-center mb-1">
                                        <div style="">
                                            <h6 class="card-subtitle" style="color: #F14A16!important;">
                                                {{ \App\CPU\translate('total projects') }}</h6>
                                            <span class="card-title h2" style="color: #F14A16!important;">
                                                {{ $data['Project'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-6 mb-3 mb-lg-5">
                            <a class="card card-hover-shadow h-100" href="{{ route('admin.staff.list') }}"
                                style="background: #FFFFFF;">
                                <div class="card-body">
                                    <div class="flex-between align-items-center mb-1">
                                        <div style="">
                                            <h6 class="card-subtitle" style="color: #F14A16!important;">
                                                {{ \App\CPU\translate('total Staffs') }}</h6>
                                            <span class="card-title h2" style="color: #F14A16!important;">
                                                {{ $data['Staff'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        function printDiv(divId, filename = "Print") {
            var d = new Date();
            document.title = filename + "-(" + d.toLocaleString() + ")";
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    </script>
    <script src="{{ asset('assets/back-end') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
@endpush
