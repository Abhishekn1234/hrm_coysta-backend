<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Certificate;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
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
            $certificates = Certificate::where('certificate_name', '!=' , '')->where(function ($q) use ($search) {
                $q->Where('certificate_name', 'like', "%{$search}%");
            });
        } else {
            $certificates = Certificate::where('certificate_name', '!=' , '');
        }
        
        $query_param = ['search' => $request['search']];
        
        $counts = $certificates;
        $certificates = $certificates->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.certificate.view', compact('certificates','search','counts'));
    }

    public function store(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'certificate_name' => 'required',
            'certificate_description' => 'required',
        ], [
            'certificate_name.required' => 'certificate_name is required!',
            'certificate_description.required' => 'certificate_description is required!',
        ]);

        $certificate = new Certificate;
        $certificate->certificate_name = $request->certificate_name;
        $certificate->certificate_description = $request->certificate_description;
        $certificate->save();
        
        Toastr::success('certificate added successfully!');
        return back();
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
    
        $certificate = Certificate::where('id', $id)->first();
        return view('admin-views.certificate.edit',compact('certificate'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'certificate_name' => 'required',
            'certificate_description' => 'required',
        ], [
            'certificate_name.required' => 'certificate_name is required!',
            'certificate_description.required' => 'certificate_description is required!',
        ]);

        $certificate = Certificate::find($id);
        $certificate->certificate_name = $request->certificate_name;
        $certificate->certificate_description = $request->certificate_description;
        $certificate->save();

        Toastr::success('certificate updated successfully!');
        return redirect()->route('admin.certificate.list');
    }
    
    public function view(Request $request, $id)
    {
        $certificate = Certificate::find($id);
        if (isset($certificate)) {
            return view('admin-views.certificate.certificate-view', compact('certificate'));
        }
        Toastr::error('certificate not found!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Certificate::find($request->id);
        $br->delete();
        return response()->json();
    }
}