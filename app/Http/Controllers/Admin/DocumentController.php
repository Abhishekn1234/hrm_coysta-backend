<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function add_offer_letters()
    {
        $user_id = auth('customer')->user()->id;
        
        $letters = DB::table('letters')->where(['staff_id' => $user_id,'type' => 'OFFER_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_offer_letters', compact('staff', 'letters'));
    }
    
    public function generate_offer_letter($letter_id)
    {
        $user_id = auth('customer')->user()->id;
        
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $user_id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'OFFER_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.document.generate_offer_letter', $data);
    }
    
    public function add_releiving_letters()
    {
        $user_id = auth('customer')->user()->id;
        
        $letters = DB::table('letters')->where(['staff_id' => $user_id,'type' => 'RELEIVING_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_releiving_letters', compact('staff', 'letters'));
    }
    
    public function generate_releiving_letter($letter_id)
    {
        $user_id = auth('customer')->user()->id;
        
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $user_id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'RELEIVING_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.document.generate_releiving_letter', $data);
    }
    
    public function add_warning_letters()
    {
        $user_id = auth('customer')->user()->id;
        
        $letters = DB::table('letters')->where(['staff_id' => $user_id,'type' => 'WARNING_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_warning_letters', compact('staff', 'letters'));
    }
    
    public function generate_warning_letter($letter_id)
    {
        $user_id = auth('customer')->user()->id;
        
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $user_id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'WARNING_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.document.generate_warning_letter', $data);
    }
    
    public function add_termination_letters()
    {
        $user_id = auth('customer')->user()->id;
        
        $letters = DB::table('letters')->where(['staff_id' => $user_id,'type' => 'TERMINATION_LETTER'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_termination_letters', compact('staff', 'letters'));
    }
    
    public function generate_termination_letter($letter_id)
    {
        $user_id = auth('customer')->user()->id;
        
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $user_id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'TERMINATION_LETTER'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.document.generate_termination_letter', $data);
    }
    
    public function add_experiences()
    {
        $user_id = auth('customer')->user()->id;
        
        $letters = DB::table('letters')->where(['staff_id' => $user_id,'type' => 'EXPERIENCE_CERTIFICATE'])->orderBy('id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_experiences', compact('staff', 'letters'));
    }
    
    public function generate_experience_certificate($letter_id)
    {
        $user_id = auth('customer')->user()->id;
        
        $hr = User::where('user_type', "HR")->first();
        $staff = User::where('id', $user_id)->first();
        $letters = DB::table('letters')->where(['id' => $letter_id,'type' => 'EXPERIENCE_CERTIFICATE'])->orderBy('id','DESC')->first();
        
        $data["staff"] = $staff;
        $data["hr"] = $hr;
        $data["letters"] = $letters;
        return view('admin-views.document.generate_experience_certificate', $data);
    }
    
    public function add_certificates()
    {
        $user_id = auth('customer')->user()->id;
        
        $certificates = DB::table('certificate_users')->leftJoin('certificates', 'certificate_users.certificate_id', '=', 'certificates.id')->where(['certificate_users.user_id' => $user_id])->orderBy('certificate_users.id','DESC')->get();
        $staff = User::where('id', $user_id)->first();
        return view('admin-views.document.add_certificates', compact('staff', 'certificates'));
    }
}