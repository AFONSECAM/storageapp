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



    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'group_id' => $request->group_id ?: null,
            'quota_limit' => $request->quota_limit ?: null
        ]);

        return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user->load('group')]);
    }



    public function update(Request $request, User $user) {
        $data = $request->only(['name','email','role','group_id','quota_limit']);
        if ($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        } 
        $user->update($data);
        
        return response()->json(['message' => 'Usuario actualizado correctamente', 'user' => $user->load('group')]);        
    }

    public function destroy(User $user) { 
        $user->delete(); 
        
        return response()->json(['message' => 'Usuario eliminado correctamente']); 
    }
}
