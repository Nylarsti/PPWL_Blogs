<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectPortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_status_cards_and_top_skills_are_consistent(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Project::create(['title' => 'Published project', 'status' => 'published']);
        Project::create(['title' => 'Draft project', 'status' => 'draft']);
        Skill::create(['name' => 'Laravel', 'proficiency' => 90]);
        Skill::create(['name' => 'CSS', 'proficiency' => 70]);

        $response = $this->actingAs($admin)->get(route('post.dashboard'));

        $response->assertOk()
            ->assertSeeText('Published')
            ->assertSeeText('Draft')
            ->assertSeeText('Laravel');

        $this->assertSame(
            Project::count(),
            Project::where('status', 'published')->count()
                + Project::where('status', 'draft')->count()
        );
    }

    public function test_public_project_pages_use_slug_and_hide_drafts(): void
    {
        $published = Project::create([
            'title' => 'My First Project',
            'status' => 'published',
        ]);
        $draft = Project::create([
            'title' => 'Private Draft',
            'status' => 'draft',
        ]);

        $this->assertSame('my-first-project', $published->slug);

        $this->get(route('projects.index'))
            ->assertOk()
            ->assertSeeText('My First Project')
            ->assertDontSeeText('Private Draft');

        $this->get(route('projects.show', $published->slug))
            ->assertOk()
            ->assertSeeText('Gallery');

        $this->get(route('projects.show', $draft->slug))
            ->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('projects.index'))
            ->assertOk()
            ->assertSeeText('Private Draft')
            ->assertSeeText('Edit project');
    }

    public function test_experience_and_education_pages_are_public(): void
    {
        $this->get(route('experience.index'))->assertOk();
        $this->get(route('education.index'))->assertOk();
    }

    public function test_thumbnail_smaller_than_minimum_dimensions_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('post.projects.create'))
            ->post(route('post.projects.store'), [
                'title' => 'Too Small',
                'status' => 'draft',
                'thumbnail' => $this->fakePngImage('small.png', 200, 200),
            ])
            ->assertSessionHasErrors('thumbnail');

        $this->assertDatabaseMissing('projects', ['title' => 'Too Small']);
    }

    public function test_gallery_rejects_an_eleventh_image_for_a_project(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::create(['title' => 'Gallery Project', 'status' => 'published']);

        foreach (range(1, 10) as $number) {
            ProjectImage::create([
                'project_id' => $project->id,
                'image' => "projects/gallery/{$number}.jpg",
            ]);
        }

        $this->actingAs($admin)
            ->from(route('post.projects.edit', $project))
            ->post(route('post.projects.gallery.store', $project), [
                'images' => [$this->fakePngImage('eleventh.png', 400, 300)],
            ])
            ->assertSessionHasErrors('images');

        $this->assertDatabaseCount('project_images', 10);
    }

    public function test_replacing_project_thumbnail_deletes_the_old_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $oldPath = 'projects/thumbnails/old.jpg';
        Storage::disk('public')->put($oldPath, 'old image');
        $project = Project::create([
            'title' => 'Replace Thumbnail',
            'thumbnail' => $oldPath,
            'status' => 'published',
        ]);

        $this->actingAs($admin)
            ->put(route('post.projects.update', $project), [
                'title' => $project->title,
                'status' => 'published',
                'thumbnail' => $this->fakePngImage('new.png', 400, 300),
            ])
            ->assertRedirect(route('projects.show', $project->fresh()));

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($project->fresh()->thumbnail);
    }

    public function test_gallery_caption_can_be_updated_and_gallery_renders_on_slug_detail(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::create(['title' => 'Gallery Detail', 'status' => 'published']);
        $image = ProjectImage::create([
            'project_id' => $project->id,
            'image' => 'projects/gallery/example.jpg',
        ]);

        $this->actingAs($admin)
            ->put(route('post.projects.gallery.update', [$project, $image]), [
                'caption' => 'Screenshot utama',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('Screenshot utama', $image->fresh()->caption);
        $this->get(route('projects.show', $project->slug))
            ->assertOk()
            ->assertSeeText('Screenshot utama');
    }

    public function test_category_dashboard_uses_project_counts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Web']);
        Project::create(['title' => 'Web project', 'category_id' => $category->id, 'status' => 'published']);

        $this->actingAs($admin)
            ->get(route('post.dashboard'))
            ->assertOk()
            ->assertSeeText('Web')
            ->assertSeeText('1 project');
    }

    public function test_profile_photo_replacement_deletes_the_old_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['photo' => 'profiles/old.png']);
        Storage::disk('public')->put($user->photo, 'old photo');

        $this->actingAs($user)
            ->patch(route('profile.photo.update'), [
                'photo' => $this->fakePngImage('new.png', 200, 200),
            ])
            ->assertRedirect(route('profile.edit'));

        Storage::disk('public')->assertMissing('profiles/old.png');
        Storage::disk('public')->assertExists($user->fresh()->photo);
    }

    public function test_profile_photo_smaller_than_minimum_dimensions_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->patch(route('profile.photo.update'), [
                'photo' => $this->fakePngImage('small.png', 199, 200),
            ])
            ->assertSessionHasErrors('photo');

        $this->assertNull($user->fresh()->photo);
    }

    public function test_deleting_gallery_image_removes_file_and_database_row(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::create(['title' => 'Delete Gallery', 'status' => 'published']);
        $path = 'projects/gallery/delete-me.png';
        Storage::disk('public')->put($path, 'image');
        $image = ProjectImage::create([
            'project_id' => $project->id,
            'image' => $path,
        ]);

        $this->actingAs($admin)
            ->delete(route('post.projects.gallery.destroy', [$project, $image]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($path);
    }

    private function fakePngImage(string $name, int $width, int $height): UploadedFile
    {
        $chunk = static function (string $type, string $data): string {
            return pack('N', strlen($data))
                . $type
                . $data
                . pack('N', crc32($type . $data));
        };

        $header = pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0);
        $row = "\0" . str_repeat("\0\0\0", $width);
        $imageData = gzcompress(str_repeat($row, $height));
        $png = "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', $header)
            . $chunk('IDAT', $imageData)
            . $chunk('IEND', '');

        $file = UploadedFile::fake()->createWithContent($name, $png);
        $file->mimeTypeToReport = 'image/png';

        return $file;
    }
}
