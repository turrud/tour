<?php
namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;

class TagPostsController extends Controller
{
    public function index(Request $request, Tag $tag): PostCollection
    {
        $this->authorize('view', $tag);

        $search = $request->get('search', '');

        $posts = $tag
            ->posts()
            ->search($search)
            ->latest()
            ->paginate();

        return new PostCollection($posts);
    }

    public function store(Request $request, Tag $tag, Post $post): Response
    {
        $this->authorize('update', $tag);

        $tag->posts()->syncWithoutDetaching([$post->id]);

        return response()->noContent();
    }

    public function destroy(Request $request, Tag $tag, Post $post): Response
    {
        $this->authorize('update', $tag);

        $tag->posts()->detach($post);

        return response()->noContent();
    }
}
