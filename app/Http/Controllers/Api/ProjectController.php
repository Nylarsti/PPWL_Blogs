<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Mengambil semua project
   
    public function index(Request $request)
    {
        $projects = Project::with(['category', 'technologies'])
            ->where('status', 'published')

            // Search berdasarkan judul dan deskripsi
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
                });
            })

            // Filter berdasarkan slug kategori
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            })

            ->latest()
            ->paginate(9)
            ->withQueryString();

        return ProjectResource::collection($projects);
    }


    // Mengambil detail satu project
    public function show(Project $project)
    {
        if ($project->status !== 'published') {
            abort(404);
        }

        return new ProjectResource(
            $project->load(['category', 'technologies'])
        );
    }
}