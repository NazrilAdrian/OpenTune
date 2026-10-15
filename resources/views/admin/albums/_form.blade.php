<div class="space-y-5">
    <div>
        <label class="mb-1 block text-sm font-medium">Nama Album 
            <span class="text-red-500">*</span>
        </label>
        <input class="admin-input" name="name" value="{{ old('name', $album->name ?? '') }}" maxlength="150" required>@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
    <div>
        <label class="mb-1 block text-sm font-medium">Artis 
            <span class="text-red-500">*</span>
        </label>
        <select class="admin-input" name="artist_id" required>
            <option value="">Pilih artis</option>
            @foreach($artists as $artist)
                <option value="{{ $artist->id }}" @selected((string)old('artist_id', $album->artist_id ?? '') === (string)$artist->id)>{{ $artist->name }}</option>
            @endforeach
        </select>
        @error('artist_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Tanggal Rilis</label>
        <input class="admin-input" type="date" name="release_date" value="{{ old('release_date', isset($album) && $album->release_date ? $album->release_date->format('Y-m-d') : '') }}">
        @error('release_date')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Cover Album</label>
        <input class="admin-input" type="file" name="cover" accept=".jpg,.jpeg,.png,.webp,image/*">
        @if(isset($album) && $album->cover_path)
            <img src="{{ $album->cover_url }}" class="mt-3 h-24 w-24 rounded-lg object-cover" alt="Cover album saat ini">
        @endif
        @error('cover')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>