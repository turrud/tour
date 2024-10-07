<?php

namespace Tests\Feature\Api;

use App\Models\Tag;
use App\Models\User;
use App\Models\Post;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagPostsTest extends TestCase
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
    public function it_gets_tag_posts(): void
    {
        $tag = Tag::factory()->create();
        $post = Post::factory()->create();

        $tag->posts()->attach($post);

        $response = $this->getJson(route('api.tags.posts.index', $tag));

        $response->assertOk()->assertSee($post->name);
    }

    /**
     * @test
     */
    public function it_can_attach_posts_to_tag(): void
    {
        $tag = Tag::factory()->create();
        $post = Post::factory()->create();

        $response = $this->postJson(
            route('api.tags.posts.store', [$tag, $post])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $tag
                ->posts()
                ->where('posts.id', $post->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_posts_from_tag(): void
    {
        $tag = Tag::factory()->create();
        $post = Post::factory()->create();

        $response = $this->deleteJson(
            route('api.tags.posts.store', [$tag, $post])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $tag
                ->posts()
                ->where('posts.id', $post->id)
                ->exists()
        );
    }
}
