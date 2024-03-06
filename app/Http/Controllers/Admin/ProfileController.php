<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getProfile()
    {
        try {
            $profile = Profile::all();
            return response()->json(['profile' => $profile, 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function AddProfile(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
            ]);


            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $imagePath = null;
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $imageName = time() . $image->getClientOriginalName();

                $storedImagePath = $image->storeAs('public/avatars', $imageName);
                $imagePath = 'avatars/' . $imageName;
            }

            $profile = Profile::create([
                'user_id' => auth('sanctum')->user()->id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'bio' => $request->input('bio'),
                'intro' => $request->input('intro'),
                'contact_no' => $request->input('contact_no'),
                'avatar' => $imagePath,
            ]);

            return response()->json(['message' => 'Profile Added Successfully', 'profile' => $profile, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }
}
