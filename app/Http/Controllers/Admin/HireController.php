<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HireController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $search = $request['search'];
        $filter_gender = $request['filter_gender'];
        
        if ($request->has('search')) {
            $hires = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','PRODUCT_OWNER')->where('user_type','!=','CLIENT')->where(['status' => '1'])->where(function ($q) use ($search) {
                $q->Where('name', 'like', "%{$search}%");
            });
        } else {
            $hires = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','PRODUCT_OWNER')->where('user_type','!=','CLIENT')->where(['status' => '1']);
        }
        
        if ($request->has('filter_gender') && $request['filter_gender'] != '') {
            $hires = $hires->where(['gender' => $request['filter_gender']]);
        }
        
        $query_param = ['search' => $request['search'],'filter_gender' => $request['filter_gender']];
        
        $counts = $hires;
        $hires = $hires->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.hire.view', compact('hires','search','filter_gender','counts'));
    }

    public function hire_now(Request $request)
    {
        if ($request->ajax()) {
            $client_id = auth('customer')->user()->id;
            $staff_id = $request->id;
            
            $count = DB::table('hire_request')->where(['client_id' => $client_id,'staff_id' => $staff_id])->count();
            if($count == 0) {
                DB::table('hire_request')->insert([
                    'client_id' => $client_id,
                    'staff_id' => $staff_id,
                    'created_at' => now(),
                ]);
            } 
            return response()->json();
        }
    }
}