<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocialLinkController extends Controller
{
    public function getSocialLinks()
    {
        try {
            $social = SocialLink::all();
            return response()->json(['social' => $social,  'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function addSocialLink(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'platform_name' => 'required',
                'platform_url' => 'required',
                'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $socialImage = $request->file('icon')->store('social', 'public');
            $imageUrl = Storage::url($socialImage);

            $social = SocialLink::create([
                'user_id' => auth('sanctum')->user()->id,
                'platform_name' => $request->input('platform_name'),
                'platform_url' => $request->input('platform_url'),
                'icon' => $imageUrl,
            ]);

            return response()->json(['message' => 'Social Link Added Successfully', 'social' => $social, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function updateSocialLink(Request $request, $id)
    {
        try {

            $validation = Validator::make($request->all(), [
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }
            $social = SocialLink::find($id);

            if (!$social) {
                return response()->json(['message' => 'Social Link Not Found', 'status' => 404], status: 404);
            }

            if ($social->user_id != auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            if ($request->has('platform_name')) {
                $social->update([
                    'platform_name' => $request->input('platform_name'),
                    'platform_url' => $request->input('platform_url'),
                ]);
            }

            if ($request->hasFile('icon')) {
                $socialImage = $request->file('icon')->store('social', 'public');
                $imageUrl = Storage::url($socialImage);
                $social->update([
                    'icon' => $imageUrl,
                ]);
            }
            return response()->json(['message' => 'Social Link Updated Successfully', 'social' => $social, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function deleteSocialLink($id)
    {
        try {
            $social = SocialLink::find($id);
            if (!$social) {
                return response()->json(['message' => 'Social Link Not Found', 'status' => 404], status: 404);
            }
            if ($social->user_id != auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            $social->delete();
            return response()->json(['message' => 'Social Link Deleted Successfully', 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }
}
