<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.posts.show_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{ route('posts.index') }}" class="mr-4"
                        ><i class="mr-1 icon ion-md-arrow-back"></i
                    ></a>
                </x-slot>

                <div class="mt-4 px-4">
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.posts.inputs.name')
                        </h5>
                        <span>{{ $post->name ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.posts.inputs.text')
                        </h5>
                        <span>{{ $post->text ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.posts.inputs.user_id')
                        </h5>
                        <span>{{ optional($post->user)->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-10">
                    <a href="{{ route('posts.index') }}" class="button">
                        <i class="mr-1 icon ion-md-return-left"></i>
                        @lang('crud.common.back')
                    </a>

                    @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}" class="button">
                        <i class="mr-1 icon ion-md-add"></i>
                        @lang('crud.common.create')
                    </a>
                    @endcan
                </div>
            </x-partials.card>

            @can('view-any', App\Models\Image::class)
            <x-partials.card class="mt-5">
                <x-slot name="title"> Images </x-slot>

                <livewire:post-images-detail :post="$post" />
            </x-partials.card>
            @endcan @can('view-any', App\Models\post_tag::class)
            <x-partials.card class="mt-5">
                <x-slot name="title"> Tags </x-slot>

                <livewire:post-tags-detail :post="$post" />
            </x-partials.card>
            @endcan
        </div>
    </div>
</x-app-layout>
