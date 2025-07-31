@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Staff Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.staff.list') }}">
                                    {{ \App\CPU\translate('Staffs') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ \App\CPU\translate('Staff details') }}
                            </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{ \App\CPU\translate('Staff ID') }} #{{ $staff['id'] }}</h1>
                        <span class="{{ Session::get('direction') === 'rtl' ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3' }}">
                            <i class="tio-date-range"></i>
                            {{ \App\CPU\translate('Joined At') }} :
                            {{ date('d M Y H:i:s', strtotime($staff['created_at'])) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if ($staff)
                        <div class="card-body">
                            <div class="" href="javascript:">
                                <center>
                                    <img class="avatar-img"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        src="{{ asset('storage/app/public/banner/' . $staff->image) }}"
                                        alt="Image Description" style="height:250px;">
                                </center>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ \App\CPU\translate('Staff info') }}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>User Type : <b><i>{{ $staff['user_type'] }}</i></b></li>
                                <li>Join Type : <b><i>{{ $staff['join_type'] }}</i></b></li>
                                <li>Join Date : <b><i>{{ date('d M Y', strtotime($staff['join_date'])) }}</i></b></li>
                                <li>Name : <b><i>{{ $staff['name'] }}</i></b></li>
                                <li>Email : <b><i>{{ $staff['email'] }}</i></b></li>
                                <li>Phone : <b><i>{{ $staff['phone'] }}</i></b></li>
                                <li>Place : <b><i>{{ $staff['place'] }}</i></b></li>
                                <li>Address : <b><i>{!! $staff['address'] !!}</i></b></li>
                                <li>Gender : <b><i>{{ $staff['gender'] }}</i></b></li>

                                <li>Work Location : <b><i>{{ $staff['work_location'] }}</i></b></li>
                                <li>Employment Type : <b><i>{{ $staff['employment_type'] }}</i></b></li>

                                <li>Hourly Rate : <b><i>$ {{ $staff['hourly_rate'] }}</i></b></li>
                                <li>Monthly Rate : <b><i>$ {{ $staff['monthly_rate'] }}</i></b></li>

                                <li>Annual ctc : <b><i>{{ $staff['annual_ctc'] }}</i></b></li>
                                <li>Basic Salary : <b><i>{{ $staff['basic_salary'] }}</i></b></li>
                                <li>HRA : <b><i>{{ $staff['hra'] }}</i></b></li>
                                <li>Special Allowances : <b><i>{{ $staff['special_allowances'] }}</i></b></li>
                                <li>Probation Period : <b><i>{{ $staff['probation_period'] }}</i></b></li>

                                <li>DOB : <b><i>{{ date('d M Y', strtotime($staff['date_of_birth'])) }}</i></b></li>
                                <li>Age : <b><i>{{ now()->diffInYears($staff['date_of_birth']) }}</i></b></li>
                                <li>Qualification : <b><i>{{ $staff['qualification'] }}</i></b></li>
                                <li>Experience : <b><i>{{ $staff['experience'] }}</i></b></li>
                                <li>Expertise : <b><i>{{ $staff['expertise'] }}</i></b></li>

                                <li>Designation : <b><i>{{ $staff['designation'] }}</i></b></li>
                                <li>Role : <b><i>{{ $staff['role'] }}</i></b></li>
                                <li>Reports To : <b><i>{{ $staff['reports_to_name'] }}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="card">
                    @if ($staff)
                        <div class="card-body">
                            <div class="" href="javascript:">
                                <center>
                                    <img class="avatar-img"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        src="{{ asset('storage/app/public/banner/' . $staff->image) }}"
                                        alt="Image Description" style="height:250px;">
                                </center>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ \App\CPU\translate('Bank info') }}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Bank Name : <b><i>{{ $staff['bank_name'] }}</i></b></li>
                                <li>Account Holder Name : <b><i>{{ $staff['account_holder_name'] }}</i></b></li>
                                <li>Account Number : <b><i>{{ $staff['account_number'] }}</i></b></li>
                                <li>Ifsc Code : <b><i>{{ $staff['ifsc_code'] }}</i></b></li>
                                <li>Branch : <b><i>{{ $staff['branch'] }}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
