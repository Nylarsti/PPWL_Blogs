<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return view('portofolio.projects-index', [
            'projects' => Project::with('category')->latest()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('post.projects.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Project $project)
    {
        $project->load(['category', 'images']);

        return view('portofolio.projects', compact('project'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096'
                . '|dimensions:min_width=400,min_height=300',
        ]);

        $projectData = $validated;
        unset($projectData['thumbnail'], $projectData['images']);
        $project = Project::create($projectData);

        if ($request->hasFile('thumbnail')) {
            $project->update([
                'thumbnail' => $request->file('thumbnail')
                    ->store('projects/thumbnails', 'public'),
            ]);
        }

        foreach ($request->file('images', []) as $index => $file) {
            $project->images()->create([
                'image' => $file->store('projects/gallery', 'public'),
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('post.projects.create')
            ->with('success', 'Project berhasil disimpan.');
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('projects/thumbnails', 'public');
        }

        $project->update($validated);
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
        Storage::disk('public')->delete($project->thumbnail);
        }
        foreach ($project->images as $image) {
        Storage::disk('public')->delete($image->image);
        }
        $project->technologies()->detach();
        $project->delete(); // baris project_images ikut hilang (cascadeOnDelete)
        return redirect()->route('post.projects.index')->with('success', 'Project dihapus.');
    }
}