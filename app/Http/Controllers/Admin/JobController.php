<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Job;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $search = $request['search'];
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        if ($request->has('search')) {
            $jobs = Job::where('job_title', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('job_title', 'like', "%{$search}%");
            });
        } else {
            $jobs = Job::where('job_title', '!=' , '');
        }
        
        $query_param = ['search' => $request['search']];
        
        $counts = $jobs;
        $jobs = $jobs->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.job.view', compact('jobs','search','counts'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'job_title' => 'required',
            'job_description' => 'required',
            'job_type' => 'required',
            'qualification_required' => 'required',
            'experience_required' => 'required',
            'min_salary' => 'required',
            'max_salary' => 'required',
        ], [
            'job_title.required' => 'job_title is required!',
            'job_description.required' => 'job_description is required!',
            'job_type.required' => 'job_type is required!',
            'qualification_required.required' => 'qualification_required is required!',
            'experience_required.required' => 'experience_required is required!',
            'min_salary.required' => 'min_salary is required!',
            'max_salary.required' => 'max_salary is required!',
        ]);

        $job = new Job;
        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;
        $job->job_type = $request->job_type;
        $job->qualification_required = $request->qualification_required;
        $job->experience_required = $request->experience_required;
        $job->min_salary = $request->min_salary;
        $job->max_salary = $request->max_salary;
        $job->save();
        
        Toastr::success('job added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $job = Job::find($request->id);
            $job->status = $request->status;
            $job->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $job = Job::where('id', $id)->first();
        return view('admin-views.job.edit',compact('job'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'job_title' => 'required',
            'job_description' => 'required',
            'job_type' => 'required',
            'qualification_required' => 'required',
            'experience_required' => 'required',
            'min_salary' => 'required',
            'max_salary' => 'required',
        ], [
            'job_title.required' => 'job_title is required!',
            'job_description.required' => 'job_description is required!',
            'job_type.required' => 'job_type is required!',
            'qualification_required.required' => 'qualification_required is required!',
            'experience_required.required' => 'experience_required is required!',
            'min_salary.required' => 'min_salary is required!',
            'max_salary.required' => 'max_salary is required!',
        ]);

        $job = Job::find($id);
        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;
        $job->job_type = $request->job_type;
        $job->qualification_required = $request->qualification_required;
        $job->experience_required = $request->experience_required;
        $job->min_salary = $request->min_salary;
        $job->max_salary = $request->max_salary;
        $job->save();

        Toastr::success('job updated successfully!');
        return redirect()->route('admin.job.list');
    }
    
    public function view(Request $request, $id)
    {
        $job = Job::find($id);
        if (isset($job)) {
            return view('admin-views.job.job-view', compact('job'));
        }
        Toastr::error('job not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Job::find($request->id);
        $br->delete();
        return response()->json();
    }
}