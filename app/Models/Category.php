<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug) && filled($category->name)) {
                $baseSlug = Str::slug($category->name);
                $slug = $baseSlug;
                $suffix = 2;

                while (
                    static::where('slug', $slug)
                        ->where('id', '!=', $category->id)
                        ->exists()
                ) {
                    $slug = $baseSlug . '-' . $suffix++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
