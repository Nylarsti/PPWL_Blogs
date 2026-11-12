<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $technology = $request->query('technology');
        $isAdminProjectList = $request->routeIs('post.projects.index');
        $status = $request->query('status');

        $query = Project::with('category', 'technologies');

        if (Auth::user()?->role !== 'admin') {
            $query->where('status', 'published');
        }

        $query
            ->when(is_string($search) && $search !== '', function ($query) use ($search) {
                $keyword = "%{$search}%";

                $query->where(fn ($match) => $match
                    ->where('title', 'like', $keyword)
                    ->orWhere('short_description', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword));
            })
            ->when(is_string($category) && $category !== '', function ($query) use ($category) {
                $query->whereHas('category', fn ($relation) => $relation->where('slug', $category));
            });

            $query->when(is_string($technology) && $technology !== '', function ($projects) use ($technology) {
                $projects->whereHas('technologies', function ($technologies) use ($technology) {
                    $technologies->where('technologies.slug', $technology);
                });
            });

            $query->when(
                $isAdminProjectList && in_array($status, ['draft', 'published'], true),
                fn ($projects) => $projects->where('status', $status)
            );

            switch ($request->query('sort')) {
                case 'terlama':
                    $query->oldest();
                    break;

                case 'az':
                    $query->orderBy('title');
                    break;

                case 'project_date':
                    $query->orderByDesc('project_date')->orderByDesc('id');
                    break;

                default:
                    $query->latest();
            }

        return view('portofolio.projects-index', [
            'projects' => $query->paginate(9)->withQueryString(),
            'categories' => Category::whereNotNull('slug')->orderBy('name')->get(),
            'technologies' => Technology::whereNotNull('slug')->orderBy('name')->get(),
            'isAdminProjectList' => $isAdminProjectList,
            'projectsIndexRoute' => $isAdminProjectList ? 'post.projects.index' : 'projects.index',
        ]);
    }

    public function create()
    {
        return view('post.projects.create', [
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function show(Project $project)
    {
        abort_unless(
            $project->status === 'published' || Auth::user()?->role === 'admin',
            404
        );

        $project->load(['category', 'images']);

        return view('portofolio.projects', compact('project'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'project_date' => 'nullable|date_format:Y-m-d',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096'
                . '|dimensions:min_width=400,min_height=300',
            'status' => 'required|in:draft,published',
            'short_description' => 'nullable|string|max:255',
            'project_url' => 'nullable|url:http,https|max:255',
            'repository_url' => 'nullable|url:http,https|max:255',
            'technologies' => 'nullable|array',
            'technologies.*' => 'integer|distinct|exists:technologies,id',
        ]);

        $projectData = $validated;
        $technologyIds = $projectData['technologies'] ?? [];
        unset($projectData['thumbnail'], $projectData['images'], $projectData['technologies']);

        $project = DB::transaction(function () use ($projectData, $technologyIds) {
            $project = Project::create($projectData);
            $project->technologies()->attach($technologyIds);

            return $project;
        });

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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_date' => 'nullable|date_format:Y-m-d',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'sometimes|required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                . '|dimensions:min_width=400,min_height=300',
        ]);

        $projectData = $data;
        unset($projectData['thumbnail']);
        $file = $request->file('thumbnail');

        if ($file === null) {
            $project->update($projectData);

            return redirect()->route('projects.show', $project);
        }

        $disk = Storage::disk('public');
        $newPath = $disk->putFile('projects/thumbnails', $file);

        if ($newPath === false) {
            return back()->withInput()->withErrors([
                'thumbnail' => 'Thumbnail baru gagal disimpan.',
            ]);
        }

        try {
            $oldPath = DB::transaction(function () use ($project, $projectData, $newPath) {
                $currentProject = Project::lockForUpdate()->findOrFail($project->id);
                $oldPath = $currentProject->thumbnail;

                $currentProject->update([...$projectData, 'thumbnail' => $newPath]);

                return $oldPath;
            });
        } catch (\Throwable $error) {
            $disk->delete($newPath);

            throw $error;
        }

        if ($oldPath && $disk->exists($oldPath) && !$disk->delete($oldPath)) {
            return back()->withErrors([
                'thumbnail' => 'Thumbnail baru tersimpan, tetapi file lama gagal dihapus.',
            ]);
        }

        return redirect()->route('projects.show', $project->fresh());
    }

    public function edit(Project $project)
    {
        return view('post.projects.edit', compact('project'));
    }

    public function destroy(Project $project)
    {
        $disk = Storage::disk('public');
        $paths = $project->images->pluck('image')
            ->prepend($project->thumbnail)
            ->filter()
            ->unique();

        foreach ($paths as $path) {
            if ($disk->exists($path) && !$disk->delete($path)) {
                return back()->withErrors([
                    'project' => 'File project gagal dihapus.',
                ]);
            }
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project dihapus.');
    }
}