<?php

namespace App\Http\Controllers\Admin\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Model\Candidate;
use App\Model\Job;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Model\Admin;
use App\User;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout','candidate_registration','candidate_registration_submit','personality_test','personality_test_submit']]);
    }
    
    public function captcha($tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if(Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function login()
    {
        return view('admin-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:6'
        ]);
        
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                            }
                        },
                    ],
                ]);
            } catch (\Exception $exception) {
            }
        } else {
            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
                Session::forget('default_captcha_code');
                return back()->withErrors(\App\CPU\translate('Captcha Failed'));
            }
        }

        $user = User::where('email', $request->email)->first();
        if (isset($user) && $user->status != 1) {
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['You are blocked!!, contact with admin.']);
        } else {
            if (auth('customer')->attempt(['email' => $request->email, 'password' => $request->password], 1)) {
                return redirect()->route('admin.dashboard');
            }
            
            // Mail::to($user->email)->send(new \App\Mail\EmailVerification('1111'));
            // $response = 'check_your_email';
            // Toastr::success($response);
            
            // return redirect(route('admin.auth.check', [$user->id]));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['Credentials does not match.']);
    }
    
    public function candidate_registration()
    {
        $job = Job::get();
        return view('admin-views.auth.candidate_registration', compact('job'));
    }

    public function candidate_registration_submit(Request $request)
    {
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
            'resume' => 'required',
            'tenth_mark_percentage' => 'required',
            'twelveth_mark_percentage' => 'required',
            'degree_mark_percentage' => 'required',
            'last_qualification_certificate' => 'required',
            'portfolio_link' => 'required',
            'strengths' => 'required',
            'weaknesses' => 'required',
            'goals' => 'required',
            'willingness_to_relocate' => 'required',
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
            'resume.required' => 'resume is required!',
            'tenth_mark_percentage.required' => 'tenth_mark_percentage is required!',
            'twelveth_mark_percentage.required' => 'twelveth_mark_percentage is required!',
            'degree_mark_percentage.required' => 'degree_mark_percentage is required!',
            'last_qualification_certificate.required' => 'last_qualification_certificate is required!',
            'portfolio_link.required' => 'portfolio_link is required!',
            'strengths.required' => 'strengths is required!',
            'weaknesses.required' => 'weaknesses is required!',
            'goals.required' => 'goals is required!',
            'willingness_to_relocate.required' => 'willingness_to_relocate is required!',
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
        $candidate->strengths = $request->strengths;
        $candidate->weaknesses = $request->weaknesses;
        $candidate->goals = $request->goals;
        $candidate->willingness_to_relocate = $request->willingness_to_relocate;
        
        if($request->file('resume')) {
            $candidate->resume = ImageManager::upload('banner/', 'pdf', $request->file('resume'));
        }
        
        if($request->file('last_qualification_certificate')) {
            $candidate->last_qualification_certificate = ImageManager::upload('banner/', 'pdf', $request->file('last_qualification_certificate'));
        }
        
        $candidate->save();
        
        Toastr::success('candidate Registered successfully!');
        // return back();
        return redirect(route('admin.auth.personality_test', [$candidate->id]));
    }
    
    public function personality_test($id)
    {
        $candidate = Candidate::where(['candidates.id' => $id])->first();
        if (isset($candidate)) {
            if($candidate->is_test_done == 0){
            return view('admin-views.auth.personality_test', compact('candidate'));
            } else {
                Toastr::success('Test Completed successfully. We will contact you...');        
                return redirect(route('admin.auth.candidate_registration'));
            }
        }
        
        Toastr::error('Candidate does not exists');
        return redirect(route('admin.auth.candidate_registration'));
    }
    
    public function personality_test_submit(Request $request)
    {
        $candidate = Candidate::find($request->id);
        $candidate->result_type = $request->result_type;
        $candidate->result_typeindex = $request->result_typeindex;
        $candidate->result_highestScore = $request->result_highestScore;
        $candidate->is_test_done = 1;
        $candidate->save();
        
        return response()->json(['candidate' => $candidate]);
        
        // Toastr::error('Test Completed successfully. We will contact you...');
        // return redirect(route('admin.auth.candidate_registration'));
    }
    
    public static function check($id)
    {
        $user = User::find($id);
        
        DB::table('otp_verifications')->where(['email' => $user->email])->delete();

        $otp = rand(1000, 9999);
        DB::table('otp_verifications')->insert([
            'email' => $user->email,
            'otp' => $otp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\EmailVerification($otp));
            $response = 'check_your_email';
            Toastr::success($response);
        } catch (\Exception $exception) {
            $response = 'email_failed';
            Toastr::error($response);
        }

        return view('admin-views.auth.verify', compact('user'));
    }
    
    public static function verify(Request $request)
    {
        Validator::make($request->all(), [
            'otp' => 'required',
            'password' => 'required',
        ]);

        $user = User::find($request->id);
        $verify = DB::table('otp_verifications')->where(['email' => $user->email, 'otp' => $request['otp']])->first();

        if (isset($verify)) {
            try {
                DB::table('otp_verifications')->where(['email' => $user->email, 'otp' => $request['otp']])->delete();
            } catch (\Exception $exception) {
                Toastr::info('Try again');
            }
            
            if (auth('customer')->attempt(['email' => $user->email, 'password' => $request->password], 1)) {
                return redirect()->route('admin.dashboard');
            }
    
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([$a]);

        } else {
            Toastr::error('Verification_code_or_OTP mismatched');
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.auth.login');
    }
   public function apiLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    $user = Auth::user();

    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'user' => $user,
        'token' => $user->createToken('admin-token')->accessToken,
    ]);
}
}