@php $editing = isset($image) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <div
            x-data="imageViewer('{{ $editing && $image->path ? \Storage::url($image->path) : '' }}')"
        >
            <x-inputs.partials.label
                name="path"
                label="Path"
            ></x-inputs.partials.label
            ><br />

            <!-- Show the image -->
            <template x-if="imageUrl">
                <img
                    :src="imageUrl"
                    class="object-cover rounded border border-gray-200"
                    style="width: 100px; height: 100px;"
                />
            </template>

            <!-- Show the gray box when image is not available -->
            <template x-if="!imageUrl">
                <div
                    class="border rounded border-gray-200 bg-gray-100"
                    style="width: 100px; height: 100px;"
                ></div>
            </template>

            <div class="mt-2">
                <input type="file" name="path" id="path" @change="fileChosen" />
            </div>

            @error('path') @include('components.inputs.partials.error')
            @enderror
        </div>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="post_id" label="Post" required>
            @php $selected = old('post_id', ($editing ? $image->post_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Post</option>
            @foreach($posts as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
