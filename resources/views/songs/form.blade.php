@php($editing = isset($song))

<div class="grid gap-7 lg:grid-cols-[320px_1fr]">
    <section class="rounded-2xl border border-[#e0e2e8] bg-white p-5">
        <h2 class="text-2xl font-bold text-[#303137]">Cover Lagu</h2>
        <p class="mt-2 text-sm leading-5 text-[#989aa2]">Gunakan gambar yang jelas dan mudah dikenali sebagai representasi lagu.</p>

        <label class="mt-4 block cursor-pointer">
            <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" class="hidden" data-image-input="#song-cover-preview">
            <div class="overflow-hidden rounded-xl bg-[#f0f0f3]">
                <img id="song-cover-preview" src="{{ $editing ? $song->cover_url : 'https://placehold.co/600x600/f4f3f7/777983?text=Cover+Lagu' }}" alt="Preview cover" class="aspect-square w-full object-cover">
            </div>
            <span class="mt-3 block text-center text-sm font-semibold text-[#6d6f78]">Klik untuk memilih cover</span>
        </label>
        @error('cover')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </section>

    <section class="rounded-2xl border border-[#e0e2e8] bg-white p-5 sm:p-6">
        <h2 class="text-2xl font-bold text-[#303137]">Informasi Lagu</h2>
        <p class="mt-2 text-sm text-[#989aa2]">Isi detail lagu agar mudah dicari dan dipahami oleh pendengar.</p>

        <div class="mt-5 space-y-5">
            <div>
                <label for="title" class="mb-2 block text-sm font-semibold">Judul Lagu</label>
                <input id="title" name="title" type="text" value="{{ old('title', $song->title ?? '') }}" required class="field-input">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="artist_name" class="mb-2 block text-sm font-semibold">Artis</label>
                <input id="artist_name" name="artist_name" type="text" value="{{ old('artist_name', $song->artist->name ?? '') }}" required class="field-input" list="artists-list">
                <datalist id="artists-list">
                    @foreach($artists as $artist)
                        <option value="{{ $artist->name }}">
                    @endforeach
                </datalist>
                <p class="mt-1 text-xs text-[#9a9ba2]">Masukkan artist baru untuk membuat artist otomatis.</p>
                @error('artist_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="genre_id" class="mb-2 block text-sm font-semibold">Genre</label>
                <select id="genre_id" name="genre_id" required class="field-input">
                    <option value="">Pilih genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" @selected(old('genre_id', $song->genre_id ?? '') == $genre->id)>{{ $genre->name }}</option>
                    @endforeach
                </select>
                @error('genre_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="album_id" class="mb-2 block text-sm font-semibold">Album <span class="font-normal text-[#9a9ba2]">(opsional)</span></label>
                <select id="album_id" name="album_id" class="field-input">
                    <option value="">Single / tanpa album</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->id }}" @selected(old('album_id', $song->album_id ?? '') == $album->id)>{{ $album->name }}</option>
                    @endforeach
                </select>
                @error('album_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="audio" class="mb-2 block text-sm font-semibold">File Audio</label>
                <label for="audio" class="flex cursor-pointer items-center justify-between gap-4 rounded-xl bg-[#f1f1f4] px-4 py-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#8d8f96]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.7"><path d="M12 16V4m0 0L8 8m4-4 4 4M5 14v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4"/></svg>
                        </span>
                        <span class="min-w-0"><span data-file-name class="block truncate text-sm font-semibold text-[#3e3f45]">{{ $editing ? 'Pilih file baru jika ingin mengganti audio' : 'Belum ada file dipilih' }}</span><span class="block text-xs text-[#9a9ba2]">MP3 atau M4A - Maks. 50 MB</span></span>
                    </div>
                    <span class="shrink-0 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#4b4c52] shadow-sm">Pilih File</span>
                </label>
                <input id="audio" name="audio" type="file" accept="audio/mpeg,audio/mp4,.mp3,.m4a" {{ $editing ? '' : 'required' }} class="hidden" data-file-label>
                @error('audio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>
</div>
