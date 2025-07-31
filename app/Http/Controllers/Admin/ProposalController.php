<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Proposal;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use App\Services\DomPDFService;

class ProposalController extends Controller
{
    protected $pdfService;
    public function __construct(DomPDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    
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
        
        if ($request->has('search')) {
            $proposals = Proposal::where('status', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('proposal_title', 'like', "%{$search}%");
            });
        } else {
            $proposals = Proposal::where('status', '!=' , '');
        }
        
        if ($request->has('filter_status') && $request['filter_status'] != '') {
            $proposals = $proposals->where(['status' => $request['filter_status']]);
        }
        
        if ($request->has('filter_client') && $request['filter_client'] != 0) {
            $proposals = $proposals->where(['client_id' => $request['filter_client']]);
        }
        
        if($user_type == 'CLIENT'){
            $proposals = $proposals->where('client_id', $user_id);
        }
        
        $query_param = ['search' => $request['search'],'filter_status' => $request['filter_status'],'filter_client' => $request['filter_client']];
        
        $counts = $proposals;
        $proposals = $proposals->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.proposal.view', compact('proposals','search','filter_status','filter_client','client_list'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'client_id' => 'required',
            'proposal_title' => 'required',
            'proposal_date' => 'required',
            'proposal_description' => 'required',
            'min_expected_amount' => 'required',
            'max_expected_amount' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'proposal_title.required' => 'proposal_title is required!',
            'proposal_date.required' => 'proposal_date is required!',
            'proposal_description.required' => 'proposal_description is required!',
            'min_expected_amount.required' => 'min_expected_amount is required!',
            'max_expected_amount.required' => 'max_expected_amount is required!',

        ]);

        $proposal = new Proposal;
        $proposal->client_id = $request->client_id;
        $proposal->proposal_title = $request->proposal_title;
        $proposal->proposal_date = $request->proposal_date;
        $proposal->proposal_description = $request->proposal_description;
        $proposal->min_expected_amount = $request->min_expected_amount;
        $proposal->max_expected_amount = $request->max_expected_amount;
        
        if($request->file('direct_pdf')) {
            $proposal->direct_pdf = ImageManager::upload('banner/', 'pdf', $request->file('direct_pdf'));
        }
        
        $proposal->save();
        
        Toastr::success('proposal added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $client_list = User::where(['status' => 1])->where('user_type', '!=' , 'ADMIN')->orderBy('id', 'ASC')->get();
        $proposal = Proposal::where('id', $id)->first();
        return view('admin-views.proposal.edit',compact('proposal','client_list'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'client_id' => 'required',
            'proposal_title' => 'required',
            'proposal_date' => 'required',
            'proposal_description' => 'required',
            'min_expected_amount' => 'required',
            'max_expected_amount' => 'required',
            'status' => 'required',
        ], [
            'client_id.required' => 'client_id is required!',
            'proposal_title.required' => 'proposal_title is required!',
            'proposal_date.required' => 'proposal_date is required!',
            'proposal_description.required' => 'proposal_description is required!',
            'min_expected_amount.required' => 'min_expected_amount is required!',
            'max_expected_amount.required' => 'max_expected_amount is required!',
            'status.required' => 'status is required!',
        ]);

        $proposal = Proposal::find($id);
        $proposal->client_id = $request->client_id;
        $proposal->proposal_title = $request->proposal_title;
        $proposal->proposal_date = $request->proposal_date;
        $proposal->proposal_description = $request->proposal_description;
        $proposal->min_expected_amount = $request->min_expected_amount;
        $proposal->max_expected_amount = $request->max_expected_amount;
        $proposal->status = $request->status;
        
        if($request->file('direct_pdf')) {
            $proposal->direct_pdf = ImageManager::update('banner/', $proposal['direct_pdf'], 'pdf', $request->file('direct_pdf'));
        }
        
        $proposal->save();

        Toastr::success('proposal updated successfully!');
        return redirect()->route('admin.proposal.list');
    }
    
    public function view(Request $request, $id)
    {
        $proposal = Proposal::find($id);
        if (isset($proposal)) {
            return view('admin-views.proposal.proposal-view', compact('proposal'));
        }
        Toastr::error('proposal not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Proposal::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function generate_proposal($id)
    {
        $proposal = Proposal::where('id', $id)->first();
        $staff_list = User::where(['status' => '1'])->whereIn('user_type', ['CEO','HR','TEAM_LEAD','TECHNICAL_LEAD','QAQC','STAFF'])->get();
        
        $data["proposal"] = $proposal;
        $data["staff_list"] = $staff_list;
        
        $client = User::where('id', $proposal->client_id)->first();
        
        $user_id = auth('customer')->user()->id;
        if($client && $proposal->client_id != $user_id) {
            $msg['name'] = $client->name;
            $msg['message'] = "Proposal has been generated.<br>Link: <a href='" . route('admin.proposal.generate_proposal',[$proposal['id']]) . "'>Click to view</a>";
            Mail::to($client->email)->send(new \App\Mail\Bulkmessage($msg));
        }
        return view('admin-views.proposal.generate_proposal', $data);
        
        // $html = view('admin-views.proposal.generate_proposal', ['proposal' => $proposal,'staff_list' => $staff_list])->render();
        // $this->pdfService->loadHtml($html);
        // $this->pdfService->setPaper('A4');
        // $this->pdfService->render();
        // return $this->pdfService->stream($proposal->proposal_title . '_' . date('d-m-Y_h:i:s') . '.pdf');
    }
}