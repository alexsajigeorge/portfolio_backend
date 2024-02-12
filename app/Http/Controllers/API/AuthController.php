<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{



    // Register Controller
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['user' => $user, 'auth_token' => $token], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 500]);
        }
    }

    // Login Controller
    public function login(Request $request)
    {
        try {
            $validator =  Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                return response()->json(['user' => $user, 'auth_token' => $token], 200);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 500]);
        }
    }
}
