<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\Image;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostImagesDetail extends Component
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    public Post $post;
    public Image $image;
    public $imagePath;
    public $uploadIteration = 0;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Image';

    protected $rules = [
        'imagePath' => ['image'],
    ];

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->resetImageData();
    }

    public function resetImageData(): void
    {
        $this->image = new Image();

        $this->imagePath = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newImage(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.post_images.new_title');
        $this->resetImageData();

        $this->showModal();
    }

    public function editImage(Image $image): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.post_images.edit_title');
        $this->image = $image;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->image->post_id) {
            $this->authorize('create', Image::class);

            $this->image->post_id = $this->post->id;
        } else {
            $this->authorize('update', $this->image);
        }

        if ($this->imagePath) {
            $this->image->path = $this->imagePath->store('public');
        }

        $this->image->save();

        $this->uploadIteration++;

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Image::class);

        collect($this->selected)->each(function (string $id) {
            $image = Image::findOrFail($id);

            if ($image->path) {
                Storage::delete($image->path);
            }

            $image->delete();
        });

        $this->selected = [];
        $this->allSelected = false;

        $this->resetImageData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->post->images as $image) {
            array_push($this->selected, $image->id);
        }
    }

    public function render(): View
    {
        return view('livewire.post-images-detail', [
            'images' => $this->post->images()->paginate(20),
        ]);
    }
}
