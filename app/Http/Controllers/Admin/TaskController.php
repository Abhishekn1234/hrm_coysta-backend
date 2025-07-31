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

class TaskController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $projects = Project::where(['status' => 1])->orderBy('id', 'ASC')->get();

        $query_param = [];
        $search = $request['search'];
        $filter_project = $request['filter_project'];
        $filter_date = $request['filter_date'];
        $filter_task_status = $request['filter_task_status'];
        
        if ($request->has('search')) {
            $tasks = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['user_id' => $user_id])->where(function ($q) use ($search) {
                $q->Where('task_name', 'like', "%{$search}%");
            });
        } else {
            $tasks = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['user_id' => $user_id]);
        }
        
        if ($request->has('filter_project') && $request['filter_project'] != 0) {
            $tasks = $tasks->where(['project_id' => $request['filter_project']]);
        }
        
        if ($request->has('filter_date') && $request['filter_date'] != '') {
            $tasks = $tasks->where(['date' => $request['filter_date']]);
        }
        
        if ($request->has('filter_task_status') && $request['filter_task_status'] != '') {
            $tasks = $tasks->where(['task_status' => $request['filter_task_status']]);
        }
        
        $query_param = ['search' => $request['search'],'filter_project' => $request['filter_project'],'filter_date' => $request['filter_date'],'filter_task_status' => $request['filter_task_status']];
        
        $counts = $tasks;
        $tasks = $tasks->orderBy('tasks.task_status', 'ASC')->orderBy('tasks.id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.task.view', compact('tasks','search','filter_project','filter_date','filter_task_status','counts','projects'));
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
            'date' => 'required',
            'project_id' => 'required',
            'task_name' => 'required',
            'estimated_time' => 'required',
        ], [
            'date.required' => 'date is required!',
            'project_id.required' => 'project_id is required!',
            'task_name.required' => 'task_name is required!',
            'estimated_time.required' => 'estimated_time is required!',
        ]);

        $task = new Task;
        $task->user_id = $user_id;
        $task->date = $request->date;
        $task->project_id = $request->project_id;
        $task->task_name = $request->task_name;
        $task->task_description = $request->task_description;
        $task->agile_work_detail = $request->agile_work_detail;
        $task->estimated_time = $request->estimated_time;
        $task->test_case = $request->test_case;
        $task->test_case_updated = $request->test_case_updated;
        
        if($user_type == 'TEAM_LEAD'){
            $task->team_lead_name = $user_name;
            $task->team_lead_remark = "";
            $task->team_lead_approval = 1;
            
            $task->reviewed_tech_lead_name = $user_name;
            $task->tech_lead_adjusted_time = $request->estimated_time;
            $task->tech_lead_remarks = "";
            $task->tech_lead_approval = 1;
        }
        
        if($user_type == 'TECHNICAL_LEAD'){
            $task->reviewed_tech_lead_name = $user_name;
            $task->tech_lead_adjusted_time = $request->estimated_time;
            $task->tech_lead_remarks = "";
            $task->tech_lead_approval = 1;
        }
        
        if($request->file('ui_sample')) {
            $task->ui_sample = ImageManager::upload('banner/', 'png', $request->file('ui_sample'));
        }
        
        if($request->file('database_file')) {
            $task->database_file = ImageManager::upload('banner/', 'sql', $request->file('database_file'));
        }
        
        $task->save();
        
        Toastr::success('task added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $task = Task::find($request->id);
            $task->status = $request->status;
            $task->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    
    public function ceo_approval(Request $request)
    {
        if ($request->ajax()) {
            $task = Task::find($request->id);
            $task->ceo_approval = $request->ceo_approval;
            $task->save();
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
        
        $task = Task::where('id', $id)->first();
        return view('admin-views.task.edit',compact('task','projects'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'date' => 'required',
            'project_id' => 'required',
            'task_name' => 'required',
            'estimated_time' => 'required',
        ], [
            'date.required' => 'date is required!',
            'project_id.required' => 'project_id is required!',
            'task_name.required' => 'task_name is required!',
            'estimated_time.required' => 'estimated_time is required!',
        ]);

        $task = Task::find($id);
        $task->date = $request->date;
        $task->project_id = $request->project_id;
        $task->task_name = $request->task_name;
        $task->task_description = $request->task_description;
        $task->agile_work_detail = $request->agile_work_detail;
        $task->estimated_time = $request->estimated_time;
        $task->test_case = $request->test_case;
        $task->test_case_updated = $request->test_case_updated;
        
        if($request->file('ui_sample')) {
            $task->ui_sample = ImageManager::update('banner/', $task['ui_sample'], 'png', $request->file('ui_sample'));
        }
        
        if($request->file('database_file')) {
            $task->database_file = ImageManager::update('banner/', $task['database_file'], 'sql', $request->file('database_file'));
        }
        
        $task->save();

        Toastr::success('task updated successfully!');
        return redirect()->route('admin.task.list');
    }
    
    public function view(Request $request, $id)
    {
        $task = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['tasks.id' => $id])->first();
        if (isset($task)) {
            return view('admin-views.task.task-view', compact('task'));
        }
        Toastr::error('task not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Task::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function task_start(Request $request)
    {
        $task = Task::find($request->id);
        
        $task_started_date = now();
        $task_started_time = now();
        $task_from_time = now();
        $task_tracking_status = 'STARTED';
        
        $task->task_started_date = $task_started_date;
        $task->task_started_time = $task_started_time;
        $task->task_from_time = $task_from_time;
        $task->task_tracking_status = $task_tracking_status;
        
        $task->save();
        
        return response()->json();
    }
    
    public function task_pause(Request $request)
    {
        $task = Task::find($request->id);
        
        if($task->task_tracking_status != 'PAUSED'){
            $task_to_time = now();
            if($task->task_tracking_status == 'STARTED'){
                $tracked_actual_time_taken = number_format((now()->diffInSeconds($task->task_started_time) / 60), 2);
            } else {
                $tracked_actual_time_taken = number_format((now()->diffInSeconds($task->task_from_time) / 60), 2);
            }
            
            $task_tracking_status = 'PAUSED';
            
            $task->task_to_time = $task_to_time;
            $task->tracked_actual_time_taken = $task->tracked_actual_time_taken + $tracked_actual_time_taken;
            $task->task_tracking_status = $task_tracking_status;
            
            $task->save();
            
            if($tracked_actual_time_taken > 0) {
                $user_id = auth('customer')->user()->id;
                
                $track_count = DB::table('task_trackings')->where(['task_id' => $request->id,'user_id' => $user_id,'time_taken' => $tracked_actual_time_taken,'date' => now(),'time' => now(),'created_at' => now(),'updated_at' => now()])->count();
                
                if($track_count == 0){            
                    DB::table('task_trackings')->insert([
                        'task_id' => $request->id,
                        'user_id' => $user_id,
                        'time_taken' => $tracked_actual_time_taken,
                        'date' => now(),
                        'time' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        return response()->json();
    }
    
    public function task_resume(Request $request)
    {
        $task = Task::find($request->id);
        
        $task_from_time = now();
        $task_tracking_status = 'RESUMED';
        
        $task->task_from_time = $task_from_time;
        $task->task_tracking_status = $task_tracking_status;
        
        $task->save();
        return response()->json();
    }
    
    public function task_end(Request $request)
    {
        $task = Task::find($request->id);
        
        if($task->task_tracking_status != 'ENDED'){
            $task_ended_date = now();
            $task_ended_time = now();
            $task_to_time = now();
            
            if($task->task_tracking_status == 'STARTED'){
                $tracked_actual_time_taken = number_format((now()->diffInSeconds($task->task_started_time) / 60), 2);
            } else if($task->task_tracking_status == 'PAUSED'){
                $tracked_actual_time_taken = 0;
            } else {
                $tracked_actual_time_taken = number_format((now()->diffInSeconds($task->task_from_time) / 60), 2);
            }
            
            $task_tracking_status = 'ENDED';
            $task_status = 'Completed';
            
            $task->task_ended_date = $task_ended_date;
            $task->task_ended_time = $task_ended_time;
            $task->task_to_time = $task_to_time;
            $task->tracked_actual_time_taken = $task->tracked_actual_time_taken + $tracked_actual_time_taken;
            $task->task_tracking_status = $task_tracking_status;
            $task->task_status = $task_status;
            
            $task->save();
            
            if($tracked_actual_time_taken > 0) {
                $user_id = auth('customer')->user()->id;
                
                $track_count = DB::table('task_trackings')->where(['task_id' => $request->id,'user_id' => $user_id,'time_taken' => $tracked_actual_time_taken,'date' => now(),'time' => now(),'created_at' => now(),'updated_at' => now()])->count();
                
                if($track_count == 0){            
                    DB::table('task_trackings')->insert([
                        'task_id' => $request->id,
                        'user_id' => $user_id,
                        'time_taken' => $tracked_actual_time_taken,
                        'date' => now(),
                        'time' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        return response()->json();
    }
}