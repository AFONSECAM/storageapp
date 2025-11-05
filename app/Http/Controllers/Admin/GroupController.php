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
    

    
    public function store(Request $request) { 
        $request->validate(['name'=>'required']); 
        $group = Group::create($request->only('name','quota_limit')); 
        return response()->json(['message' => 'Grupo creado correctamente', 'group' => $group->fresh()]);
    }



    public function update(Request $request, Group $group) { 
        $group->update($request->only('name','quota_limit')); 
        return response()->json(['message' => 'Grupo actualizado correctamente', 'group' => $group->fresh()]);
    }

    public function destroy(Group $group){
        $group->delete(); 
        return response()->json(['message' => 'Grupo eliminado correctamente']);
    }
}
