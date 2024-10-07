<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ImageCollection;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageStoreRequest;
use App\Http\Requests\ImageUpdateRequest;

class ImageController extends Controller
{
    public function index(Request $request): ImageCollection
    {
        $this->authorize('view-any', Image::class);

        $search = $request->get('search', '');

        $images = Image::search($search)
            ->latest()
            ->paginate();

        return new ImageCollection($images);
    }

    public function store(ImageStoreRequest $request): ImageResource
    {
        $this->authorize('create', Image::class);

        $validated = $request->validated();
        if ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('public');
        }

        $image = Image::create($validated);

        return new ImageResource($image);
    }

    public function show(Request $request, Image $image): ImageResource
    {
        $this->authorize('view', $image);

        return new ImageResource($image);
    }

    public function update(
        ImageUpdateRequest $request,
        Image $image
    ): ImageResource {
        $this->authorize('update', $image);

        $validated = $request->validated();

        if ($request->hasFile('path')) {
            if ($image->path) {
                Storage::delete($image->path);
            }

            $validated['path'] = $request->file('path')->store('public');
        }

        $image->update($validated);

        return new ImageResource($image);
    }

    public function destroy(Request $request, Image $image): Response
    {
        $this->authorize('delete', $image);

        if ($image->path) {
            Storage::delete($image->path);
        }

        $image->delete();

        return response()->noContent();
    }
}
