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

class StafftaskController extends Controller
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
        $filter_date = $request['filter_date'];
        $filter_task_status = $request['filter_task_status'];
        $filter_staff_list = $request['filter_staff_list'];
        
        $pending_approval = Task::where('user_id' ,'!=', $user_id);
        
        if($user_type == 'TEAM_LEAD'){
            $pending_approval = $pending_approval->where(['tasks.tech_lead_approval' => 0]);
        }
        
        if($user_type == 'TECHNICAL_LEAD'){
            $pending_approval = $pending_approval->where(['tasks.team_lead_approval' => 0]);
        }
        
        if($user_type == 'CEO'){
            $pending_approval = $pending_approval->where(['tasks.ceo_approval' => 0]);
        }
        
        $pending_approval = $pending_approval->count();
        
        if ($request->has('search')) {
            $stafftasks = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where('user_id' ,'!=', $user_id)->where(function ($q) use ($search) {
                $q->Where('task_name', 'like', "%{$search}%");
            });
        } else {
            $stafftasks = Task::select('tasks.*','tasks.id AS tsid','projects.*','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where('user_id' ,'!=', $user_id);
        }
        
        if ($request->has('filter_project') && $request['filter_project'] != 0) {
            $stafftasks = $stafftasks->where(['tasks.project_id' => $request['filter_project']]);
        }
        
        if ($request->has('filter_date') && $request['filter_date'] != '') {
            $stafftasks = $stafftasks->where(['tasks.date' => $request['filter_date']]);
        }
        
        if ($request->has('filter_task_status') && $request['filter_task_status'] != '') {
            $stafftasks = $stafftasks->where(['tasks.task_status' => $request['filter_task_status']]);
        }
        
        if ($request->has('filter_staff_list') && $request['filter_staff_list'] != 0) {
            $stafftasks = $stafftasks->where(['tasks.user_id' => $request['filter_staff_list']]);
        }
        
        $staff_list = User::where(['status' => 1])->where('user_type' ,'!=', 'ADMIN')->where('user_type' ,'!=', 'CEO');
        
        $user_set = User::where(['reports_to' => $user_id,'status' => 1])->get('id');
        $user_sub_set = User::where(['status' => 1])->whereIn('users.reports_to', $user_set->pluck('id'))->get('id');
        
        if($user_type == 'TEAM_LEAD'){
            $stafftasks = $stafftasks->where(function ($q) use ($user_set,$user_sub_set) {
                $q->whereIn('tasks.user_id', $user_set->pluck('id'))->orwhereIn('tasks.user_id', $user_sub_set->pluck('id'));
            });
            $staff_list = $staff_list->whereIn('users.id', $user_set->pluck('id'))->orwhereIn('users.id', $user_sub_set->pluck('id'));
        }
        
        if($user_type == 'TECHNICAL_LEAD'){
            $stafftasks = $stafftasks->where(function ($q) use ($user_set) {
                $q->whereIn('tasks.user_id', $user_set->pluck('id'));
            });
            $staff_list = $staff_list->whereIn('users.id', $user_set->pluck('id'));
        }
        
        if($user_type == 'MARKETING_MANAGER'){
            $stafftasks = $stafftasks->where(function ($q) use ($user_set) {
                $q->whereIn('tasks.user_id', $user_set->pluck('id'));
            });
            $staff_list = $staff_list->whereIn('users.id', $user_set->pluck('id'));
        }
        
        if($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT'){
            $stafftasks = $stafftasks->whereIn('tasks.project_id', $project_set->pluck('id'));
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();
         
        $query_param = ['search' => $request['search'],'filter_project' => $request['filter_project'],'filter_date' => $request['filter_date'],'filter_task_status' => $request['filter_task_status'],'filter_staff_list' => $request['filter_staff_list']];
        
        $counts = $stafftasks;
        $stafftasks = $stafftasks->orderBy('tasks.task_status', 'ASC')->orderBy('tasks.id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.stafftask.view', compact('stafftasks','search','filter_project','filter_date','filter_task_status','filter_staff_list','counts','projects','staff_list','pending_approval'));
    }
    
    public function ceo_approval(Request $request)
    {
        if ($request->ajax()) {
            $stafftask = Task::find($request->id);
            $stafftask->ceo_approval = $request->ceo_approval;
            $stafftask->save();
            $data = $request->ceo_approval;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $projects = Project::where(['status' => 1])->orderBy('id', 'ASC')->get();
        $stafftask = Task::where('id', $id)->first();
        return view('admin-views.stafftask.edit',compact('stafftask','projects'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        $user_name = auth('customer')->user()->name;

        $stafftask = Task::find($id);
        $stafftask->tech_lead_adjusted_time = isset($request->tech_lead_adjusted_time) ? $request->tech_lead_adjusted_time : $stafftask->tech_lead_adjusted_time;
        $stafftask->tech_lead_remarks = isset($request->tech_lead_remarks) ? $request->tech_lead_remarks : $stafftask->tech_lead_remarks;
        $stafftask->tech_lead_approval = isset($request->tech_lead_approval) ? $request->tech_lead_approval : $stafftask->tech_lead_approval;
        $stafftask->team_lead_remark = isset($request->team_lead_remark) ? $request->team_lead_remark : $stafftask->team_lead_remark;
        $stafftask->team_lead_approval = isset($request->team_lead_approval) ? $request->team_lead_approval : $stafftask->team_lead_approval;
        $stafftask->ceo_approval = isset($request->ceo_approval) ? $request->ceo_approval : $stafftask->ceo_approval;
        
        if($user_type == 'TEAM_LEAD'){
            $stafftask->team_lead_name = $user_name;
        }
        
        if($user_type == 'TECHNICAL_LEAD'){
            $stafftask->reviewed_tech_lead_name = $user_name;
        }
        
        if($user_type == 'CEO' || $user_type == 'TEAM_LEAD'){
            $stafftask->date = isset($request->date) ? $request->date : $stafftask->date;
            $stafftask->project_id = isset($request->project_id) ? $request->project_id : $stafftask->project_id;
            $stafftask->task_name = isset($request->task_name) ? $request->task_name : $stafftask->task_name;
            $stafftask->task_description = isset($request->task_description) ? $request->task_description : $stafftask->task_description;
            $stafftask->agile_work_detail = isset($request->agile_work_detail) ? $request->agile_work_detail : $stafftask->agile_work_detail;
            $stafftask->estimated_time = isset($request->estimated_time) ? $request->estimated_time : $stafftask->estimated_time;
            $stafftask->test_case = isset($request->test_case) ? $request->test_case : $stafftask->test_case;
            $stafftask->test_case_updated = isset($request->test_case_updated) ? $request->test_case_updated : $stafftask->test_case_updated;
        }
        
        $stafftask->save();

        Toastr::success('stafftask updated successfully!');
        return redirect()->route('admin.stafftask.list');
    }
    
    public function view(Request $request, $id)
    {
        $stafftask = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['tasks.id' => $id])->first();
        if (isset($stafftask)) {
            return view('admin-views.stafftask.stafftask-view', compact('stafftask'));
        }
        Toastr::error('stafftask not found!');
        return back();
    }
    
    public function task_restart(Request $request)
    {
        $stafftask = Task::find($request->id);
        
        $task_tracking_status = 'PAUSED';
        $stafftask->task_status = 'In Progress';
        $stafftask->task_ended_date = '';
        $stafftask->task_ended_time = '';
        $stafftask->task_to_time = '';
        $stafftask->task_tracking_status = $task_tracking_status;
        
        $stafftask->save();
        
        return response()->json();
    }
    
    public function track_time_edit(Request $request)
    {
        if ($request->ajax()) {
            $stafftask = Task::find($request->id);
            $stafftask->tracked_actual_time_taken = $request->time_edit;
            $stafftask->save();
            $data = $request->time_edit;
            return response()->json($data);
        }
    }
}