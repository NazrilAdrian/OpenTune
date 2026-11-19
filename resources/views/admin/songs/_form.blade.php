<div class="space-y-6">
    {{-- Informasi utama lagu --}}
    <section class="rounded-xl border border-gray-100 p-4 sm:p-5">
        <div class="mb-4 flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-xl text-purple-700">♫</div>
            <div>
                <h2 class="font-semibold text-gray-900">Informasi Lagu</h2>
                <p class="mt-1 text-xs text-gray-500">Masukkan informasi dasar agar lagu mudah ditemukan.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700">Judul lagu <span class="text-red-500">*</span></label>
                <input id="title" class="admin-input" name="title" value="{{ old('title', $song->title ?? '') }}" maxlength="150" placeholder="Contoh: Hati-Hati di Jalan" required>
                @error('title')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="artist_id" class="mb-1.5 block text-sm font-medium text-gray-700">Artist <span class="text-red-500">*</span></label>
                <select id="artist_id" class="admin-input" name="artist_id" required>
                    <option value="">Pilih artist</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}" @selected((string)old('artist_id', $song->artist_id ?? '') === (string)$artist->id)>{{ $artist->name }}</option>
                    @endforeach
                </select>
                @error('artist_id')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                @if($artists->isEmpty())<p class="mt-1.5 text-xs text-amber-600">Belum ada artist. Tambahkan data artist terlebih dahulu.</p>@endif
            </div>
            <div>
                <label for="genre_id" class="mb-1.5 block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                <select id="genre_id" class="admin-input" name="genre_id" required>
                    <option value="">Pilih genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" @selected((string)old('genre_id', $song->genre_id ?? '') === (string)$genre->id)>{{ $genre->name }}</option>
                    @endforeach
                </select>
                @error('genre_id')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                @if($genres->isEmpty())<p class="mt-1.5 text-xs text-amber-600">Belum ada genre. Tambahkan data genre terlebih dahulu.</p>@endif
            </div>
            <div class="sm:col-span-2">
                <label for="album_id" class="mb-1.5 block text-sm font-medium text-gray-700">Album <span class="font-normal text-gray-400">(opsional)</span></label>
                <select id="album_id" class="admin-input" name="album_id">
                    <option value="">Single / tidak masuk album</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->id }}" @selected((string)old('album_id', $song->album_id ?? '') === (string)$album->id)>{{ $album->name }} — {{ $album->artist?->name ?? 'Artist tidak diketahui' }}</option>
                    @endforeach
                </select>
                @error('album_id')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1.5 text-xs text-gray-500">Pilih album jika lagu merupakan bagian dari album tertentu.</p>
            </div>
        </div>
    </section>

    {{-- File audio --}}
    <section class="rounded-xl border border-gray-100 p-4 sm:p-5">
        <div class="mb-4 flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700">♪</div>
            <div>
                <h2 class="font-semibold text-gray-900">File Audio</h2>
                <p class="mt-1 text-xs text-gray-500">Unggah file lagu yang akan diputar di OpenTune.</p>
            </div>
        </div>
        @if(isset($song) && $song->file_path)
            <div class="mb-4 flex items-center gap-3 rounded-lg bg-gray-50 p-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-lg">🎵</div>
                <div class="min-w-0 flex-1"><p class="text-sm font-medium text-gray-800">File audio saat ini</p><p class="truncate text-xs text-gray-500">{{ basename($song->file_path) }}</p></div>
                <span class="admin-pill bg-green-100 text-green-700">Tersimpan</span>
            </div>
        @endif
        <label for="audio" class="mb-1.5 block text-sm font-medium text-gray-700">{{ isset($song) ? 'Ganti file audio (opsional)' : 'Pilih file audio' }} @unless(isset($song))<span class="text-red-500">*</span>@endunless</label>
        <input id="audio" class="admin-input" type="file" name="audio" accept=".mp3,.m4a,audio/mpeg,audio/mp4" {{ isset($song) ? '' : 'required' }}>
        <p class="mt-1.5 text-xs text-gray-500">Format yang didukung: MP3 atau M4A. Ukuran maksimal 50 MB.</p>
        @error('audio')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
    </section>

    {{-- Cover lagu --}}
    <section class="rounded-xl border border-gray-100 p-4 sm:p-5">
        <div class="mb-4 flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pink-100 text-xl text-pink-700">▧</div>
            <div>
                <h2 class="font-semibold text-gray-900">Cover Lagu</h2>
                <p class="mt-1 text-xs text-gray-500">Gambar cover membantu lagu terlihat menarik di daftar musik.</p>
            </div>
        </div>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
            @if(isset($song) && $song->cover_url)
                <img src="{{ $song->cover_url }}" class="h-28 w-28 rounded-xl border border-gray-100 bg-gray-50 object-cover" alt="Cover lagu saat ini">
            @else
                <div class="flex h-28 w-28 shrink-0 flex-col items-center justify-center rounded-xl border border-dashed border-purple-200 bg-purple-50 text-purple-400"><span class="text-3xl">♫</span><span class="mt-1 text-[10px]">Preview cover</span></div>
            @endif
            <div class="min-w-0 flex-1">
                <label for="cover" class="mb-1.5 block text-sm font-medium text-gray-700">Pilih gambar <span class="font-normal text-gray-400">(opsional)</span></label>
                <input id="cover" class="admin-input" type="file" name="cover" accept="image/jpeg,image/png,image/webp">
                <p class="mt-1.5 text-xs text-gray-500">Format JPG, PNG, atau WEBP. Ukuran maksimal 5 MB.</p>
                @error('cover')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>
    <p class="text-xs text-gray-500"><span class="text-red-500">*</span> Wajib diisi. Pastikan data dan file yang dipilih sudah benar sebelum menyimpan.</p>
</div>
