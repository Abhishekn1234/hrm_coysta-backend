<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class Point_settingController extends Controller
{
    function list(Request $request)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $point_settings = DB::table('point_settings')->get();
        return view('admin-views.point_setting.view', compact('point_settings'));
    }

    public function edit($id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $point_setting = DB::table('point_settings')->where('id', $id)->first();
        return view('admin-views.point_setting.edit',compact('point_setting'));
    }

    public function update(Request $request,$id)
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }
        
        $request->validate([
            'items' => 'required',
            'type' => 'required',
            'points' => 'required',
        ], [
            'items.required' => 'items is required!',
            'type.required' => 'type is required!',
            'points.required' => 'points is required!',
        ]);
        
        DB::table('point_settings')->where('id', $id)->update([
            'items' => $request->items,
            'type' => $request->type,
            'points' => $request->points
        ]);

        Toastr::success('point_setting updated successfully!');
        return redirect()->route('admin.point_setting.list');
    }
}