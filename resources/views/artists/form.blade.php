<div class="mx-auto max-w-2xl rounded-2xl border border-[#e0e2e8] bg-white p-6 sm:p-8">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold">Nama Artist</label>
        <input id="name" name="name" value="{{ old('name', $artist->name ?? '') }}" required class="field-input">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="mt-5">
        <label for="bio" class="mb-2 block text-sm font-semibold">Bio</label>
        <textarea id="bio" name="bio" rows="5" class="field-input resize-none">{{ old('bio', $artist->bio ?? '') }}</textarea>
        @error('bio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="mt-5">
        <label for="image" class="mb-2 block text-sm font-semibold">Foto Artist</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#e0e2e8] bg-white p-3 text-sm">
        @if(isset($artist) && $artist->image)<img src="{{ $artist->image_url }}" class="mt-3 h-28 w-28 rounded-xl object-cover" alt="{{ $artist->name }}">@endif
        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
