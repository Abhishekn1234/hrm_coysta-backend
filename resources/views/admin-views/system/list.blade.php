@extends('layouts.back-end.app')

@section('title', 'Daily Report')

@section('content')
    <div class="content container-fluid ">
        <div class="col-md-4" style="margin-bottom: 20px;">
            <h3 class="text-capitalize">Daily Report</h3>
        </div>
        
        <div class="card">
            <div class="card-body" style="padding: 0">
                <div class="">
                    <table id="datatable" style="text-align:left" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="5" style="text-align:center"><h2>{{date('d M, Y h:i:s',strtotime($data['currentTime']))}}</h2></th>
                            </tr>
                        </thead>
                        <thead class="thead-light">
                            <tr>
                                <th><h2><b>District</b></h2></th>
                                <th><h2>Minus Balance</h2></th>
                                <th><h2>Zero Balance</h2></th>
                                <th><h2>Below 500</h2></th>
                                <th><h2>Above 500</h2></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><h2><b>Alappuzha</b></h2></td>
                                <td><h2>{{$data['Alappuzha_minus']}}</h2></td>
                                <td><h2>{{$data['Alappuzha_zero']}}</h2></td>
                                <td><h2>{{$data['Alappuzha_below']}}</h2></td>
                                <td><h2>{{$data['Alappuzha_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Ernakulam</b></h2></td>
                                <td><h2>{{$data['Ernakulam_minus']}}</h2></td>
                                <td><h2>{{$data['Ernakulam_zero']}}</h2></td>
                                <td><h2>{{$data['Ernakulam_below']}}</h2></td>
                                <td><h2>{{$data['Ernakulam_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Idukki</b></h2></td>
                                <td><h2>{{$data['Idukki_minus']}}</h2></td>
                                <td><h2>{{$data['Idukki_zero']}}</h2></td>
                                <td><h2>{{$data['Idukki_below']}}</h2></td>
                                <td><h2>{{$data['Idukki_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Kannur</b></h2></td>
                                <td><h2>{{$data['Kannur_minus']}}</h2></td>
                                <td><h2>{{$data['Kannur_zero']}}</h2></td>
                                <td><h2>{{$data['Kannur_below']}}</h2></td>
                                <td><h2>{{$data['Kannur_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Kasaragod</b></h2></td>
                                <td><h2>{{$data['Kasaragod_minus']}}</h2></td>
                                <td><h2>{{$data['Kasaragod_zero']}}</h2></td>
                                <td><h2>{{$data['Kasaragod_below']}}</h2></td>
                                <td><h2>{{$data['Kasaragod_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Kollam</b></h2></td>
                                <td><h2>{{$data['Kollam_minus']}}</h2></td>
                                <td><h2>{{$data['Kollam_zero']}}</h2></td>
                                <td><h2>{{$data['Kollam_below']}}</h2></td>
                                <td><h2>{{$data['Kollam_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Kottayam</b></h2></td>
                                <td><h2>{{$data['Kottayam_minus']}}</h2></td>
                                <td><h2>{{$data['Kottayam_zero']}}</h2></td>
                                <td><h2>{{$data['Kottayam_below']}}</h2></td>
                                <td><h2>{{$data['Kottayam_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Kozhikode</b></h2></td>
                                <td><h2>{{$data['Kozhikode_minus']}}</h2></td>
                                <td><h2>{{$data['Kozhikode_zero']}}</h2></td>
                                <td><h2>{{$data['Kozhikode_below']}}</h2></td>
                                <td><h2>{{$data['Kozhikode_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Malappuram</b></h2></td>
                                <td><h2>{{$data['Malappuram_minus']}}</h2></td>
                                <td><h2>{{$data['Malappuram_zero']}}</h2></td>
                                <td><h2>{{$data['Malappuram_below']}}</h2></td>
                                <td><h2>{{$data['Malappuram_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Palakkad</b></h2></td>
                                <td><h2>{{$data['Palakkad_minus']}}</h2></td>
                                <td><h2>{{$data['Palakkad_zero']}}</h2></td>
                                <td><h2>{{$data['Palakkad_below']}}</h2></td>
                                <td><h2>{{$data['Palakkad_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Pathanamthitta</b></h2></td>
                                <td><h2>{{$data['Pathanamthitta_minus']}}</h2></td>
                                <td><h2>{{$data['Pathanamthitta_zero']}}</h2></td>
                                <td><h2>{{$data['Pathanamthitta_below']}}</h2></td>
                                <td><h2>{{$data['Pathanamthitta_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Thiruvananthapuram</b></h2></td>
                                <td><h2>{{$data['Thiruvananthapuram_minus']}}</h2></td>
                                <td><h2>{{$data['Thiruvananthapuram_zero']}}</h2></td>
                                <td><h2>{{$data['Thiruvananthapuram_below']}}</h2></td>
                                <td><h2>{{$data['Thiruvananthapuram_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Thrissur</b></h2></td>
                                <td><h2>{{$data['Thrissur_minus']}}</h2></td>
                                <td><h2>{{$data['Thrissur_zero']}}</h2></td>
                                <td><h2>{{$data['Thrissur_below']}}</h2></td>
                                <td><h2>{{$data['Thrissur_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Wayanad</b></h2></td>
                                <td><h2>{{$data['Wayanad_minus']}}</h2></td>
                                <td><h2>{{$data['Wayanad_zero']}}</h2></td>
                                <td><h2>{{$data['Wayanad_below']}}</h2></td>
                                <td><h2>{{$data['Wayanad_above']}}</h2></td>
                            </tr>
                            
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td><h2><b>Total</b></td>
                                <td><h2><b>{{$data['Total_minus']}}</b></td>
                                <td><h2><b>{{$data['Total_zero']}}</b></td>
                                <td><h2><b>{{$data['Total_below']}}</b></td>
                                <td><h2><b>{{$data['Total_above']}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection