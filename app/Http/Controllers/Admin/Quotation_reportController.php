<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Quotation;
use App\Model\Proposal;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class Quotation_reportController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $search = $request['search'];
        $filter_status = $request['filter_status'];
        $filter_client = $request['filter_client'];
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN')->orderBy('id', 'ASC')->get();
        $proposal_list = Proposal::where(['status' => 'FINALIZED'])->orderBy('id', 'ASC')->get();
        
        if ($request->has('search')) {
            $quotation_reports = Quotation::where('status', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('quotation_number', 'like', "%{$search}%");
            });
        } else {
            $quotation_reports = Quotation::where('status', '!=' , '');
        }
        
        if ($request->has('filter_status') && $request['filter_status'] != '') {
            $quotation_reports = $quotation_reports->where(['status' => $request['filter_status']]);
        }
        
        if ($request->has('filter_client') && $request['filter_client'] != 0) {
            $quotation_reports = $quotation_reports->where(['client_id' => $request['filter_client']]);
        }
        
        $query_param = ['search' => $request['search'],'filter_status' => $request['filter_status'],'filter_client' => $request['filter_client']];
        
        $counts = $quotation_reports;
        $quotation_reports = $quotation_reports->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.quotation_report.view', compact('quotation_reports','search','filter_status','filter_client','client_list','proposal_list'));
    }
}