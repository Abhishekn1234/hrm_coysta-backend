<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Invoice;
use App\Model\Quotation;
use App\Model\Proposal;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class Invoice_reportController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;

        $query_param = [];
        $search = $request['search'];
        $filter_status = $request['filter_status'];
        $filter_client = $request['filter_client'];
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN');
        
        if($user_type != 'CEO'){
            $client_list = $client_list->where('id', $user_id);
        }
        
        $client_list = $client_list->orderBy('id', 'ASC')->get();
        
        $quotation_list = Quotation::where(['status' => 'ACCEPTED'])->orderBy('id', 'ASC')->get();
        
        if ($request->has('search')) {
            $invoice_reports = Invoice::where('status', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('invoice_number', 'like', "%{$search}%");
            });
        } else {
            $invoice_reports = Invoice::where('status', '!=' , '');
        }
        
        if ($request->has('filter_status') && $request['filter_status'] != '') {
            $invoice_reports = $invoice_reports->where(['status' => $request['filter_status']]);
        }
        
        if ($request->has('filter_client') && $request['filter_client'] != 0) {
            $invoice_reports = $invoice_reports->where(['client_id' => $request['filter_client']]);
        }
        
        if($user_type == 'CLIENT'){
            $invoice_reports = $invoice_reports->where('client_id', $user_id);
        }
        
        $query_param = ['search' => $request['search'],'filter_status' => $request['filter_status'],'filter_client' => $request['filter_client']];
        
        $counts = $invoice_reports;
        $invoice_reports = $invoice_reports->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.invoice_report.view', compact('invoice_reports','search','filter_status','filter_client','client_list','quotation_list'));
    }

    public function add_receipts($invoice_report_id)
    {
        $receipt_number = "RC-" . (DB::table('receipts')->orderBy('id', 'DESC')->first() ? DB::table('receipts')->orderBy('id', 'DESC')->first()->id : 0) . "-" . rand(1000,9999);
        $receipts = DB::table('receipts')->where(['invoice_id' => $invoice_report_id])->orderBy('id', 'DESC')->get();
        $invoice_report = Invoice::where('id', $invoice_report_id)->first();
        
        $items_total = DB::table('quotation_items')->where(['quotation_id' => $invoice_report->quotation_id])->sum('total');
        
        return view('admin-views.invoice_report.add_receipts', compact('invoice_report', 'receipts','receipt_number','items_total'));
    }
}