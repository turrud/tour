<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.posts.edit_title')
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

                <x-form
                    method="PUT"
                    action="{{ route('posts.update', $post) }}"
                    class="mt-4"
                >
                    @include('app.posts.form-inputs')

                    <div class="mt-10">
                        <a href="{{ route('posts.index') }}" class="button">
                            <i
                                class="
                                    mr-1
                                    icon
                                    ion-md-return-left
                                    text-primary
                                "
                            ></i>
                            @lang('crud.common.back')
                        </a>

                        <a href="{{ route('posts.create') }}" class="button">
                            <i class="mr-1 icon ion-md-add text-primary"></i>
                            @lang('crud.common.create')
                        </a>

                        <button
                            type="submit"
                            class="button button-primary float-right"
                        >
                            <i class="mr-1 icon ion-md-save"></i>
                            @lang('crud.common.update')
                        </button>
                    </div>
                </x-form>
            </x-partials.card>

            @can('view-any', App\Models\Image::class)
            <x-partials.card class="mt-5">
                <x-slot name="title"> Images </x-slot>

                <livewire:post-images-detail :post="$post" />
            </x-partials.card>
            @endcan @can('view-any', App\Models\Tag::class)
            <x-partials.card class="mt-5">
                <x-slot name="title"> Tags </x-slot>

                <livewire:post-tags-detail :post="$post" />
            </x-partials.card>
            @endcan
        </div>
    </div>
</x-app-layout>
