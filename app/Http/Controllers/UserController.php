<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    public function  store(){

    }

    public function update(Request $request, User $user ){
        $data = $request->all();
        $selected_institutions = $data['selected_institutions'];
        $new_institutions = array();
        unset($data['user']);
        unset($data['available_institutions']);
        unset($data['selected_institutions']);
        $user->update($data);
        $user->save();
        die();
        foreach($selected_institutions as $institution){
            $new_institutions[] = new Institution(['institution_id' => $institution,'user_id' => $user->id]);
        }
        $user->institutions()->updateOrCreate($new_institutions);
        $request->session()->flash('status', 'User updated successfully!');
        $available_institutions = [];


        $institutions = Institution::get();
        foreach($institutions as $institution){
            $available_institutions[] =array(
                'id' => $institution->id,
                'label' => $institution->description
            );
        }
        foreach($user->institutions as $institution) {
            $user_institutions[] = $institution->id;
        }
        return Inertia::render('Admin/UserEdit', [
            'available_institutions' => $available_institutions,
            'user_institutions' => $user_institutions,
            'user' => $user,
            'status' => session('status'),
        ]);

    }
}
