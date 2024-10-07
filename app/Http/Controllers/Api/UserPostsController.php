<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;

class UserPostsController extends Controller
{
    public function index(Request $request, User $user): PostCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $posts = $user
            ->posts()
            ->search($search)
            ->latest()
            ->paginate();

        return new PostCollection($posts);
    }

    public function store(Request $request, User $user): PostResource
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'text' => ['nullable', 'max:255', 'string'],
        ]);

        $post = $user->posts()->create($validated);

        return new PostResource($post);
    }
}
