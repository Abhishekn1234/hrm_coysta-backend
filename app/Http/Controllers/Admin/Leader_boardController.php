<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Task;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class Leader_boardController extends Controller
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
        
        $filter_from_date = $request['filter_from_date'];
        $filter_to_date = $request['filter_to_date'];
        
        $leader_boards = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','CLIENT')->where('user_type','!=','PRODUCT_OWNER')->where(['users.status' => '1']);
        
        $query_param = ['search' => $request['search'],'filter_from_date' => $request['filter_from_date'],'filter_to_date' => $request['filter_to_date']];
        
        
        $home = [];
        foreach($leader_boards->get() as $r){
            $r['total_time_taken'] = [];
            $data_set = DB::table('task_trackings')->where(['user_id' => $r->id]);
            
            if ($request->has('filter_from_date') && $request['filter_from_date'] != '') {
                $data_set = $data_set->where('date', '>=', $request['filter_from_date']);
            }
            
            if ($request->has('filter_to_date') && $request['filter_to_date'] != '') {
                $data_set = $data_set->where('date', '<=', $request['filter_to_date']);
            }
                
            $r['total_time_taken'] = $data_set->sum('time_taken');
            
            $values[] = $r;
            $home = $values;
        }
        
        $leader_boards = $home;

        return view('admin-views.leader_board.view', compact('leader_boards','search','filter_from_date','filter_to_date'));
    }
}