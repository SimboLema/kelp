<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AgentLoginController extends Controller
{
    public function login(Request $request){
        
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Failed'
            ]);
        }

        $agent = User::query()->where('phone_number', $request->phone_number)->first();
        Log::info('Login attempt', [
            'incoming_phone' => $request->phone_number,
            'matched_user' => User::query()->where('phone_number', $request->phone_number)->first(),
        ]);
    
    if (!$agent || !Hash::check($request->password, $agent->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials, please try again.'
        ], 401);
    }

    
    $token = $agent->createToken('agent-auth-token')->plainTextToken;

    
    return response()->json([
        'success' => true,
        'token' => $token,
        'agent' => [
            'id' => $agent->id,
            'name' => $agent->name,
            'phone_number' => $agent->phone_number,
        ]
    ], 200);
    }
}
