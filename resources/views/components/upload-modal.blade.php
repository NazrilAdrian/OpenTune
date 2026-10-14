<div id="upload-choice-modal"
     class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/40 px-4 py-6 backdrop-blur-[2px]"
     role="dialog"
     aria-modal="true"
     aria-labelledby="upload-choice-title">
    <section class="w-full max-w-[460px] rounded-2xl border border-[#eeeaf7] bg-white p-6 shadow-2xl sm:p-7">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="upload-choice-title" class="text-2xl font-bold tracking-tight text-[#303137]">Upload musik</h2>
                <p class="mt-1 text-sm text-[#8b8d95]">Pilih jenis konten yang ingin kamu unggah.</p>
            </div>
            <button type="button" data-upload-choice-close aria-label="Tutup dialog" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[#e2e0eb] bg-white text-xl leading-none text-[#6e6b78] transition hover:bg-[#f1ebff] hover:text-[#5b32eb]">&times;</button>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('songs.create') }}" class="group rounded-xl border border-[#e2e0eb] bg-white p-4 transition hover:border-[#8b6af5] hover:bg-[#f8f5ff]">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f1ebff] text-[#5b32eb] transition group-hover:bg-gradient-to-r group-hover:from-[#d19cff] group-hover:to-[#5b32eb] group-hover:text-white">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="1.8" aria-hidden="true"><path d="M9 18V5l11-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="17" cy="16" r="3"/></svg>
                </span>
                <span class="mt-3 block font-semibold text-[#303137]">Upload Lagu</span>
                <span class="mt-1 block text-xs leading-5 text-[#8b8d95]">Unggah satu file audio MP3 atau M4A.</span>
            </a>

            <a href="{{ route('albums.create') }}" class="group rounded-xl border border-[#e2e0eb] bg-white p-4 transition hover:border-[#8b6af5] hover:bg-[#f8f5ff]">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f1ebff] text-[#5b32eb] transition group-hover:bg-gradient-to-r group-hover:from-[#d19cff] group-hover:to-[#5b32eb] group-hover:text-white">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></svg>
                </span>
                <span class="mt-3 block font-semibold text-[#303137]">Upload Album</span>
                <span class="mt-1 block text-xs leading-5 text-[#8b8d95]">Unggah album berisi beberapa lagu melalui file ZIP.</span>
            </a>
        </div>

        <div class="mt-5 flex justify-end">
            <button type="button" data-upload-choice-close class="upload-form-action px-5 text-sm">Cancel</button>
        </div>
    </section>
</div>
