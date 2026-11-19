<div class="space-y-5">
    <div>
        <label class="mb-1 block text-sm font-medium">Nama Artist 
            <span class="text-red-500">*</span>
        </label>
        <input class="admin-input" name="name" value="{{ old('name', $artist->name ?? '') }}" maxlength="100" required>@error('name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Biografi</label>
        <textarea class="admin-input" name="bio" rows="5" maxlength="2000">{{ old('bio', $artist->bio ?? '') }}</textarea>
        @error('bio')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Foto Artist</label>
        <input class="admin-input" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/*">
        @if(isset($artist) && $artist->image)
            <img src="{{ $artist->image_url }}" class="mt-3 h-24 w-24 rounded-full object-cover" alt="Foto artis saat ini">
        @endif
        @error('image')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>