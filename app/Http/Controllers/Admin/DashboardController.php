<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\File;
use App\Models\Group;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalFiles = File::count();
        $totalGroups = Group::count();
        $totalStorage = File::sum('size');
        
        return view('admin.dashboard', compact('totalUsers', 'totalFiles', 'totalGroups', 'totalStorage'));
    }
}