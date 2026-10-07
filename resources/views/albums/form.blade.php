@php($editing = isset($album))
<div class="grid gap-7 lg:grid-cols-[320px_1fr]">
    <section class="rounded-2xl border border-[#e0e2e8] bg-white p-5">
        <h2 class="text-2xl font-bold text-[#303137]">Cover Album</h2>
        <p class="mt-2 text-sm leading-5 text-[#989aa2]">Gunakan gambar yang jelas dan mudah dikenali sebagai representasi album.</p>
        <label class="mt-4 block cursor-pointer">
            <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" class="hidden" data-image-input="#album-cover-preview">
            <div class="overflow-hidden rounded-xl border border-dashed border-[#dfe1e7] bg-white"><img id="album-cover-preview" src="{{ $editing ? $album->cover_url : 'https://placehold.co/600x600/fbfbfd/888a92?text=Cover+Album' }}" alt="Preview cover album" class="aspect-square w-full object-cover"></div>
            <span class="mt-3 block text-center text-sm font-semibold text-[#6d6f78]">Klik untuk memilih cover</span>
        </label>
        @error('cover')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </section>
    <section class="rounded-2xl border border-[#e0e2e8] bg-white p-5 sm:p-6">
        <h2 class="text-2xl font-bold text-[#303137]">Informasi Album</h2>
        <p class="mt-2 text-sm text-[#989aa2]">Isi detail album agar mudah dicari dan dipahami oleh pendengar.</p>
        <div class="mt-5 space-y-5">
            <div><label for="name" class="mb-2 block text-sm font-semibold">Judul Album</label><input id="name" name="name" value="{{ old('name', $album->name ?? '') }}" required class="field-input">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div><label for="artist_name" class="mb-2 block text-sm font-semibold">Artis</label><input id="artist_name" name="artist_name" value="{{ old('artist_name', $album->artist->name ?? '') }}" required class="field-input" list="album-artists-list"><datalist id="album-artists-list">@foreach($artists as $artist)<option value="{{ $artist->name }}">@endforeach</datalist>@error('artist_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            @unless($editing)
                <div><label for="genre_id" class="mb-2 block text-sm font-semibold">Genre</label><select id="genre_id" name="genre_id" required class="field-input"><option value="">Pilih genre</option>@foreach($genres as $genre)<option value="{{ $genre->id }}" @selected(old('genre_id') == $genre->id)>{{ $genre->name }}</option>@endforeach</select>@error('genre_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label for="release_date" class="mb-2 block text-sm font-semibold">Tanggal Rilis <span class="font-normal text-[#9a9ba2]">(opsional)</span></label><input id="release_date" name="release_date" type="date" value="{{ old('release_date') }}" class="field-input">@error('release_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label for="zip" class="mb-2 block text-sm font-semibold">File Audio</label><label for="zip" class="flex cursor-pointer items-center justify-between gap-4 rounded-xl bg-[#f1f1f4] px-4 py-3"><div class="flex min-w-0 items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#8d8f96]"><svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.7"><path d="M4 7h16v13H4z"/><path d="m8 7 1.5-3h5L16 7"/></svg></span><span class="min-w-0"><span data-file-name class="block truncate text-sm font-semibold text-[#3e3f45]">Belum ada file dipilih</span><span class="block text-xs text-[#9a9ba2]">ZIP berisi MP3/M4A - Maks. 50 MB</span></span></div><span class="shrink-0 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#4b4c52] shadow-sm">Pilih File</span></label><input id="zip" name="zip" type="file" accept=".zip,application/zip" required class="hidden" data-file-label>@error('zip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            @endunless
        </div>
    </section>
</div>
