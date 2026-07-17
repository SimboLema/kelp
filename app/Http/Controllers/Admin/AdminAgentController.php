<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminAgentController extends Controller
{
    public function index(){
        return view('admin.agents.index');
    }

    public function create(Request $request){
        $credentials = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|min:4|string'
        ]);

        if($credentials->fails()){
            return redirect()->route('admin.agents')->with('Validation Failed');
        }

        User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make( $request->password),
            'role' => 'agent'
        ]);

        return redirect()->route('admin.agents')->with('Agent created Successfully');
    }
}
