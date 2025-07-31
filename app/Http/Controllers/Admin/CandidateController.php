<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Candidate;
use App\Model\Job;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;

class CandidateController extends Controller
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
        
        $job = Job::get();
        
        if ($request->has('search')) {
            $candidates = Candidate::select('candidates.*')->where(function ($q) use ($search) {
                $q->Where('name', 'like', "%{$search}%")->orWhere('position', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");;
            });
        } else {
            $candidates = Candidate::select('candidates.*');
        }
        
        $query_param = ['search' => $request['search']];
        
        $candidates = $candidates;
        $candidates = $candidates->orderBy('candidates.id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.candidate.view', compact('candidates','search','job'));
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
            'job_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:candidates,email',
            'phone' => 'required|unique:candidates,phone',
            'position' => 'required',
            'experience' => 'required',
            'place' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'qualification' => 'required',
            'skills' => 'required',
            'tenth_mark_percentage' => 'required',
            'twelveth_mark_percentage' => 'required',
            'degree_mark_percentage' => 'required',
            'portfolio_link' => 'required',
        ], [
            'job_id.required' => 'job_id is required!',
            'name.required' => 'name is required!',
            'email.required' => 'email is required!',
            'phone.required' => 'phone is required!',
            'position.required' => 'position is required!',
            'experience.required' => 'experience is required!',
            'place.required' => 'place is required!',
            'address.required' => 'address is required!',
            'gender.required' => 'gender is required!',
            'date_of_birth.required' => 'date_of_birth is required!',
            'qualification.required' => 'qualification is required!',
            'skills.required' => 'skills is required!',
            'tenth_mark_percentage.required' => 'tenth_mark_percentage is required!',
            'twelveth_mark_percentage.required' => 'twelveth_mark_percentage is required!',
            'degree_mark_percentage.required' => 'degree_mark_percentage is required!',
            'portfolio_link.required' => 'portfolio_link is required!',
        ]);

        $candidate = new Candidate;
        $candidate->job_id = $request->job_id;
        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        
        $candidate->place = $request->place;
        $candidate->address = $request->address;
        $candidate->gender = $request->gender;
        $candidate->date_of_birth = $request->date_of_birth;
        $candidate->qualification = $request->qualification;
        
        $candidate->position = $request->position;
        $candidate->experience = $request->experience;
        $candidate->skills = $request->skills;
        
        $candidate->tenth_mark_percentage = $request->tenth_mark_percentage;
        $candidate->twelveth_mark_percentage = $request->twelveth_mark_percentage;
        $candidate->degree_mark_percentage = $request->degree_mark_percentage;
        $candidate->portfolio_link = $request->portfolio_link;
        
        if($request->file('resume')) {
            $candidate->resume = ImageManager::upload('banner/', 'pdf', $request->file('resume'));
        }
        
        if($request->file('last_qualification_certificate')) {
            $candidate->last_qualification_certificate = ImageManager::upload('banner/', 'pdf', $request->file('last_qualification_certificate'));
        }
        
        $candidate->save();
        
        Toastr::success('candidate added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $job = Job::get();
        $candidate = Candidate::where('id', $id)->first();
        return view('admin-views.candidate.edit',compact('candidate','job'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'job_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:candidates,email',
            'phone' => 'required|unique:candidates,phone',
            'position' => 'required',
            'experience' => 'required',
            'place' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'qualification' => 'required',
            'skills' => 'required',
            'tenth_mark_percentage' => 'required',
            'twelveth_mark_percentage' => 'required',
            'degree_mark_percentage' => 'required',
            'portfolio_link' => 'required',
        ], [
            'job_id.required' => 'job_id is required!',
            'name.required' => 'name is required!',
            'email.required' => 'email is required!',
            'phone.required' => 'phone is required!',
            'position.required' => 'position is required!',
            'experience.required' => 'experience is required!',
            'place.required' => 'place is required!',
            'address.required' => 'address is required!',
            'gender.required' => 'gender is required!',
            'date_of_birth.required' => 'date_of_birth is required!',
            'qualification.required' => 'qualification is required!',
            'skills.required' => 'skills is required!',
            'tenth_mark_percentage.required' => 'tenth_mark_percentage is required!',
            'twelveth_mark_percentage.required' => 'twelveth_mark_percentage is required!',
            'degree_mark_percentage.required' => 'degree_mark_percentage is required!',
            'portfolio_link.required' => 'portfolio_link is required!',
        ]);

        $candidate = Candidate::find($id);
        $candidate->job_id = $request->job_id;
        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        
        $candidate->place = $request->place;
        $candidate->address = $request->address;
        $candidate->gender = $request->gender;
        $candidate->date_of_birth = $request->date_of_birth;
        $candidate->qualification = $request->qualification;
        
        $candidate->position = $request->position;
        $candidate->experience = $request->experience;
        $candidate->skills = $request->skills;
        
        $candidate->tenth_mark_percentage = $request->tenth_mark_percentage;
        $candidate->twelveth_mark_percentage = $request->twelveth_mark_percentage;
        $candidate->degree_mark_percentage = $request->degree_mark_percentage;
        $candidate->portfolio_link = $request->portfolio_link;
        
        if($request->file('resume')) {
            $candidate->resume = ImageManager::update('banner/', $candidate['resume'], 'pdf', $request->file('resume'));
        }
        
        if($request->file('last_qualification_certificate')) {
            $candidate->last_qualification_certificate = ImageManager::update('banner/', $candidate['last_qualification_certificate'], 'pdf', $request->file('last_qualification_certificate'));
        }
        
        $candidate->save();

        Toastr::success('candidate updated successfully!');
        return redirect()->route('admin.candidate.list');
    }
    
    public function view(Request $request, $id)
    {
        $candidate = Candidate::where(['candidates.id' => $id])->first();
        if (isset($candidate)) {
            return view('admin-views.candidate.candidate-view', compact('candidate'));
        }
        Toastr::error('candidate not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Candidate::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function bulk_import_index()
    {
        $job = Job::get();
        return view('admin-views.candidate.bulk-import', compact('job'));
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('candidates_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }
        
        $user_id = auth('customer')->user()->id;
        $job_id = $request->job_id;

        $data = [];
        foreach ($collections as $collection) {
            array_push($data, [
                'job_id' => $job_id,
                'name' => $collection['name'],
                'email' => $collection['email'],
                'phone' => $collection['phone'],
                'place' => $collection['place'],
                'address' => $collection['address'],
                'gender' => $collection['gender'],
                'date_of_birth' => $collection['date_of_birth'],
                'qualification' => $collection['qualification'],
                'position' => $collection['position'],
                'experience' => $collection['experience'],
                'skills' => $collection['skills'],
                'created_at' => now(),
            ]);
        }
        DB::table('candidates')->insert($data);
        Toastr::success(count($data) . ' - Candidates imported successfully!');
        return back();
    }
    
    public function add_interview($candidate_id)
    {
        $candidate = Candidate::where('id', $candidate_id)->first();
        $interviewer_list = User::where(['status' => '1'])->whereIn('user_type', ['PRODUCT_OWNER', 'HR','TEAM_LEAD','TECHNICAL_LEAD','STAFF','CEO'])->get();
        $interviews = DB::table('interviews')->where(['candidate_id' => $candidate_id])->orderBy('id', 'DESC')->get();
        return view('admin-views.candidate.add_interview', compact('interviews','candidate','interviewer_list'));
    }

    public function add_interview_submit(Request $request, $candidate_id)
    {
        DB::table('interviews')->insert([
            'candidate_id' => $candidate_id,
            'interview_date' => $request['interview_date'],
            'interview_time' => $request['interview_time'],
            'interviewer_ids' => json_encode($request['interviewer_ids']),
            'google_meet_link' => $request['google_meet_link'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $candidate = Candidate::where('id', $candidate_id)->first();
        $msg['name'] = $candidate->name;
        
        $msg['message'] = "Interview has been scheduled on " . date('d M Y', strtotime($request['interview_date'])) . " at " . date('h:i:s A', strtotime($request['interview_time'])) . ".<br>Link: <a href='" . $request['google_meet_link'] . "'>Join Meeting</a>";

        Mail::to($candidate->email)->send(new \App\Mail\Bulkmessage($msg));

        return back();
    }

    public function delete_interview(Request $request)
    {
        DB::table('interviews')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function interview_status_change(Request $request)
    {
        if ($request->ajax()) {
            $interviews = DB::table('interviews')->where(['id' => $request->id])->update(['interview_status' => $request->interview_status]);
            $data = $request->status;
            return response()->json($data);
        }
    }
    
    public function staff_convert($candidate_id)
    {
        $candidate = Candidate::where('id', $candidate_id)->first();
        $reports_to = User::where(['status' => '1'])->where('user_type','!=','ADMIN')->where('user_type','!=','PRODUCT_OWNER')->where('user_type','!=','CLIENT')->get();
        return view('admin-views.candidate.staff_convert', compact('candidate','reports_to'));
    }

    public function staff_convert_submit(Request $request, $candidate_id)
    {
        $request->validate([
            'user_type' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'place' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'hourly_rate' => 'required',
            'monthly_rate' => 'required',
            'date_of_birth' => 'required',
            'qualification' => 'required',
            'experience' => 'required',
            'expertise' => 'required',
            'designation' => 'required',
            'role' => 'required',
            'reports_to' => 'required',
            'join_date' => 'required',
            'work_location' => 'required',
            'employment_type' => 'required',
            'annual_ctc' => 'required',
            'basic_salary' => 'required',
            'hra' => 'required',
            'special_allowances' => 'required',
            'probation_period' => 'required',
        ], [
            'user_type.required' => 'user_type is required!',
            'name.required' => 'name is required!',
            'email.required' => 'email is required!',
            'password.required' => 'password is required!',
            'phone.required' => 'phone is required!',
            'place.required' => 'place is required!',
            'address.required' => 'address is required!',
            'gender.required' => 'gender is required!',
            'hourly_rate.required' => 'hourly_rate is required!',
            'monthly_rate.required' => 'monthly_rate is required!',
            'date_of_birth.required' => 'date_of_birth is required!',
            'qualification.required' => 'qualification is required!',
            'experience.required' => 'experience is required!',
            'expertise.required' => 'expertise is required!',
            'designation.required' => 'designation is required!',
            'role.required' => 'role is required!',
            'reports_to.required' => 'reports_to is required!',
            'join_date.required' => 'join_date is required!',
            'work_location.required' => 'work_location is required!',
            'employment_type.required' => 'employment_type is required!',
            'annual_ctc.required' => 'annual_ctc is required!',
            'basic_salary.required' => 'basic_salary is required!',
            'hra.required' => 'hra is required!',
            'special_allowances.required' => 'special_allowances is required!',
            'probation_period.required' => 'probation_period is required!',
        ]);

        $staff = new User;
        $staff->user_type = $request->user_type;
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->phone = $request->phone;
        $staff->place = $request->place;
        $staff->address = $request->address;
        $staff->gender = $request->gender;
        $staff->hourly_rate = $request->hourly_rate;
        $staff->monthly_rate = $request->monthly_rate;
        $staff->date_of_birth = $request->date_of_birth;
        $staff->qualification = $request->qualification;
        $staff->experience = $request->experience;
        $staff->expertise = $request->expertise;
        $staff->designation = $request->designation;
        $staff->role = $request->role;
        $staff->reports_to = $request->reports_to;
        
        $staff->join_date = $request->join_date;
        $staff->work_location = $request->work_location;
        $staff->employment_type = $request->employment_type;
        $staff->annual_ctc = $request->annual_ctc;
        $staff->basic_salary = $request->basic_salary;
        $staff->hra = $request->hra;
        $staff->special_allowances = $request->special_allowances;
        $staff->probation_period = $request->probation_period;
        $staff->join_type = 'CANDIDATE';
        
        if($request->file('image')) {
            $staff->image = ImageManager::upload('banner/', 'png', $request->file('image'));
        }
        
        $staff->save();
        
        $candidate = Candidate::find($candidate_id);
        $candidate->is_staff = 1;
        $candidate->staff_id = $staff->id;
        $candidate->save();
        
        Toastr::success('Candidate converted added successfully!');
        return redirect()->route('admin.candidate.list');
    }
    
    public function processAction(Request $request)
    {
        $id = $request->multi_candidates_id;
        $candidateIds = explode(',', $id);
        $candidateCount = count($candidateIds);
    
        $numbers = [];
        $names = [];
        foreach ($candidateIds as $candidateId) {
            $candidate = Candidate::find($candidateId);
            if ($candidate && $candidate->phone) {
                // Add the phone number to the numbers array
                $numbers[] = $candidate->phone;
                $names[] = $candidate->name;
            }
        }
        
        // $numbers = [9747625648,9747627106];

        return response()->json(['numbers' => $numbers,'names' => $names]);
    }
    
    public function send_email(Request $request)
    {
        $id = $request->multi_candidates_id;
        $bulk_message = $request->bulk_message;
        
        $candidateIds = explode(',', $id);
        $candidateCount = count($candidateIds);
        
        $emails = [];
        foreach ($candidateIds as $candidateId) {
            $candidate = Candidate::find($candidateId);
            if ($candidate && $candidate->email) {
                $emails[] = $candidate->email;
                
                // $test = 'rishikeshr850@gmail.com';
                
                $msg['name'] = $candidate->name;
                $msg['message'] = $request->bulk_message;
                Mail::to($candidate->email)->send(new \App\Mail\Bulkmessage($msg));
            }
        }

        return response()->json(['emails' => $emails]);
    }
}