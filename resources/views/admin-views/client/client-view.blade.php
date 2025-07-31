@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('client Details'))

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.client.list') }}">
                                    {{ \App\CPU\translate('clients') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ \App\CPU\translate('client details') }}
                            </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{ \App\CPU\translate('client ID') }} #{{ $client['id'] }}</h1>
                        <span class="{{ Session::get('direction') === 'rtl' ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3' }}">
                            <i class="tio-date-range"></i>
                            {{ \App\CPU\translate('Joined At') }} :
                            {{ date('d M Y H:i:s', strtotime($client['created_at'])) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-12">
                <div class="card">
                    @if ($client)
                        <div class="card-body">
                            <div class="" href="javascript:">
                                <center>
                                    <img class="avatar-img"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        src="{{ asset('storage/app/public/banner/' . $client->image) }}"
                                        alt="Image Description" style="height:250px;">
                                </center>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ \App\CPU\translate('client info') }}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>Name : <b><i>{{ $client['name'] }}</i></b></li>
                                <li>Email : <b><i>{{ $client['email'] }}</i></b></li>
                                <li>Phone : <b><i>{{ $client['phone'] }}</i></b></li>
                                <li>Place : <b><i>{{ $client['place'] }}</i></b></li>
                                <li>Address : <b><i>{!! $client['address'] !!}</i></b></li>
                                <li>Gender : <b><i>{{ $client['gender'] }}</i></b></li>
                                <li>DOB : <b><i>{{ date('d M Y', strtotime($client['date_of_birth'])) }}</i></b></li>
                                <li>Age : <b><i>{{ now()->diffInYears($client['date_of_birth']) }}</i></b></li>
                                <li>Qualification : <b><i>{{ $client['qualification'] }}</i></b></li>
                                <li>Experience : <b><i>{{ $client['experience'] }}</i></b></li>
                                <li>Expertise : <b><i>{{ $client['expertise'] }}</i></b></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
