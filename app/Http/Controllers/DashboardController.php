<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Category;
use App\Models\Technology;
use App\Models\Skill;


class DashboardController extends Controller
{
    public function index()
    {
        return view('post.dashboard.index', [
            'totalProjects' => Project::count(),
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
        ]);
    }
}
