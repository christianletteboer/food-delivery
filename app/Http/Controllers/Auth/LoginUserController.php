<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidationException;
use App\Models\User;

class LoginUserController extends Controller
{
    //
    public function login(Request $request){
        try{

            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);

            $credentials = request(['email', 'password']);
            
            if(!Auth::attempt($credentials)){
                return response()->json([
                    'status_code' => 422,
                    'message' =>'Unauthorized',
                    ]);
                }
                
            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, [])){
                return response()->json([
                    'status_code' => 422,
                    'message' =>' Password does not Match',
                ]);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'acces_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);

        }catch (Exception $error){

            return response()->json([
                'status_code' => 500,
                'message' =>'Error Login',
                'error' => $error,
            ]);
        }
    }

}
