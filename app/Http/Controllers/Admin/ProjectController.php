<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function getProjects()
    {
        try {
            $projects = Project::with('skills')->get();
            return response()->json(['projects' => $projects,  'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }

    public function addProject(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'img_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'skills' => 'required', // Assuming the skills come in an array from the dropdown
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }

            $projectImage = $request->file('img_url')->store('projects', 'public');
            $imageUrl = Storage::url($projectImage);

            $projects = Project::create([
                'user_id' => auth('sanctum')->user()->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'project_url' => $request->input('project_url'),
                'github_url' => $request->input('github_url'),
                'img_url' => $imageUrl,
            ]);

            // Retrieve the Skill Instances based on the selected skill IDs
            $selectedSkills = Skill::find($request->input('skills'));

            // Attach Skills to the Project
            $projects->skills()->attach($selectedSkills);


            return response()->json(['message' => 'Project Added Successfully', 'projects' => $projects, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function updateProject(Request $request, $id)
    {
        try {
            $projects = Project::find($id);
            if (!$projects) {
                return response()->json(['message' => 'Project not fund', 'status' => 404], 404);
            }
            if ($projects->user_id !== auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            $projects->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'project_url' => $request->input('project_url'),
                'github_url' => $request->input('github_url'),
            ]);

            if ($request->hasFile('img_url')) {
                $projectImage = $request->file('img_url')->store('projects', 'public');
                $imageUrl = Storage::url($projectImage);
                $projects->update([
                    'img_url' => $imageUrl,
                ]);
            }

            return response()->json(['message' => 'Project Updated Successfully', 'projects' => $projects, 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function deleteProject($id)
    {
        try {
            $projects = Project::find($id);
            if (!$projects) {
                return response()->json(['message' => 'Project not fund', 'status' => 404], 404);
            }
            if ($projects->user_id !== auth('sanctum')->user()->id) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401], status: 401);
            }

            $projects->delete();
            return response()->json(['message' => 'Project Deleted Successfully', 'status' => 200], status: 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }
}
