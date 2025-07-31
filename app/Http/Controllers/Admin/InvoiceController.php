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

class InvoiceController extends Controller
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
        
        $invoice_number = "INV-" . (Invoice::orderBy('id', 'DESC')->first() ? Invoice::orderBy('id', 'DESC')->first()->id : 0) . "-" . rand(1000,9999);
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN');
        
        if($user_type != 'CEO'){
            $client_list = $client_list->where('id', $user_id);
        }
        
        $client_list = $client_list->orderBy('id', 'ASC')->get();
        
        $quotation_list = Quotation::where(['status' => 'ACCEPTED'])->orderBy('id', 'ASC')->get();
        
        if ($request->has('search')) {
            $invoices = Invoice::where('status', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('invoice_number', 'like', "%{$search}%");
            });
        } else {
            $invoices = Invoice::where('status', '!=' , '');
        }
        
        if ($request->has('filter_status') && $request['filter_status'] != '') {
            $invoices = $invoices->where(['status' => $request['filter_status']]);
        }
        
        if ($request->has('filter_client') && $request['filter_client'] != 0) {
            $invoices = $invoices->where(['client_id' => $request['filter_client']]);
        }
        
        if($user_type == 'CLIENT'){
            $invoices = $invoices->where('client_id', $user_id);
        }
        
        $query_param = ['search' => $request['search'],'filter_status' => $request['filter_status'],'filter_client' => $request['filter_client']];
        
        $counts = $invoices;
        $invoices = $invoices->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.invoice.view', compact('invoices','search','filter_status','filter_client','client_list','quotation_list','invoice_number'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'client_id' => 'required',
            'quotation_id' => 'required',
            'invoice_number' => 'required',
            'start_date' => 'required',
            'renewal_date' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'quotation_id.required' => 'quotation_id is required!',
            'invoice_number.required' => 'invoice_number is required!',
            'start_date.required' => 'start_date is required!',
            'renewal_date.required' => 'renewal_date is required!',
        ]);
        
        $quotation = Quotation::where(['id' => $request->quotation_id])->first();

        $invoice = new Invoice;
        $invoice->quotation_id = $request->quotation_id;
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->start_date = $request->start_date;
        $invoice->renewal_date = $request->renewal_date;
        $invoice->save();
        
        Toastr::success('invoice added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN')->orderBy('id', 'ASC')->get();
        $quotation_list = Quotation::where(['status' => 'ACCEPTED'])->orderBy('id', 'ASC')->get();
        $invoice = Invoice::where('id', $id)->first();
        return view('admin-views.invoice.edit',compact('invoice','quotation_list','client_list'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
         
        $request->validate([
            'client_id' => 'required',
            'quotation_id' => 'required',
            'invoice_number' => 'required',
            'start_date' => 'required',
            'renewal_date' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'quotation_id.required' => 'quotation_id is required!',
            'invoice_number.required' => 'invoice_number is required!',
            'start_date.required' => 'start_date is required!',
            'renewal_date.required' => 'renewal_date is required!',
        ]);

        $invoice = Invoice::find($id);
        $invoice->quotation_id = $request->quotation_id;
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->start_date = $request->start_date;
        $invoice->renewal_date = $request->renewal_date;
        $invoice->save();

        Toastr::success('invoice updated successfully!');
        return redirect()->route('admin.invoice.list');
    }
    
    public function view(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if (isset($invoice)) {
            return view('admin-views.invoice.invoice-view', compact('invoice'));
        }
        Toastr::error('invoice not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Invoice::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function invoice_status_change(Request $request)
    {
        if ($request->ajax()) {
            $invoice = Invoice::find($request->id);
            $invoice->status = $request->status;
            $invoice->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    
    public function get_quotation(Request $request)
    {
        $cat = Quotation::select('proposals.*','quotations.*','quotations.id AS quotations_id')->leftJoin('proposals', 'proposals.id', '=', 'quotations.proposal_id')->where(['quotations.client_id' => $request->client_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->quotation_select) {
                $res .= '<option value="' . $row->quotations_id . '" selected >' . $row->quotation_number . ' ( ' . $row->proposal_title . ' )' . '</option>';
            } else {
                $res .= '<option value="' . $row->quotations_id . '">' . $row->quotation_number . ' ( ' . $row->proposal_title . ' )' . '</option>';
            }
        }
        return response()->json(['select_tag' => $res]);
    }
    
    public function generate_invoice($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $quotation = Quotation::where('id', $invoice->quotation_id)->first();
        
        if($quotation) {
            $proposal = Proposal::where('id', $quotation->proposal_id)->first();
            $items = DB::table('quotation_items')->where(['quotation_id' => $quotation->id])->get();
        } else {
            $proposal = [];
            $items = []; 
        }  
        
        $client = User::where(['id' => $invoice->client_id])->first();
        
        $data["proposal"] = $proposal;
        $data["quotation"] = $quotation;
        $data["invoice"] = $invoice;
        $data["client"] = $client;
        $data["items"] = $items;
        
        $user_id = auth('customer')->user()->id;
        if($client && $invoice->client_id != $user_id) {
            $msg['name'] = $client->name;
            $msg['message'] = "Invoice has been generated.<br>Link: <a href='" . route('admin.invoice.generate_invoice',[$invoice['id']]) . "'>Click to view</a>";
            Mail::to($client->email)->send(new \App\Mail\Bulkmessage($msg));
        }
        
        return view('admin-views.invoice.generate_invoice', $data);
        
        // $html = view('admin-views.proposal.generate_proposal', ['proposal' => $proposal,'staff_list' => $staff_list])->render();
        // $this->pdfService->loadHtml($html);
        // $this->pdfService->setPaper('A4');
        // $this->pdfService->render();
        // return $this->pdfService->stream($proposal->proposal_title . '_' . date('d-m-Y_h:i:s') . '.pdf');
    }
    
    public function generate_receipt($id)
    {
        $receipt = DB::table('receipts')->where('id', $id)->first();
        $receipt_list = DB::table('receipts')->where('invoice_id', $receipt->invoice_id);
        
        $invoice = Invoice::where('id', $receipt->invoice_id)->first();
        $quotation = Quotation::where('id', $invoice->quotation_id)->first();
        
        if($quotation) {
            $proposal = Proposal::where('id', $quotation->proposal_id)->first();
            $items = DB::table('quotation_items')->where(['quotation_id' => $quotation->id])->get();
        } else {
            $proposal = [];
            $items = []; 
        }  
        
        $client = User::where(['id' => $invoice->client_id])->first();
        
        $data["proposal"] = $proposal;
        $data["quotation"] = $quotation;
        $data["invoice"] = $invoice;
        $data["receipt"] = $receipt;
        $data["receipt_list"] = $receipt_list;
        $data["client"] = $client;
        $data["items"] = $items;
        
        $user_id = auth('customer')->user()->id;
        if($client && $invoice->client_id != $user_id) {
            $msg['name'] = $client->name;
            //$msg['message'] = "Receipt has been generated.<br>Link: <a href='" . route('admin.invoice.generate_receipt',[$receipt['id']]) . "'>Click to view</a>";
           // Mail::to($client->email)->send(new \App\Mail\Bulkmessage($msg));
        }
        
        return view('admin-views.invoice.generate_receipt', $data);
        
        // $html = view('admin-views.proposal.generate_proposal', ['proposal' => $proposal,'staff_list' => $staff_list])->render();
        // $this->pdfService->loadHtml($html);
        // $this->pdfService->setPaper('A4');
        // $this->pdfService->render();
        // return $this->pdfService->stream($proposal->proposal_title . '_' . date('d-m-Y_h:i:s') . '.pdf');
    }
    
    public function add_receipts($invoice_id)
    {
        $receipt_number = "RC-" . (DB::table('receipts')->orderBy('id', 'DESC')->first() ? DB::table('receipts')->orderBy('id', 'DESC')->first()->id : 0) . "-" . rand(1000,9999);
        $receipts = DB::table('receipts')->where(['invoice_id' => $invoice_id])->orderBy('id', 'DESC')->get();
        $invoice = Invoice::where('id', $invoice_id)->first();
        
        $items_total = DB::table('quotation_items')->where(['quotation_id' => $invoice->quotation_id])->sum('total');
        
        return view('admin-views.invoice.add_receipts', compact('invoice', 'receipts','receipt_number','items_total'));
    }

    public function add_receipts_submit(Request $request, $invoice_id)
    {
        DB::table('receipts')->insert([
            'invoice_id' => $invoice_id,
            'receipt_number' => $request['receipt_number'],
            'receipt_date' => $request['receipt_date'],
            'amount_paid' => $request['amount_paid'],
            'payment_method' => $request['payment_method'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_receipts($invoice_id,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $receipts = DB::table('receipts')->where(['id' => $id])->first();
        $invoice = Invoice::where('id', $invoice_id)->first();
        return view('admin-views.invoice.edit_receipts',compact('invoice','receipts'));
    }

    public function update_receipts(Request $request,$invoice_id,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
         
        DB::table('receipts')->where('id', $id)->update([
            'invoice_id' => $invoice_id,
            'receipt_number' => $request['receipt_number'],
            'receipt_date' => $request['receipt_date'],
            'amount_paid' => $request['amount_paid'],
            'payment_method' => $request['payment_method'],
            'updated_at' => now(),
        ]);

        Toastr::success('invoice updated successfully!');
        return redirect()->route('admin.invoice.add_receipts',$invoice_id);
    }

    public function delete_receipts(Request $request)
    {
        DB::table('receipts')->where('id', $request->id)->delete();
        return response()->json();
    }
}