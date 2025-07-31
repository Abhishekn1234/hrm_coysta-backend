<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Backlog;
use App\Model\Task;
use App\Model\Project;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class BacklogController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $projects = Project::where(['status' => 1]);
        $staff_list_f = User::where(['status' => 1])->where('user_type' ,'!=', 'ADMIN')->orderBy('id', 'ASC')->get();
        $staff_list = User::where(['status' => 1])->where('user_type' ,'!=', 'ADMIN');
        
        if($user_type != 'CEO'){
            $staff_list = $staff_list->where('id', $user_id);
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();
        
        if($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT'){
            $projects = $projects->where(['product_owner_id' => $user_id]);
        }
        
        $project_set = $projects->orderBy('id', 'ASC')->get('id');
        $projects = $projects->orderBy('id', 'ASC')->get();

        $query_param = [];
        $search = $request['search'];
        $filter_project = $request['filter_project'];
        $filter_staff_list = $request['filter_staff_list'];
        
        if ($request->has('search')) {
            $backlogs = Backlog::select('backlogs.*','backlogs.id AS tsid','projects.project_name')->leftJoin('projects', 'projects.id', '=', 'backlogs.project_id')->where(function ($q) use ($search) {
                $q->Where('backlog_name', 'like', "%{$search}%");
            });
        } else {
            $backlogs = Backlog::select('backlogs.*','backlogs.id AS tsid','projects.project_name')->leftJoin('projects', 'projects.id', '=', 'backlogs.project_id');
        }
        
        if ($request->has('filter_project') && $request['filter_project'] != 0) {
            $backlogs = $backlogs->where(['project_id' => $request['filter_project']]);
        }
        
        if ($request->has('filter_staff_list') && $request['filter_staff_list'] != 0) {
            $backlogs = $backlogs->where(['backlog_assigned_user_id' => $request['filter_staff_list']]);
        }
        
        if($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT'){
            $backlogs = $backlogs->whereIn('backlogs.project_id', $project_set->pluck('id'));
        }
        
        $query_param = ['search' => $request['search'],'filter_project' => $request['filter_project'],'filter_staff_list' => $request['filter_staff_list']];
        
        $counts = $backlogs;
        $backlogs = $backlogs->orderBy('backlogs.ceo_approval', 'ASC')->orderBy('backlogs.status', 'ASC')->orderBy('backlogs.user_id', 'ASC')->orderBy('backlogs.id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.backlog.view', compact('backlogs','search','filter_project','filter_staff_list','counts','projects','staff_list','staff_list_f'));
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
            'project_id' => 'required',
            'backlog_assigned_user_id' => 'required',
            'sprint_name' => 'required',
            'backlog_name' => 'required',
            'estimated_time' => 'required',
        ], [
            'project_id.required' => 'project_id is required!',
            'backlog_assigned_user_id.required' => 'backlog_assigned_user_id is required!',
            'sprint_name.required' => 'sprint_name is required!',
            'backlog_name.required' => 'backlog_name is required!',
            'estimated_time.required' => 'estimated_time is required!',
        ]);

        $backlog = new Backlog;
        $backlog->user_id = $user_id;
        $backlog->project_id = $request->project_id;
        $backlog->backlog_assigned_user_id = $request->backlog_assigned_user_id;
        $backlog->sprint_name = $request->sprint_name;
        $backlog->backlog_name = $request->backlog_name;
        $backlog->backlog_description = $request->backlog_description;
        $backlog->estimated_time = $request->estimated_time;
        $backlog->save();
        
        Toastr::success('backlog added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $projects = Project::where(['status' => 1])->orderBy('id', 'ASC')->get();
        $staff_list = User::where(['status' => 1])->where('user_type' ,'!=', 'ADMIN');
        
        if($user_type != 'CEO'){
            $staff_list = $staff_list->where('id', $user_id);
        }
        
        $staff_list = $staff_list->orderBy('id', 'ASC')->get();
        
        $backlog = Backlog::where('id', $id)->first();
        return view('admin-views.backlog.edit',compact('backlog','projects','staff_list'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'project_id' => 'required',
            'backlog_assigned_user_id' => 'required',
            'sprint_name' => 'required',
            'backlog_name' => 'required',
            'estimated_time' => 'required',
        ], [
            'project_id.required' => 'project_id is required!',
            'backlog_assigned_user_id.required' => 'backlog_assigned_user_id is required!',
            'sprint_name.required' => 'sprint_name is required!',
            'backlog_name.required' => 'backlog_name is required!',
            'estimated_time.required' => 'estimated_time is required!',
        ]);

        $backlog = Backlog::find($id);
        $backlog->project_id = $request->project_id;
        $backlog->backlog_assigned_user_id = $request->backlog_assigned_user_id;
        $backlog->sprint_name = $request->sprint_name;
        $backlog->backlog_name = $request->backlog_name;
        $backlog->backlog_description = $request->backlog_description;
        $backlog->estimated_time = $request->estimated_time;
        $backlog->save();

        Toastr::success('backlog updated successfully!');
        return redirect()->route('admin.backlog.list');
    }
    
    public function view(Request $request, $id)
    {
        $backlog = Backlog::select('backlogs.*','backlogs.id AS tsid','projects.project_name')->leftJoin('projects', 'projects.id', '=', 'backlogs.project_id')->where(['backlogs.id' => $id])->first();
        if (isset($backlog)) {
            return view('admin-views.backlog.backlog-view', compact('backlog'));
        }
        Toastr::error('backlog not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Backlog::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function add_to_task(Request $request)
    {
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        $user_name = auth('customer')->user()->name;
        $today_date = date('Y-m-d');
        
        $backlog = Backlog::find($request->id);
        
            $task = new Task;
            $task->user_id = $user_id;
            $task->date = $today_date;
            $task->project_id = $backlog->project_id;
            $task->task_name = $backlog->backlog_name;
            $task->task_description = $backlog->backlog_description;
            $task->estimated_time = $backlog->estimated_time;
            
            if($user_type == 'TEAM_LEAD'){
                $task->team_lead_name = $user_name;
                $task->team_lead_remark = "";
                $task->team_lead_approval = 1;
                
                $task->reviewed_tech_lead_name = $user_name;
                $task->tech_lead_adjusted_time = $backlog->estimated_time;
                $task->tech_lead_remarks = "";
                $task->tech_lead_approval = 1;
            }
            
            if($user_type == 'TECHNICAL_LEAD'){
                $task->reviewed_tech_lead_name = $user_name;
                $task->tech_lead_adjusted_time = $backlog->estimated_time;
                $task->tech_lead_remarks = "";
                $task->tech_lead_approval = 1;
            }
            
            $task->task_status = 'In Progress';
            $task->save();
        
        $backlog->backlog_taken_user_id = $user_id;
        $backlog->assigned_task_id = $task->id;
        $backlog->status = 1;
        $backlog->save();
        return response()->json();
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
    
    public function ceo_approval(Request $request)
    {
        if ($request->ajax()) {
            $backlog = Backlog::find($request->id);
            $backlog->ceo_approval = $request->ceo_approval;
            $backlog->save();
            $data = $request->ceo_approval;
            return response()->json($data);
        }
    }
    
    public function time_edit(Request $request)
    {
        if ($request->ajax()) {
            $backlog = Backlog::find($request->id);
            $backlog->estimated_time = $request->time_edit;
            $backlog->save();
            $data = $request->time_edit;
            return response()->json($data);
        }
    }
    
    public function bulk_import_index()
    {
        return view('admin-views.backlog.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('backlogs_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }
        
        $user_id = auth('customer')->user()->id;

        $data = [];
        foreach ($collections as $collection) {
            array_push($data, [
                'user_id' => $user_id,
                'project_id' => $collection['project_id'],
                'backlog_assigned_user_id' => $collection['backlog_assigned_user_id'],
                'sprint_name' => $collection['sprint_name'],
                'backlog_name' => $collection['backlog_name'],
                'backlog_description' => $collection['backlog_description'],
                'estimated_time' => $collection['estimated_time'],
                'ceo_approval' => 1,
                'created_at' => now(),
            ]);
        }
        DB::table('backlogs')->insert($data);
        Toastr::success(count($data) . ' - Backlogs imported successfully!');
        return back();
    }
}