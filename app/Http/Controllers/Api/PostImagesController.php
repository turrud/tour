<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ImageCollection;

class PostImagesController extends Controller
{
    public function index(Request $request, Post $post): ImageCollection
    {
        $this->authorize('view', $post);

        $search = $request->get('search', '');

        $images = $post
            ->images()
            ->search($search)
            ->latest()
            ->paginate();

        return new ImageCollection($images);
    }

    public function store(Request $request, Post $post): ImageResource
    {
        $this->authorize('create', Image::class);

        $validated = $request->validate([
            'path' => ['image', 'max:1024', 'nullable'],
        ]);

        if ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('public');
        }

        $image = $post->images()->create($validated);

        return new ImageResource($image);
    }
}
