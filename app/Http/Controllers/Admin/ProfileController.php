<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function view()
    {
        $data = User::where('id', auth('customer')->id())->first();
        return view('admin-views.profile.view', compact('data'));
    }

    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        return view('admin-views.profile.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::find($id);
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        
        $customer->place = $request->place;
        $customer->address = $request->address;
        $customer->gender = $request->gender;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->qualification = $request->qualification;
        $customer->experience = $request->experience;
        $customer->expertise = $request->expertise;
        
        $customer->bank_name = $request->bank_name;
        $customer->account_holder_name = $request->account_holder_name;
        $customer->account_number = $request->account_number;
        $customer->ifsc_code = $request->ifsc_code;
        $customer->branch = $request->branch;
        
        
        if ($request->image) {
            $customer->image = ImageManager::update('banner/', $customer->image, 'png', $request->file('image'));
        }
        $customer->save();
        Toastr::info('Profile updated successfully!');
        return back();
    }

    public function settings_password_update(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        $customer = User::find(auth('customer')->id());
        $customer->password = bcrypt($request['password']);
        $customer->save();
        Toastr::success('User password updated successfully!');
        return back();
    }

}
