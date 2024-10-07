<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserPostsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public User $user;
    public Post $post;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Post';

    protected $rules = [
        'post.name' => ['nullable', 'max:255', 'string'],
        'post.text' => ['nullable', 'max:255', 'string'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->resetPostData();
    }

    public function resetPostData(): void
    {
        $this->post = new Post();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newPost(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.user_posts.new_title');
        $this->resetPostData();

        $this->showModal();
    }

    public function editPost(Post $post): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_posts.edit_title');
        $this->post = $post;

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

        if (!$this->post->user_id) {
            $this->authorize('create', Post::class);

            $this->post->user_id = $this->user->id;
        } else {
            $this->authorize('update', $this->post);
        }

        $this->post->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Post::class);

        Post::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetPostData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->user->posts as $post) {
            array_push($this->selected, $post->id);
        }
    }

    public function render(): View
    {
        return view('livewire.user-posts-detail', [
            'posts' => $this->user->posts()->paginate(20),
        ]);
    }
}
