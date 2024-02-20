<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getProjects()
    {
        try {
            $projects = Project::all();
            return response()->json(['projects' => $projects, 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Something went wrong', 'status' => 500], status: 500);
        }
    }
}
