<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'category_id', 'thumbnail', 'status', 'short_description', 'project_date', 'is_featured', 'project_url', 'repository_url'];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (blank($project->slug)) {
                $baseSlug = Str::slug($project->title) ?: 'project';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $suffix++;
                }

                $project->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
