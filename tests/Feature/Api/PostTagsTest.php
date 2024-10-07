<?php

namespace Tests\Feature\Api;

use App\Models\Tag;
use App\Models\User;
use App\Models\Post;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTagsTest extends TestCase
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
    public function it_gets_post_tags(): void
    {
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();

        $post->tags()->attach($tag);

        $response = $this->getJson(route('api.posts.tags.index', $post));

        $response->assertOk()->assertSee($tag->name);
    }

    /**
     * @test
     */
    public function it_can_attach_tags_to_post(): void
    {
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->postJson(
            route('api.posts.tags.store', [$post, $tag])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $post
                ->tags()
                ->where('tags.id', $tag->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_tags_from_post(): void
    {
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->deleteJson(
            route('api.posts.tags.store', [$post, $tag])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $post
                ->tags()
                ->where('tags.id', $tag->id)
                ->exists()
        );
    }
}
