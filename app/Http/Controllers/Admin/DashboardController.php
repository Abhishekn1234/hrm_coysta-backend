<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (auth('customer')->user() == NULL) {
            return redirect()->route('admin.auth.logout');
        }

        $data['Project'] = Project::count();
        $data['Staff'] = User::where('user_type', '!=' ,'ADMIN')->count();
        
        return view('admin-views.system.dashboard', compact('data'));
    }
}