<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::with('group')->paginate(10);
        $groups = Group::all();
        return view('admin.users.index', compact('users', 'groups'));
    }

    public function create(){
        $groups = Group::all();
        return view('admin.users.create', compact('groups'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'group_id' => $request->group_id ?: null,
            'quota_limit' => $request->quota_limit ?: null
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user){
        $groups = Group::all();
        return view('admin.users.edit', compact('user','groups'));
    }

    public function update(Request $request, User $user) {
        $data = $request->only(['name','email','role','group_id','quota_limit']);
        if ($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        } 
        $user->update($data);
        return redirect()->route('admin.users.index');        
    }

    public function destroy(User $user) { 
        $user->delete(); 
        return back(); 
    }
}
