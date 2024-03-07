<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

            return response()->json(['user' => $user, 'authToken' => $token], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 500]);
        }
    }

    public function getAuthUser()
    {
        try {
            $user = User::where('id', auth('sanctum')->user()->id)->get();
            return response()->json(['user' => $user], 200);
        } catch (\Throwable $th) {
            //throw $th;
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
                return response()->json(['user' => $user, 'authToken' => $token], 200);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 500]);
        }
    }

    public function logout()
    {
        try {
            Auth::guard("web")->logout();
            Session::flush();
            return response()->json(['message' => 'logged out successfully'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 500]);
        }
    }
}
