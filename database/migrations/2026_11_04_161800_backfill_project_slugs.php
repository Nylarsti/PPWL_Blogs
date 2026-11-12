<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('projects')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->orderBy('id')
            ->get(['id', 'title'])
            ->each(function (object $project): void {
                $baseSlug = Str::slug($project->title) ?: 'project';
                $slug = $baseSlug;
                $suffix = 2;

                while (DB::table('projects')
                    ->where('slug', $slug)
                    ->where('id', '!=', $project->id)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $suffix++;
                }

                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['slug' => $slug]);
            });
    }

    public function down(): void
    {
        // Existing slugs must remain intact when rolling back data migrations.
    }
};
