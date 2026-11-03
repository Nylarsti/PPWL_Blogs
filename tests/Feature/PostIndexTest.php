<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\RandomPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_random_post_seeder_creates_25_posts(): void
    {
        $this->seed(RandomPostSeeder::class);

        $this->assertDatabaseCount('posts', 25);
    }

    public function test_post_index_paginates_and_keeps_search_and_page_size_in_links(): void
    {
        foreach (range(1, 12) as $number) {
            Post::create([
                'title' => 'Laravel post ' . $number,
                'description' => 'Description ' . $number,
            ]);
        }

        $response = $this->get(route('post.index', [
            'search' => 'Laravel',
            'per_page' => 10,
            'page' => 2,
        ]));

        $response->assertOk()
            ->assertSeeText('11.')
            ->assertSeeText('12.')
            ->assertSee('search=Laravel')
            ->assertSee('per_page=10');
    }

    public function test_post_index_filters_by_title_and_accepts_supported_page_sizes(): void
    {
        foreach (range(1, 6) as $number) {
            Post::create([
                'title' => 'Matching title ' . $number,
                'description' => 'Description ' . $number,
            ]);
        }

        Post::create([
            'title' => 'Different title',
            'description' => 'Another description',
        ]);

        $response = $this->get(route('post.index', [
            'search' => 'Matching',
            'per_page' => 5,
        ]));

        $response->assertOk()
            ->assertSeeText('Matching title 1')
            ->assertDontSeeText('Matching title 6')
            ->assertDontSeeText('Different title');
    }
}
