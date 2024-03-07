<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function addProfile(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
            ]);


            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $iconPath = $request->file('avatar')->store('avatars', 'public');
            $imageUrl = Storage::url($iconPath);

            $profile = Profile::create([
                'user_id' => auth('sanctum')->user()->id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'bio' => $request->input('bio'),
                'intro' => $request->input('intro'),
                'contact_no' => $request->input('contact_no'),
                'avatar' => $imageUrl,
            ]);



            return response()->json(['message' => 'Profile Added Successfully', 'profile' => $profile, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function updateProfile(Request $request, $id)
    {

        try {
            $profile = Profile::find($id);
            if (!$profile) {
                return response()->json(['message' => 'Profile not found', 'status' => 404], 404);
            }
            if (!$profile->user_id == auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }
            $profile->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'bio' => $request->input('bio'),
                'intro' => $request->input('intro'),
                'contact_no' => $request->input('contact_no'),
            ]);
            if ($request->has('avatar')) {
                $iconPath = $request->file('avatar')->store('avatars', 'public');
                $imageUrl = Storage::url($iconPath);
                $profile->update([
                    'avatar' => $imageUrl
                ]);
            }

            return response()->json(['message' => 'Profile Updated Successfully', 'profile' => $profile, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function deleteProfile($id)
    {
        try {
            $profile = Profile::find($id);
            if (!$profile) {
                return response()->json(['message' => 'Profile not found', 'status' => 404], 404);
            }

            if ($profile->user_id !== auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
            }

            $profile->delete();
            return response()->json(['message' => 'Profile Deleted Successfully', 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }
}
