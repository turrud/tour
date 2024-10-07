<?php
namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;

class PostTagsController extends Controller
{
    public function index(Request $request, Post $post): TagCollection
    {
        $this->authorize('view', $post);

        $search = $request->get('search', '');

        $tags = $post
            ->tags()
            ->search($search)
            ->latest()
            ->paginate();

        return new TagCollection($tags);
    }

    public function store(Request $request, Post $post, Tag $tag): Response
    {
        $this->authorize('update', $post);

        $post->tags()->syncWithoutDetaching([$tag->id]);

        return response()->noContent();
    }

    public function destroy(Request $request, Post $post, Tag $tag): Response
    {
        $this->authorize('update', $post);

        $post->tags()->detach($tag);

        return response()->noContent();
    }
}
