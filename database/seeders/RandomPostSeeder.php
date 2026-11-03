<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class RandomPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 25; $i++) {
            Post::create([
                'title' => fake()->sentence(3),
                'description' => fake()->paragraph(),
            ]);
        }
    }
}
