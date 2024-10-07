<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Image;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostImagesTest extends TestCase
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
    public function it_gets_post_images(): void
    {
        $post = Post::factory()->create();
        $images = Image::factory()
            ->count(2)
            ->create([
                'post_id' => $post->id,
            ]);

        $response = $this->getJson(route('api.posts.images.index', $post));

        $response->assertOk()->assertSee($images[0]->path);
    }

    /**
     * @test
     */
    public function it_stores_the_post_images(): void
    {
        $post = Post::factory()->create();
        $data = Image::factory()
            ->make([
                'post_id' => $post->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.posts.images.store', $post),
            $data
        );

        $this->assertDatabaseHas('images', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $image = Image::latest('id')->first();

        $this->assertEquals($post->id, $image->post_id);
    }
}
