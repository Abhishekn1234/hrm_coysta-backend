<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Hire_requestController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $filter_client_list = $request['filter_client_list'];
        $filter_staff_list = $request['filter_staff_list'];
        
        $client_list = User::where(['user_type' => 'CLIENT'])->where(['status' => '1'])->get();
        $staff_list = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','PRODUCT_OWNER')->where('user_type','!=','CLIENT')->where(['status' => '1'])->get();
        
        $hire_requests = DB::table('hire_request')->where('staff_id', '!=', '0');
        
        if ($request->has('filter_client_list') && $request['filter_client_list'] != 0) {
            $hire_requests = $hire_requests->where(['client_id' => $request['filter_client_list']]);
        }
        
        if ($request->has('filter_staff_list') && $request['filter_staff_list'] != 0) {
            $hire_requests = $hire_requests->where(['staff_id' => $request['filter_staff_list']]);
        }
        
        $query_param = ['filter_client_list' => $request['filter_client_list'],'filter_staff_list' => $request['filter_staff_list']];
        
        $counts = $hire_requests;
        $hire_requests = $hire_requests->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.hire_request.view', compact('hire_requests','counts','filter_client_list','filter_staff_list','client_list','staff_list'));
    }
    
    public function view(Request $request, $id)
    {
        $staff = User::find($id);
        if (isset($staff)) {
            $reports_to = User::where(['status' => '1','id' => $staff->reports_to])->first();
            if($reports_to) {
                $staff->reports_to_name = $reports_to->name . ' ( ' . $reports_to->user_type . ' )';
            } else {
                $staff->reports_to_name = "";
            }
            
            return view('admin-views.hire_request.staff-view', compact('staff'));
        }
        Toastr::error('staff not found!');
        return back();
    }
}