<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $search = $request['search'];
        $filter_user_type = $request['filter_user_type'];
        $filter_gender = $request['filter_gender'];
        
        $reports_to = User::where(['status' => '1'])->where('user_type','!=','ADMIN')->where('user_type','!=','CLIENT')->where('user_type','!=','PRODUCT_OWNER')->get();
        
        if ($request->has('search')) {
            $staffs = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','CLIENT')->where(function ($q) use ($search) {
                $q->Where('name', 'like', "%{$search}%");
            });
        } else {
            $staffs = User::where('user_type', '!=' , 'ADMIN')->where('user_type','!=','CLIENT');
        }
        
        if ($request->has('filter_user_type') && $request['filter_user_type'] != '') {
            $staffs = $staffs->where(['user_type' => $request['filter_user_type']]);
        }
        
        if ($request->has('filter_gender') && $request['filter_gender'] != '') {
            $staffs = $staffs->where(['gender' => $request['filter_gender']]);
        }
        
        $query_param = ['search' => $request['search'],'filter_user_type' => $request['filter_user_type'],'filter_gender' => $request['filter_gender']];
        
        $counts = $staffs;
        $staffs = $staffs->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.staff.view', compact('staffs','search','filter_user_type','filter_gender','counts','reports_to'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
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
        
        if($request->file('image')) {
            $staff->image = ImageManager::upload('banner/', 'png', $request->file('image'));
        }
        
        $staff->save();
        
        Toastr::success('Staff added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $staff = User::find($request->id);
            $staff->status = $request->status;
            $staff->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $reports_to = User::where(['status' => '1'])->where('user_type','!=','ADMIN')->where('user_type','!=','CLIENT')->where('user_type','!=','PRODUCT_OWNER')->get();
        
        $staff = User::where('id', $id)->first();
        return view('admin-views.staff.edit',compact('staff','reports_to'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'user_type' => 'required',
            'name' => 'required',
            'email' => 'required',
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

        $staff = User::find($id);
        $staff->user_type = $request->user_type;
        $staff->name = $request->name;
        $staff->email = $request->email;
        
        if ($request['password']) {
            $staff->password = bcrypt($request->password);
        }
        
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
        
        if($request->file('image')) {
            $staff->image = ImageManager::update('banner/', $staff['image'], 'png', $request->file('image'));
        }
        
        $staff->save();

        Toastr::success('Staff updated successfully!');
        return redirect()->route('admin.staff.list');
    }
    
    public function view(Request $request, $id)
    {
        $staff = User::find($id);
        if (isset($staff)) {
            $reports_to = User::where(['status' => '1','id' => $staff->reports_to])->first();
            if($reports_to) {
                $staff->reports_to_name = $reports_to->name . ' ( ' . $reports_to->user_type . ' )';
            } else {
                $staff->reports_to_name = "";
            }
            
            return view('admin-views.staff.staff-view', compact('staff'));
        }
        Toastr::error('staff not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = User::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function add_offer_letters($staff_id)
    {
        $letters = DB::table('letters')->where(['staff_id' => $staff_id,'type' => 'OFFER_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_offer_letters', compact('staff', 'letters'));
    }

    public function add_offer_letters_submit(Request $request, $staff_id)
    {
        DB::table('letters')->insert([
            'staff_id' => $staff_id,
            'type' => 'OFFER_LETTER',
            'terms_and_conditions' => $request['terms_and_conditions'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_offer_letters($staff_id,$id)
    {
        $letters = DB::table('letters')->where(['id' => $id,'type' => 'OFFER_LETTER'])->first();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.edit_offer_letters', compact('staff', 'letters'));
    }

    public function update_offer_letters(Request $request, $staff_id,$id)
    {
        DB::table('letters')->where('id', $id)->update([
            'staff_id' => $staff_id,
            'type' => 'OFFER_LETTER',
            'terms_and_conditions' => $request['terms_and_conditions'],
            'updated_at' => now(),
        ]);

        Toastr::success('Letter updated successfully!');
        return redirect()->route('admin.staff.add_offer_letters',$staff_id);
    }

    public function delete_offer_letters(Request $request)
    {
        DB::table('letters')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function generate_offer_letter($id,$letter_id)
    {
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'OFFER_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.staff.generate_offer_letter', $data);
    }
    
    public function add_releiving_letters($staff_id)
    {
        $letters = DB::table('letters')->where(['staff_id' => $staff_id,'type' => 'RELEIVING_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_releiving_letters', compact('staff', 'letters'));
    }

    public function add_releiving_letters_submit(Request $request, $staff_id)
    {
        DB::table('letters')->insert([
            'staff_id' => $staff_id,
            'type' => 'RELEIVING_LETTER',
            'join_date' => $request['join_date'],
            'releiving_date' => $request['releiving_date'],
            'dues_cleared' => $request['dues_cleared'],
            'experience_certificate_issued' => $request['experience_certificate_issued'],
            'notice_period_served' => $request['notice_period_served'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_releiving_letters($staff_id,$id)
    {
        $letters = DB::table('letters')->where(['id' => $id,'type' => 'RELEIVING_LETTER'])->first();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.edit_releiving_letters', compact('staff', 'letters'));
    }

    public function update_releiving_letters(Request $request, $staff_id,$id)
    {
        DB::table('letters')->where('id', $id)->update([
            'staff_id' => $staff_id,
            'type' => 'RELEIVING_LETTER',
            'join_date' => $request['join_date'],
            'releiving_date' => $request['releiving_date'],
            'dues_cleared' => $request['dues_cleared'],
            'experience_certificate_issued' => $request['experience_certificate_issued'],
            'notice_period_served' => $request['notice_period_served'],
            'updated_at' => now(),
        ]);

        Toastr::success('Letter updated successfully!');
        return redirect()->route('admin.staff.add_releiving_letters',$staff_id);
    }

    public function delete_releiving_letters(Request $request)
    {
        DB::table('letters')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function generate_releiving_letter($id,$letter_id)
    {
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'RELEIVING_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.staff.generate_releiving_letter', $data);
    }
    
    public function add_warning_letters($staff_id)
    {
        $letters = DB::table('letters')->where(['staff_id' => $staff_id,'type' => 'WARNING_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_warning_letters', compact('staff', 'letters'));
    }

    public function add_warning_letters_submit(Request $request, $staff_id)
    {
        DB::table('letters')->insert([
            'staff_id' => $staff_id,
            'type' => 'WARNING_LETTER',
            'warning_subject' => $request['warning_subject'],
            'warning_incident_date' => $request['warning_incident_date'],
            'policy_violated' => $request['policy_violated'],
            'warning_incident_discription' => $request['warning_incident_discription'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_warning_letters($staff_id,$id)
    {
        $letters = DB::table('letters')->where(['id' => $id,'type' => 'WARNING_LETTER'])->first();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.edit_warning_letters', compact('staff', 'letters'));
    }

    public function update_warning_letters(Request $request, $staff_id,$id)
    {
        DB::table('letters')->where('id', $id)->update([
            'staff_id' => $staff_id,
            'type' => 'WARNING_LETTER',
            'warning_subject' => $request['warning_subject'],
            'warning_incident_date' => $request['warning_incident_date'],
            'policy_violated' => $request['policy_violated'],
            'warning_incident_discription' => $request['warning_incident_discription'],
            'updated_at' => now(),
        ]);

        Toastr::success('Letter updated successfully!');
        return redirect()->route('admin.staff.add_warning_letters',$staff_id);
    }

    public function delete_warning_letters(Request $request)
    {
        DB::table('letters')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function generate_warning_letter($id,$letter_id)
    {
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'WARNING_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.staff.generate_warning_letter', $data);
    }
    
    public function add_termination_letters($staff_id)
    {
        $letters = DB::table('letters')->where(['staff_id' => $staff_id,'type' => 'TERMINATION_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_termination_letters', compact('staff', 'letters'));
    }

    public function add_termination_letters_submit(Request $request, $staff_id)
    {
        DB::table('letters')->insert([
            'staff_id' => $staff_id,
            'type' => 'TERMINATION_LETTER',
            'termination_date' => $request['termination_date'],
            'reason' => $request['reason'],
            'notice_period' => $request['notice_period'],
            'last_working_date' => $request['last_working_date'],
            'settlement_process' => $request['settlement_process'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_termination_letters($staff_id,$id)
    {
        $letters = DB::table('letters')->where(['id' => $id,'type' => 'TERMINATION_LETTER'])->first();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.edit_termination_letters', compact('staff', 'letters'));
    }

    public function update_termination_letters(Request $request, $staff_id,$id)
    {
        DB::table('letters')->where('id', $id)->update([
            'staff_id' => $staff_id,
            'type' => 'TERMINATION_LETTER',
            'termination_date' => $request['termination_date'],
            'reason' => $request['reason'],
            'notice_period' => $request['notice_period'],
            'last_working_date' => $request['last_working_date'],
            'settlement_process' => $request['settlement_process'],
            'updated_at' => now(),
        ]);

        Toastr::success('Letter updated successfully!');
        return redirect()->route('admin.staff.add_termination_letters',$staff_id);
    }

    public function delete_termination_letters(Request $request)
    {
        DB::table('letters')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function generate_termination_letter($id,$letter_id)
    {
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'TERMINATION_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.staff.generate_termination_letter', $data);
    }
    
    public function add_experiences($staff_id)
    {
        $letters = DB::table('letters')->where(['staff_id' => $staff_id,'type' => 'EXPERIENCE_CERTIFICATE'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_experiences', compact('staff', 'letters'));
    }

    public function add_experiences_submit(Request $request, $staff_id)
    {
        DB::table('letters')->insert([
            'staff_id' => $staff_id,
            'type' => 'EXPERIENCE_CERTIFICATE',
            'experience_description' => $request['experience_description'],
            'employement_start_date' => $request['employement_start_date'],
            'employement_end_date' => $request['employement_end_date'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }
    
    public function edit_experiences($staff_id,$id)
    {
        $letters = DB::table('letters')->where(['id' => $id,'type' => 'EXPERIENCE_CERTIFICATE'])->first();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.edit_experiences', compact('staff', 'letters'));
    }

    public function update_experiences(Request $request, $staff_id,$id)
    {
        DB::table('letters')->where('id', $id)->update([
            'staff_id' => $staff_id,
            'type' => 'EXPERIENCE_CERTIFICATE',
            'experience_description' => $request['experience_description'],
            'employement_start_date' => $request['employement_start_date'],
            'employement_end_date' => $request['employement_end_date'],
            'updated_at' => now(),
        ]);

        Toastr::success('Experiences updated successfully!');
        return redirect()->route('admin.staff.add_experiences',$staff_id);
    }

    public function delete_experiences(Request $request)
    {
        DB::table('letters')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function generate_experience_certificate($id,$letter_id)
    {
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'EXPERIENCE_CERTIFICATE'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.staff.generate_experience_certificate', $data);
    }
    
    public function add_certificates($staff_id)
    {
        $certificate_list = DB::table('certificates')->orderBy('id','ASC')->get();
        $certificates = DB::table('certificate_users')->leftJoin('certificates', 'certificate_users.certificate_id', '=', 'certificates.id')->where(['certificate_users.user_id' => $staff_id])->orderBy('certificate_users.id','DESC')->get();
        $staff = User::where('id', $staff_id)->first();
        return view('admin-views.staff.add_certificates', compact('staff', 'certificates','certificate_list'));
    }

    public function add_certificates_submit(Request $request, $staff_id)
    {
        $count = DB::table('certificate_users')->where(['user_id' => $staff_id,'certificate_id' => $request['certificate_id']])->count();
        
        if($count == 0){        
            DB::table('certificate_users')->insert([
                'user_id' => $staff_id,
                'certificate_id' => $request['certificate_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back();
    }
    
    public function delete_certificates(Request $request)
    {
        DB::table('certificate_users')->where('id', $request->id)->delete();
        return response()->json();
    }
    
    public function send_whatsapp(Request $request)
    {
        $id = $request->multi_staffs_id;
        $staffIds = explode(',', $id);
        $staffCount = count($staffIds);
    
        $numbers = [];
        $names = [];
        foreach ($staffIds as $staffId) {
            $staff = User::find($staffId);
            if ($staff && $staff->phone) {
                // Add the phone number to the numbers array
                $numbers[] = $staff->phone;
                $names[] = $staff->name;
            }
        }
        
        // $numbers = [9747625648,9747627106];

        return response()->json(['numbers' => $numbers,'names' => $names]);
    }
    
    public function send_email(Request $request)
    {
        $id = $request->multi_staffs_id;
        $bulk_message = $request->bulk_message;
        
        $staffIds = explode(',', $id);
        $staffCount = count($staffIds);
        
        $emails = [];
        foreach ($staffIds as $staffId) {
            $staff = User::find($staffId);
            if ($staff && $staff->email) {
                $emails[] = $staff->email;
                
                // $test = 'rishikeshr850@gmail.com';
                
                $msg['name'] = $staff->name;
                $msg['message'] = $request->bulk_message;
                Mail::to($staff->email)->send(new \App\Mail\Bulkmessage($msg));
            }
        }

        return response()->json(['emails' => $emails]);
    }
}