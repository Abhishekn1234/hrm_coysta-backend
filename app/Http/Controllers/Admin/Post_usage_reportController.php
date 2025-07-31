<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Task;
use App\Model\Project;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class Post_usage_reportController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $projects = Project::where(['status' => 1]);
        
        if($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT'){
            $projects = $projects->where(['product_owner_id' => $user_id]);
        }
        
        $project_set = $projects->orderBy('id', 'ASC')->get('id');
        $projects = $projects->orderBy('id', 'ASC')->get();

        $query_param = [];
        $search = $request['search'];
        $filter_project = $request['filter_project'];
        $filter_from_date = $request['filter_from_date'];
        $filter_to_date = $request['filter_to_date'];
        
        $post_usage_reports = Task::select('tasks.*','tasks.id AS tsid','projects.project_name', DB::raw('SUM(tracked_actual_time_taken) as total_tracked_actual_time_taken'))->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['task_status' => 'Completed']);
        
        if ($request->has('filter_project') && $request['filter_project'] != 0) {
            $post_usage_reports = $post_usage_reports->where(['project_id' => $request['filter_project']]);
        }
        
        if ($request->has('filter_from_date') && $request['filter_from_date'] != '') {
            $post_usage_reports = $post_usage_reports->where('date', '>=', $request['filter_from_date']);
        }
        
        if ($request->has('filter_to_date') && $request['filter_to_date'] != '') {
            $post_usage_reports = $post_usage_reports->where('date', '<=', $request['filter_to_date']);
        }
        
        if($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT'){
            $post_usage_reports = $post_usage_reports->whereIn('tasks.project_id', $project_set->pluck('id'));
        }
        
        $query_param = ['search' => $request['search'],'filter_project' => $request['filter_project'],'filter_from_date' => $request['filter_from_date'],'filter_to_date' => $request['filter_to_date']];
        
        $counts = $post_usage_reports;
        $post_usage_reports = $post_usage_reports->groupBy('tasks.project_id')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.post_usage_report.view', compact('post_usage_reports','search','filter_project','filter_from_date','filter_to_date','counts','projects'));
    }
    
    public function get_sprint(Request $request)
    {
        $today_date = date('Y-m-d');
        $project_id = $request->project_id;
        $starting_date = Project::where(['id' => $project_id])->first()->project_starting_date;
        
        if($starting_date > $today_date){
            $res = '';
        } else {
            $diff = now()->diffInDays($starting_date);
            $res = 'Sprint ' . ceil($diff / 7);
        }
        
        return response()->json(['sprint_name' => $res]);
    }
}