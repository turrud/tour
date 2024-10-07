<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Image;

use App\Models\Post;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_images_list(): void
    {
        $images = Image::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.images.index'));

        $response->assertOk()->assertSee($images[0]->path);
    }

    /**
     * @test
     */
    public function it_stores_the_image(): void
    {
        $data = Image::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.images.store'), $data);

        $this->assertDatabaseHas('images', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(route('api.images.update', $image), $data);

        $data['id'] = $image->id;

        $this->assertDatabaseHas('images', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_image(): void
    {
        $image = Image::factory()->create();

        $response = $this->deleteJson(route('api.images.destroy', $image));

        $this->assertModelMissing($image);

        $response->assertNoContent();
    }
}
