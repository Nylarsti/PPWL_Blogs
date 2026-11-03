<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_index_links_to_the_correct_detail_route(): void
    {
        $post = Post::create([
            'title' => 'Judul detail',
            'description' => 'Isi deskripsi detail',
        ]);

        $response = $this->get(route('post.index'));

        $response->assertOk()
            ->assertSee(route('post.show', $post->id));

        $detailResponse = $this->get(route('post.show', $post->id));

        $detailResponse->assertOk()
            ->assertSeeText('Judul detail')
            ->assertSeeText('Isi deskripsi detail');
    }
}
