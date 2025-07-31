<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $query_param = [];
        $search = $request['search'];
        $filter_gender = $request['filter_gender'];
        
        if ($request->has('search')) {
            $clients = User::where('user_type', '=' , 'CLIENT')->where(function ($q) use ($search) {
                $q->Where('name', 'like', "%{$search}%");
            });
        } else {
            $clients = User::where('user_type', '=' , 'CLIENT');
        }
        
        if ($request->has('filter_gender') && $request['filter_gender'] != '') {
            $clients = $clients->where(['gender' => $request['filter_gender']]);
        }
        
        $query_param = ['search' => $request['search'],'filter_gender' => $request['filter_gender']];
        
        $counts = $clients;
        $clients = $clients->orderBy('id', 'ASC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.client.view', compact('clients','search','filter_gender','counts'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'place' => 'required',
            'address' => 'required',
            'gender' => 'required',
        ], [
            'name.required' => 'name is required!',
            'email.required' => 'email is required!',
            'password.required' => 'password is required!',
            'phone.required' => 'phone is required!',
            'place.required' => 'place is required!',
            'address.required' => 'address is required!',
            'gender.required' => 'gender is required!',
        ]);

        $client = new User;
        $client->user_type = 'CLIENT';
        $client->name = $request->name;
        $client->email = $request->email;
        $client->password = bcrypt($request->password);
        $client->phone = $request->phone;
        $client->place = $request->place;
        $client->address = $request->address;
        $client->gender = $request->gender;
        $client->date_of_birth = $request->date_of_birth;
        $client->qualification = $request->qualification;
        $client->experience = $request->experience;
        $client->expertise = $request->expertise;
        
        if($request->file('image')) {
            $client->image = ImageManager::upload('banner/', 'png', $request->file('image'));
        }
        
        $client->save();
        
        Toastr::success('client added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $client = User::find($request->id);
            $client->status = $request->status;
            $client->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $client = User::where('id', $id)->first();
        return view('admin-views.client.edit',compact('client'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'place' => 'required',
            'address' => 'required',
            'gender' => 'required',
        ], [
            'name.required' => 'name is required!',
            'email.required' => 'email is required!',
            'phone.required' => 'phone is required!',
            'place.required' => 'place is required!',
            'address.required' => 'address is required!',
            'gender.required' => 'gender is required!',
        ]);

        $client = User::find($id);
        $client->user_type = 'CLIENT';
        $client->name = $request->name;
        $client->email = $request->email;
        
        if ($request['password']) {
            $client->password = bcrypt($request->password);
        }
        
        $client->phone = $request->phone;
        $client->place = $request->place;
        $client->address = $request->address;
        $client->gender = $request->gender;
        $client->date_of_birth = $request->date_of_birth;
        $client->qualification = $request->qualification;
        $client->experience = $request->experience;
        $client->expertise = $request->expertise;
        
        if($request->file('image')) {
            $client->photo = ImageManager::update('banner/', $client['image'], 'png', $request->file('image'));
        }
        
        $client->save();

        Toastr::success('client updated successfully!');
        return redirect()->route('admin.client.list');
    }
    
    public function view(Request $request, $id)
    {
        $client = User::find($id);
        if (isset($client)) {
            return view('admin-views.client.client-view', compact('client'));
        }
        Toastr::error('client not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = User::find($request->id);
        $br->delete();
        return response()->json();
    }
    
    public function send_whatsapp(Request $request)
    {
        $id = $request->multi_clients_id;
        $clientIds = explode(',', $id);
        $clientCount = count($clientIds);
    
        $numbers = [];
        $names = [];
        foreach ($clientIds as $clientId) {
            $client = User::find($clientId);
            if ($client && $client->phone) {
                // Add the phone number to the numbers array
                $numbers[] = $client->phone;
                $names[] = $client->name;
            }
        }
        
        // $numbers = [9747625648,9747627106];

        return response()->json(['numbers' => $numbers,'names' => $names]);
    }
    
    public function send_email(Request $request)
    {
        $id = $request->multi_clients_id;
        $bulk_message = $request->bulk_message;
        
        $clientIds = explode(',', $id);
        $clientCount = count($clientIds);
        
        $emails = [];
        foreach ($clientIds as $clientId) {
            $client = User::find($clientId);
            if ($client && $client->email) {
                $emails[] = $client->email;
                
                // $test = 'rishikeshr850@gmail.com';
                
                $msg['name'] = $client->name;
                $msg['message'] = $request->bulk_message;
                Mail::to($client->email)->send(new \App\Mail\Bulkmessage($msg));
            }
        }

        return response()->json(['emails' => $emails]);
    }
}