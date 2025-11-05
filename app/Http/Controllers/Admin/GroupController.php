<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index() { 
        $groups = Group::paginate(10);
        return view('admin.groups.index', compact('groups'));
    }
    
    public function create() { 
        return view('admin.groups.create'); 
    }
    
    public function store(Request $request) { 
        $request->validate(['name'=>'required']); 
        Group::create($request->only('name','quota_limit')); 
        return redirect()->route('admin.groups.index'); 
    }

    public function edit(Group $group) {
        return view('admin.groups.edit', compact('group')); 
    }

    public function update(Request $request, Group $group) { 
        $group->update($request->only('name','quota_limit')); 
        return redirect()->route('admin.groups.index'); 
    }

    public function destroy(Group $group){
        $group->delete(); 
        return back();
    }
}
