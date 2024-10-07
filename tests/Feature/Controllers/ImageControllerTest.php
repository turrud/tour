<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Image;

use App\Models\Post;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_images(): void
    {
        $images = Image::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('images.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.images.index')
            ->assertViewHas('images');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_image(): void
    {
        $response = $this->get(route('images.create'));

        $response->assertOk()->assertViewIs('app.images.create');
    }

    /**
     * @test
     */
    public function it_stores_the_image(): void
    {
        $data = Image::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('images.store'), $data);

        $this->assertDatabaseHas('images', $data);

        $image = Image::latest('id')->first();

        $response->assertRedirect(route('images.edit', $image));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_image(): void
    {
        $image = Image::factory()->create();

        $response = $this->get(route('images.show', $image));

        $response
            ->assertOk()
            ->assertViewIs('app.images.show')
            ->assertViewHas('image');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_image(): void
    {
        $image = Image::factory()->create();

        $response = $this->get(route('images.edit', $image));

        $response
            ->assertOk()
            ->assertViewIs('app.images.edit')
            ->assertViewHas('image');
    }

    /**
     * @test
     */
    public function it_updates_the_image(): void
    {
        $image = Image::factory()->create();

        $post = Post::factory()->create();

        $data = [
            'path' => $this->faker->imageUrl(),
            'post_id' => $post->id,
        ];

        $response = $this->put(route('images.update', $image), $data);

        $data['id'] = $image->id;

        $this->assertDatabaseHas('images', $data);

        $response->assertRedirect(route('images.edit', $image));
    }

    /**
     * @test
     */
    public function it_deletes_the_image(): void
    {
        $image = Image::factory()->create();

        $response = $this->delete(route('images.destroy', $image));

        $response->assertRedirect(route('images.index'));

        $this->assertModelMissing($image);
    }
}
