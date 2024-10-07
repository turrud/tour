<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostTagsDetail extends Component
{
    use AuthorizesRequests;

    public Post $post;
    public Tag $tag;
    public $tagsForSelect = [];
    public $tag_id = null;

    public $showingModal = false;
    public $modalTitle = 'New Tag';

    protected $rules = [
        'tag_id' => ['required', 'exists:tags,id'],
    ];

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->tagsForSelect = Tag::pluck('name', 'id');
        $this->resetTagData();
    }

    public function resetTagData(): void
    {
        $this->tag = new Tag();

        $this->tag_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newTag(): void
    {
        $this->modalTitle = trans('crud.post_tags.new_title');
        $this->resetTagData();

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

        $this->authorize('create', Tag::class);

        $this->post->tags()->attach($this->tag_id, []);

        $this->hideModal();
    }

    public function detach($tag): void
    {
        $this->authorize('delete-any', Tag::class);

        $this->post->tags()->detach($tag);

        $this->resetTagData();
    }

    public function render(): View
    {
        return view('livewire.post-tags-detail', [
            'postTags' => $this->post
                ->tags()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
