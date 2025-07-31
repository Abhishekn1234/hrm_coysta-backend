<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterviewController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        
        $user_id = auth('customer')->user()->id;
        $user_type = auth('customer')->user()->user_type;
        
        $ids = [];
        $interview_list = DB::table('interviews')->get();
        
        foreach ($interview_list as $cc) {
            $interviewerIds = json_decode($cc->interviewer_ids);
            if (in_array($user_id, $interviewerIds)) {
                $ids[] = $cc->id;
            }
        }
        
        if($user_type != 'CEO'){
            $interviews = DB::table('interviews')->whereIn('id', $ids);
        } else {
            $interviews = DB::table('interviews')->where('id', '!=', '');
        }
        
        $counts = $interviews;
        $interviews = $interviews->orderBy('interview_status', 'ASC')->orderBy('interview_date', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.interview.view', compact('interviews','counts'));
    }
    
    public function view(Request $request, $id)
    {
        $interview = DB::table('interviews')->where(['id' => $id])->first();
        if (isset($interview)) {
            return view('admin-views.interview.interview-view', compact('interview'));
        }
        Toastr::error('interview not found!');
        return back();
    }
    
    public function add_marks($interview_id)
    {
        $marks = DB::table('interview_marks')->where(['interview_id' => $interview_id])->orderBy('id', 'DESC')->get();
        $interview = DB::table('interviews')->where('id', $interview_id)->first();
        
        return view('admin-views.interview.add_marks', compact('interview', 'marks'));
    }

    public function add_marks_submit(Request $request, $interview_id)
    {
        $user_id = auth('customer')->user()->id;
        $interview = DB::table('interviews')->where('id', $interview_id)->first();
        
        DB::table('interview_marks')->insert([
            'interview_id' => $interview_id,
            'candidate_id' => $interview->candidate_id,
            'user_id' => $user_id,
            'marks' => $request['marks'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_marks($interview_id,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $marks = DB::table('interview_marks')->where(['id' => $id])->first();
        $interview = DB::table('interviews')->where('id', $interview_id)->first();
        return view('admin-views.interview.edit_marks',compact('interview','marks'));
    }

    public function update_marks(Request $request,$interview_id,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $user_id = auth('customer')->user()->id;
        $interview = DB::table('interviews')->where('id', $interview_id)->first();
         
        DB::table('interview_marks')->where('id', $id)->update([
            'interview_id' => $interview_id,
            'candidate_id' => $interview->candidate_id,
            'user_id' => $user_id,
            'marks' => $request['marks'],
            'updated_at' => now(),
        ]);

        Toastr::success('interview updated successfully!');
        return redirect()->route('admin.interview.add_marks',$interview_id);
    }

    public function delete_marks(Request $request)
    {
        DB::table('interview_marks')->where('id', $request->id)->delete();
        return response()->json();
    }
}
