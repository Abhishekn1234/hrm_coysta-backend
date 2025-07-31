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

class TestcaseController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $projects = Project::where(['status' => 1])->orderBy('id', 'ASC')->get();

        $query_param = [];
        $search = $request['search'];
        $filter_project = $request['filter_project'];
        
        if ($request->has('search')) {
            $testcases = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['test_case_updated' => 1,'task_status' => 'Completed'])->where(function ($q) use ($search) {
                $q->Where('task_name', 'like', "%{$search}%");
            });
        } else {
            $testcases = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['test_case_updated' => 1,'task_status' => 'Completed']);
        }
        
        if ($request->has('filter_project') && $request['filter_project'] != 0) {
            $testcases = $testcases->where(['tasks.project_id' => $request['filter_project']]);
        }
         
        $query_param = ['search' => $request['search'],'filter_project' => $request['filter_project']];
        
        $counts = $testcases;
        $testcases = $testcases->orderBy('tasks.tested_by', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.testcase.view', compact('testcases','search','filter_project','counts','projects'));
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $testcase = Task::where('id', $id)->first();
        return view('admin-views.testcase.edit',compact('testcase'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;

        $testcase = Task::find($id);
        $testcase->tested_by = $user_id;
        $testcase->test_case = isset($request->test_case) ? $request->test_case : $testcase->test_case;
        $testcase->tester_remark = isset($request->tester_remark) ? $request->tester_remark : $testcase->tester_remark;
        $testcase->test_status = isset($request->test_status) ? $request->test_status : $testcase->test_status;
        
        if($request->test_status == 'FAILED'){
            $testcase->task_status = 'In Progress';
            $testcase->task_tracking_status = 'PAUSED';
            $testcase->task_ended_date = '';
            $testcase->task_ended_time = '';
            $testcase->task_to_time = '';
        }
        $testcase->test_date_time = now();
        $testcase->save();

        Toastr::success('testcase updated successfully!');
        return redirect()->route('admin.testcase.list');
    }
    
    public function view(Request $request, $id)
    {
        $testcase = Task::select('tasks.*','tasks.id AS tsid','projects.project_name','users.name AS user_name','users.user_type AS user_user_type')->leftJoin('users', 'users.id', '=', 'tasks.user_id')->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')->where(['tasks.id' => $id])->first();
        if (isset($testcase)) {
            return view('admin-views.testcase.testcase-view', compact('testcase'));
        }
        Toastr::error('testcase not found!');
        return back();
    }
}