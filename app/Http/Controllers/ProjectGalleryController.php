<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProjectGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $files = $validated['images'];
        $savedPaths = [];

        try {
            DB::transaction(function () use ($project, $files, &$savedPaths) {
                $currentProject = Project::lockForUpdate()->findOrFail($project->id);
                $total = $currentProject->images()->count() + count($files);

                if ($total > 10) {
                    throw ValidationException::withMessages([
                        'images' => 'Maksimal 10 gambar per project.',
                    ]);
                }

                foreach ($files as $file) {
                    $path = $file->store('projects/gallery', 'public');

                    if ($path === false) {
                        throw ValidationException::withMessages([
                            'images' => 'Gambar gagal disimpan.',
                        ]);
                    }

                    $savedPaths[] = $path;
                    $currentProject->images()->create(['image' => $path]);
                }
            });
        } catch (\Throwable $error) {
            if ($savedPaths) {
                Storage::disk('public')->delete($savedPaths);
            }

            throw $error;
        }

        return back()->with('success', 'Gambar berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, ProjectImage $image)
    {
        abort_if((int) $image->project_id !== (int) $project->id, 404);

        $data = $request->validate([
            'caption' => 'present|nullable|string|max:255',
        ]);

        $image->update($data);

        return back()->with('success', 'Caption berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        $disk = Storage::disk('public');
        if ($disk->exists($image->image) && !$disk->delete($image->image)) {
            return back()->withErrors([
                'images' => 'File gambar gagal dihapus.',
            ]);
        }

        $image->delete();
        return back()->with('success', 'Gambar dihapus.');
    }
}
