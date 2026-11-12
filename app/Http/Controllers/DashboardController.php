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
            'categories' => Category::withCount('projects')->orderBy('name')->get(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'topSkills' => Skill::orderByDesc('proficiency')->orderBy('name')->take(5)->get(),
            'latestProjects' => Project::with('category')->latest()->take(5)->get(),
            'publishedProjects' => Project::where('status', 'published')->count(),
            'draftProjects' => Project::where('status', 'draft')->count(),
        ]);
    }
}
