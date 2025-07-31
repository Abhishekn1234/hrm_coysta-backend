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

class QuotationController extends Controller
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
        
        $quotation_number = "QT-" . (Quotation::orderBy('id', 'DESC')->first() ? Quotation::orderBy('id', 'DESC')->first()->id : 0) . "-" . rand(1000,9999);
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN');
        
        if($user_type != 'CEO'){
            $client_list = $client_list->where('id', $user_id);
        }
        
        $client_list = $client_list->orderBy('id', 'ASC')->get();
        
        $proposal_list = Proposal::where(['status' => 'FINALIZED'])->orderBy('id', 'ASC')->get();
        
        if ($request->has('search')) {
            $quotations = Quotation::where('status', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('quotation_number', 'like', "%{$search}%");
            });
        } else {
            $quotations = Quotation::where('status', '!=' , '');
        }
        
        if ($request->has('filter_status') && $request['filter_status'] != '') {
            $quotations = $quotations->where(['status' => $request['filter_status']]);
        }
        
        if ($request->has('filter_client') && $request['filter_client'] != 0) {
            $quotations = $quotations->where(['client_id' => $request['filter_client']]);
        }
        
        if($user_type == 'CLIENT'){
            $quotations = $quotations->where('client_id', $user_id);
        }
        
        $query_param = ['search' => $request['search'],'filter_status' => $request['filter_status'],'filter_client' => $request['filter_client']];
        
        $counts = $quotations;
        $quotations = $quotations->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.quotation.view', compact('quotations','search','filter_status','filter_client','client_list','proposal_list','quotation_number'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'client_id' => 'required',
            'quotation_number' => 'required',
            'quotation_date' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'quotation_number.required' => 'quotation_number is required!',
            'quotation_date.required' => 'quotation_date is required!',
        ]);

        $quotation = new Quotation;
        $quotation->proposal_id = $request->proposal_id;
        $quotation->client_id = $request->client_id;
        $quotation->quotation_number = $request->quotation_number;
        $quotation->quotation_date = $request->quotation_date;
        $quotation->due_date = $request->due_date;
        $quotation->notes = $request->notes;
        $quotation->save();
        
        Toastr::success('quotation added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN')->orderBy('id', 'ASC')->get();
        $proposal_list = Proposal::where(['status' => 'FINALIZED'])->orderBy('id', 'ASC')->get();
        $quotation = Quotation::where('id', $id)->first();
        return view('admin-views.quotation.edit',compact('quotation','proposal_list','client_list'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
         
        $request->validate([
            'client_id' => 'required',
            'quotation_number' => 'required',
            'quotation_date' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'quotation_number.required' => 'quotation_number is required!',
            'quotation_date.required' => 'quotation_date is required!',
        ]);

        $quotation = Quotation::find($id);
        $quotation->proposal_id = $request->proposal_id;
        $quotation->client_id = $request->client_id;
        $quotation->quotation_number = $request->quotation_number;
        $quotation->quotation_date = $request->quotation_date;
        $quotation->due_date = $request->due_date;
        $quotation->notes = $request->notes;
        $quotation->save();

        Toastr::success('quotation updated successfully!');
        return redirect()->route('admin.quotation.list');
    }
    
    public function view(Request $request, $id)
    {
        $quotation = Quotation::find($id);
        if (isset($quotation)) {
            return view('admin-views.quotation.quotation-view', compact('quotation'));
        }
        Toastr::error('quotation not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Quotation::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function quotation_status_change(Request $request)
    {
        if ($request->ajax()) {
            $quotation = Quotation::find($request->id);
            $quotation->status = $request->status;
            $quotation->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    
    public function get_proposal(Request $request)
    {
        $cat = Proposal::where(['client_id' => $request->client_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->proposal_select) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->proposal_title . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->proposal_title . '</option>';
            }
        }
        return response()->json(['select_tag' => $res]);
    }
    
    public function generate_quotation($id)
    {
        $quotation = Quotation::where('id', $id)->first();
        $proposal = Proposal::where('id', $quotation->proposal_id)->first();
        $items = DB::table('quotation_items')->where(['quotation_id' => $id])->get();
        $client = User::where(['id' => $quotation->client_id])->first();
        
        $data["proposal"] = $proposal;
        $data["quotation"] = $quotation;
        $data["client"] = $client;
        $data["items"] = $items;
        
        $user_id = auth('customer')->user()->id;
        if($client && $quotation->client_id != $user_id) {
            $msg['name'] = $client->name;
            $msg['message'] = "Quotation has been generated.<br>Link: <a href='" . route('admin.quotation.generate_quotation',[$quotation['id']]) . "'>Click to view</a>";
            Mail::to($client->email)->send(new \App\Mail\Bulkmessage($msg));
        }
        
        return view('admin-views.quotation.generate_quotation', $data);
        
        // $html = view('admin-views.proposal.generate_proposal', ['proposal' => $proposal,'staff_list' => $staff_list])->render();
        // $this->pdfService->loadHtml($html);
        // $this->pdfService->setPaper('A4');
        // $this->pdfService->render();
        // return $this->pdfService->stream($proposal->proposal_title . '_' . date('d-m-Y_h:i:s') . '.pdf');
    }
    
    public function add_items($quotation_id)
    {
        $items = DB::table('quotation_items')->where(['quotation_id' => $quotation_id])->get();
        $quotation = Quotation::where('id', $quotation_id)->first();
        return view('admin-views.quotation.add_items', compact('quotation', 'items'));
    }

    public function add_items_submit(Request $request, $quotation_id)
    {
        $total = ($request['quantity'] * $request['price']) + ($request['quantity'] * $request['price'] * $request['tax'] / 100); 
        DB::table('quotation_items')->insert([
            'quotation_id' => $quotation_id,
            'item_name' => $request['item_name'],
            'item_description' => $request['item_description'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'tax' => $request['tax'],
            'total' => $total,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }

    public function delete_items(Request $request)
    {
        DB::table('quotation_items')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function edit_items($quotation_id,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $quotation = Quotation::where('id', $quotation_id)->first();
        $item = DB::table('quotation_items')->where('id', $id)->first();
        return view('admin-views.quotation.edit_items',compact('item','quotation'));
    }

    public function update_items(Request $request,$quotation_id,$id)
    {
        if (auth('customer')->user() == NULL) { 
            return redirect()->route('admin.auth.logout'); 
        }  
           
        $total = ($request['quantity'] * $request['price']) + ($request['quantity'] * $request['price'] * $request['tax'] / 100); 
        DB::table('quotation_items')->where('id', $id)->update([
            'quotation_id' => $quotation_id,
            'item_name' => $request['item_name'],
            'item_description' => $request['item_description'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'tax' => $request['tax'],
            'total' => $total,
            'updated_at' => now(), 
        ]); 

        Toastr::success('Item updated successfully!');
        return redirect()->route('admin.quotation.add_items',$quotation_id);
    }
}