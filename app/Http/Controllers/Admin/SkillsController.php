<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    public function getSkills()
    {
        try {
            $skills = Skill::all();
            return response()->json(['skills' => $skills], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }


    public function addSkill(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'skill_name' => 'required',
                'description' => 'required',
                'proficency_lvl' => 'required',
                'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $iconPath = $request->file('icon')->store('icons', 'public');

            $skills = Skill::create([
                'user_id' => auth('sanctum')->user()->id,
                'skill_name' => $request->input('skill_name'),
                'description' => $request->input('description'),
                'proficency_lvl' => $request->input('proficency_lvl'),
                'icon' => $iconPath,
            ]);

            return response()->json(['message' => 'Skill Added Successfully', 'skills' => $skills, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function updateSkill(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'skill_name' => 'required',
                'description' => 'required',
                'proficency_lvl' => 'required',
                'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $skills = Skill::find($id);
            if (!$skills) {
                return response()->json(['message' => 'Skill not found', 'status' => 404], 404);
            }

            if ($skills->user_id !== auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            if ($request->has('skill_name')) {
                $skills->update([
                    'skill_name' => $request->input('skill_name'),
                    'description' => $request->input('description'),
                    'proficency_lvl' => $request->input('proficency_lvl'),
                ]);
            }

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $skills->update([
                    'icon' => $iconPath,
                ]);
            }

            return response()->json(['message' => 'Skills Updated Successfully', 'skills' => $skills], status: 200);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function deleteSkill($id)
    {
        try {
            $skills = Skill::find($id);
            if (!$skills) {
                return response()->json(['message' => 'Skill not found', 'status' => 404], 404);
            }

            if ($skills->user_id !== auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            $skills->delete();

            return response()->json(['message' => 'Skills Deleted Successfully', 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
