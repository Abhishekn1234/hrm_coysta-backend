<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Lead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class LeadController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $staff_list = User::where(['status' => 1])->whereIn('user_type', ['CLIENT','CEO']);
        
        if($user_type == 'CLIENT'){
            $staff_list = $staff_list->where('id', $user_id);
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();

        $query_param = [];
        $search = $request['search'];
        $filter_staff_list = $request['filter_staff_list'];
        
        if ($request->has('search')) {
            $leads = Lead::select('leads.*')->where(function ($q) use ($search) {
                $q->Where('lead_name', 'like', "%{$search}%");
            });
        } else {
            $leads = Lead::select('leads.*');
        } 
        
        if ($request->has('filter_staff_list') && $request['filter_staff_list'] != 0) {
            $leads = $leads->where(['assigned_user_id' => $request['filter_staff_list']]);
        }
        
        if($user_type == 'CLIENT'){
            $leads = $leads->where('assigned_user_id', $user_id);
        }
        
        $query_param = ['search' => $request['search'],'filter_staff_list' => $request['filter_staff_list']];
        
        $counts = $leads;
        $leads = $leads->orderBy('leads.ceo_approval', 'ASC')->orderBy('leads.id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.lead.view', compact('leads','search','filter_staff_list','counts','staff_list'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        $user_name = auth('customer')->user()->name;
        
        $request->validate([
            'assigned_user_id' => 'required',
            'lead_name' => 'required',
            'lead_email' => 'required',
            'lead_phone' => 'required',
            'lead_sourse' => 'required',
        ], [
            'assigned_user_id.required' => 'assigned_user_id is required!',
            'lead_name.required' => 'lead_name is required!',
            'lead_email.required' => 'lead_email is required!',
            'lead_phone.required' => 'lead_phone is required!',
            'lead_sourse.required' => 'lead_sourse is required!',
        ]);

        $lead = new Lead;
        $lead->user_id = $user_id;
        $lead->assigned_user_id = $request->assigned_user_id;
        $lead->lead_name = $request->lead_name;
        $lead->lead_email = $request->lead_email;
        $lead->lead_phone = $request->lead_phone;
        $lead->lead_sourse = $request->lead_sourse;
        $lead->lead_notes = $request->lead_notes;
        $lead->save();
        
        Toastr::success('lead added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $staff_list = User::where(['status' => 1])->whereIn('user_type', ['CLIENT','CEO']);
        
        if($user_type == 'CLIENT'){
            $staff_list = $staff_list->where('id', $user_id);
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();
        
        $lead = Lead::where('id', $id)->first();
        return view('admin-views.lead.edit',compact('lead','staff_list'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'assigned_user_id' => 'required',
            'lead_name' => 'required',
            'lead_email' => 'required',
            'lead_phone' => 'required',
            'lead_sourse' => 'required',
        ], [
            'assigned_user_id.required' => 'assigned_user_id is required!',
            'lead_name.required' => 'lead_name is required!',
            'lead_email.required' => 'lead_email is required!',
            'lead_phone.required' => 'lead_phone is required!',
            'lead_sourse.required' => 'lead_sourse is required!',
        ]);

        $lead = Lead::find($id);
        $lead->assigned_user_id = $request->assigned_user_id;
        $lead->lead_name = $request->lead_name;
        $lead->lead_email = $request->lead_email;
        $lead->lead_phone = $request->lead_phone;
        $lead->lead_sourse = $request->lead_sourse;
        $lead->lead_notes = $request->lead_notes;
        $lead->save();

        Toastr::success('lead updated successfully!');
        return redirect()->route('admin.lead.list');
    }
    
    public function view(Request $request, $id)
    {
        $lead = Lead::where(['leads.id' => $id])->first();
        if (isset($lead)) {
            return view('admin-views.lead.lead-view', compact('lead'));
        }
        Toastr::error('lead not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Lead::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function ceo_approval(Request $request)
    {
        if ($request->ajax()) {
            $lead = Lead::find($request->id);
            $lead->ceo_approval = $request->ceo_approval;
            $lead->save();
            $data = $request->ceo_approval;
            return response()->json($data);
        }
    }
    
    public function bulk_import_index()
    {
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $staff_list = User::where(['status' => 1])->whereIn('user_type', ['CLIENT','CEO']);
        
        if($user_type == 'CLIENT'){
            $staff_list = $staff_list->where('id', $user_id);
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();
        
        return view('admin-views.lead.bulk-import', compact('staff_list'));
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('leads_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }
        
        $user_id = auth('customer')->user()->id;

        $data = [];
        foreach ($collections as $collection) {
            array_push($data, [
                'user_id' => $user_id,
                'assigned_user_id' => $request['assigned_user_id'],
                'lead_name' => $collection['lead_name'],
                'lead_email' => $collection['lead_email'],
                'lead_phone' => $collection['lead_phone'],
                'lead_sourse' => $collection['lead_sourse'],
                'lead_notes' => $collection['lead_notes'],
                'ceo_approval' => 1,
                'created_at' => now(),
            ]);
        }
        DB::table('leads')->insert($data);
        Toastr::success(count($data) . ' - Leads imported successfully!');
        return back();
    }
    
    public function lead_status_change(Request $request)
    {
        if ($request->ajax()) {
            $lead = Lead::find($request->id);
            $lead->lead_status = $request->lead_status;
            $lead->save();
            $data = $request->lead_status;
            return response()->json($data);
        }
    }
    
    public function processAction(Request $request)
    {
        $id = $request->multi_leads_id;
        $leadIds = explode(',', $id);
        $leadCount = count($leadIds);
        
        foreach ($leadIds as $leadId) {
            if($request->type == "DELETE") {
                $lead = Lead::find($leadId);
                $lead->delete();
            } else {
                $lead = Lead::find($leadId);
                $lead->lead_status = $request->type;
                $lead->save();
            }
        }
        
        return response()->json();
    }
    
    public function send_whatsapp(Request $request)
    {
        $id = $request->multi_leads_id;
        $leadIds = explode(',', $id);
        $leadCount = count($leadIds);
    
        $numbers = [];
        $names = [];
        foreach ($leadIds as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead && $lead->lead_phone) {
                // Add the phone number to the numbers array
                $numbers[] = $lead->lead_phone;
                $names[] = $lead->lead_name;
            }
        }
        
        // $numbers = [9747625648,9747627106];

        return response()->json(['numbers' => $numbers,'names' => $names]);
    }
    
    public function send_email(Request $request)
    {
        $id = $request->multi_leads_id;
        $bulk_message = $request->bulk_message;
        
        $leadIds = explode(',', $id);
        $leadCount = count($leadIds);
        
        $emails = [];
        foreach ($leadIds as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead && $lead->lead_email) {
                $emails[] = $lead->lead_email;
                
                // $test = 'rishikeshr850@gmail.com';
                
                $msg['name'] = $lead->lead_name;
                $msg['message'] = $request->bulk_message;
                Mail::to($lead->lead_email)->send(new \App\Mail\Bulkmessage($msg));
            }
        }

        return response()->json(['emails' => $emails]);
    }
}