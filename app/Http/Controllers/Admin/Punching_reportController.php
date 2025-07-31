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

class Punching_reportController extends Controller
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
        $filter_date = $request['filter_date'];
        
        $filter_from_date = $request['filter_from_date'];
        $filter_to_date = $request['filter_to_date'];
        
        $punching_reports = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','CLIENT')->where('user_type','!=','PRODUCT_OWNER')->where(['users.status' => '1']);
        
        if($user_type != 'CEO' && $user_type != 'TEAM_LEAD'  && $user_type != 'HR'){
            $punching_reports = $punching_reports->where(['users.id' => $user_id]);
        }
        
        $query_param = ['search' => $request['search'],'filter_date' => $request['filter_date'],'filter_from_date' => $request['filter_from_date'],'filter_to_date' => $request['filter_to_date']];
        
        $counts = $punching_reports;
        
        $home = [];
        foreach($punching_reports->get() as $r){
            $r['total_time_taken'] = [];
            $data_set = DB::table('task_trackings')->where(['user_id' => $r->id]);
            
            if ($request->has('filter_date') && $request['filter_date'] != '') {
                $data_set = $data_set->where('date', '=', $request['filter_date']);
            }
            
            if ($request->has('filter_from_date') && $request['filter_from_date'] != '') {
                $data_set = $data_set->where('date', '>=', $request['filter_from_date']);
            }
            
            if ($request->has('filter_to_date') && $request['filter_to_date'] != '') {
                $data_set = $data_set->where('date', '<=', $request['filter_to_date']);
            }
                
            $r['total_time_taken'] = $data_set->sum('time_taken');
            
            $today = date('Y-m-d');
            $r['today_total_time_taken'] = [];
            $data_set_today = DB::table('task_trackings')->where(['user_id' => $r->id])->where('date', '=', $today);
            $r['today_total_time_taken'] = $data_set_today->sum('time_taken');
            
            $values[] = $r;
            $home = $values;
        }
        
        $punching_reports = $home;

        return view('admin-views.punching_report.view', compact('punching_reports','search','filter_date','filter_from_date','filter_to_date','counts'));
    }
    
    public function export_attendence(Request $request)
    {
        $id = $request['id'];
        $filter_date = $request['filter_date'];
        $filter_from_date = $request['filter_from_date'];
        $filter_to_date = $request['filter_to_date'];
        
        $data_set = DB::table('task_trackings')->select('*', DB::raw('SUM(time_taken) as total_time_taken'))->where('user_id', '=', $id)->groupBy('date');
    
        if ($request->has('filter_date') && $request['filter_date'] != '') {
            $data_set = $data_set->where('date', '=', $request['filter_date']);
        }
        
        if ($request->has('filter_from_date') && $request['filter_from_date'] != '') {
            $data_set = $data_set->where('date', '>=', $request['filter_from_date']);
        }
        
        if ($request->has('filter_to_date') && $request['filter_to_date'] != '') {
            $data_set = $data_set->where('date', '<=', $request['filter_to_date']);
        }
        
        $user = User::where('id', $id)->first();
        $filename = $user->name . '-'  .date('Y-m-d H:i:s');
        
        $employment_type_working_hour_new = $r->employment_type == 'FULL_TIME' ? 450 : 225;
        $employment_type_working_hour_old = $r->employment_type == 'FULL_TIME' ? 420 : 210;
        
        $storage = [];
        foreach($data_set->get() as $r){
            $user_name = $user->name;
            $date = $r->date;
            $employment_type = $user->employment_type;
            $total_time_taken = $r->total_time_taken;
            
            $minutes_today1 = floor($r->total_time_taken);
            $seconds_today1 = round(($r->total_time_taken - $minutes_today1) * 60);
            $formatted_min_today1 = $minutes_today1 . " min " . $seconds_today1 . " sec";
            
            $hours_today2 = floor($r->total_time_taken / 60);
            $minutes_today2 = $r->total_time_taken % 60;
            $formatted_time_today2 = $hours_today2 . " hr " . $minutes_today2 . " min";
            
            $type = ($r->date > date('Y-m-d', strtotime('April 8 2025'))) ? $employment_type_working_hour_new : $employment_type_working_hour_old;
            if($r->total_time_taken >= $type) {
                $status = 'Full Day';
            } elseif($r->total_time_taken >= ($type/2)) {
                $status = 'Half Day';
            } else {
                $status = 'Unaccountable';
            }
            
            $storage[] = [
                'Staff' => $user_name,
                'Employment Type' => $employment_type,
                'Date' => $date,
                'Total Time in Minutes' => $formatted_min_today1,
                'Total Time in Hours' => $formatted_time_today2,
                'Required Productive hours' => ($type/60),
                'status' => $status
            ];
        }
        
        return (new FastExcel($storage))->download($filename.'.xlsx');
    }
}