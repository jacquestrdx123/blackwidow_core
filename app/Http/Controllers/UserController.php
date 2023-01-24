<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    public function  store(){

    }

    public function index(){
        if((Auth::user()->isAdmin())){
            $users = User::get();
            $users->load('roles');
            $users->load('departments');
            return Inertia::render('User/Index', [
                'users' => $users,
                'status' => session('status'),
            ]);
        }else{
            return Inertia::render('Unauthorised', [
                'status' => session('status'),
            ]);
        }
    }
    public function edit($id){
        $user = User::find($id);
        $departments = Department::get();
        $user_departments = array();
        $available_institutions = array();
        foreach($departments as $department){
            $available_departments[] = array(
                'id' => $department->id,
                'label' => $department->description
            );
        }
        foreach($user->departments as $department) {
            $user_departments[] = $department->id;
        }
        if((Auth::user()->isAdmin())){
            return Inertia::render('User/Edit', [
                'available_departments' => $available_departments,
                'user_departments' => $user_departments,
                'user' => $user,
                'status' => session('status'),
            ]);
        }else{
            return Inertia::render('Unauthorised', [
                'status' => session('status'),
            ]);
        }
    }

    public function update(Request $request, User $user ){
        $data = $request->all();
        $selected_departments = $data['selected_departments'] ?? [];
        unset($data['available_departments']);
        unset($data['selected_departments']);
        $user->update($data['user']);

        $user->departments()->sync($selected_departments);
        if($request->hasFile('profile_picture')){
            $fileName = $user->id.'_profile_pic.'.$request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move(public_path('user_files'), $fileName);
            $user->profile_picture = $fileName;
        }
        $user->save();
        $request->session()->flash('status', 'User updated successfully!');

        $available_departments = array();
        $user_departments = array();

        $departments = Department::get();
        foreach($departments as $department){
            $available_departments[] =array(
                'id' => $department->id,
                'label' => $department->description
            );
        }
        foreach($user->departments as $department) {
            $user_departments[] = $department->id;
        }
        return Inertia::render('User/Edit', [
            'available_departments' => $available_departments,
            'user_departments' => $user_departments,
            'user' => $user,
            'status' => session('status'),
        ]);

    }
}
