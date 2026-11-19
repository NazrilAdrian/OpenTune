<section id="music-player" class="fixed inset-x-0 bottom-0 z-50 hidden border-t border-[#dedfe5] bg-white/95 shadow-[0_-12px_35px_rgba(38,31,70,0.08)] backdrop-blur lg:left-[274px]">
    <div class="mx-auto max-w-[1500px] px-4 py-3 sm:px-6 lg:px-10">
        <div class="mx-auto mb-2 h-1 w-10 rounded-full bg-[#d5d7df]"></div>
        <div class="grid items-center gap-4 lg:grid-cols-[280px_1fr_300px]">
            <div class="flex min-w-0 items-center gap-3">
                <img id="player-cover" src="https://placehold.co/100x100/e9e5ff/5b36e8?text=Song" alt="" class="h-14 w-14 shrink-0 rounded-xl object-cover">
                <div class="min-w-0">
                    <p id="player-title" class="truncate text-sm font-bold text-[#2f3035]">Tidak ada lagu</p>
                    <p id="player-artist" class="truncate text-xs text-[#8b8d95]">-</p>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <div class="mb-1 flex items-center gap-3">
                    <button id="player-prev" type="button" class="flex h-9 w-9 items-center justify-center rounded-full text-[#3a3b42] hover:bg-[#f2f1fa]" aria-label="Sebelumnya">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8"><path d="M19 6 9 12l10 6V6Z"/><path d="M5 5v14"/></svg>
                    </button>
                    <button id="player-toggle" type="button" class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#cfa0ff] to-[#6035ef] text-white shadow-md" aria-label="Putar">
                        <svg id="player-play-icon" viewBox="0 0 24 24" class="ml-0.5 h-6 w-6 fill-current"><path d="M8 5v14l11-7z"/></svg>
                    </button>
                    <button id="player-next" type="button" class="flex h-9 w-9 items-center justify-center rounded-full text-[#3a3b42] hover:bg-[#f2f1fa]" aria-label="Berikutnya">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="1.8"><path d="m5 6 10 6-10 6V6Z"/><path d="M19 5v14"/></svg>
                    </button>
                </div>
                <div class="flex w-full items-center gap-3 text-[11px] text-[#777983]">
                    <span id="player-current-time">0:00</span>
                    <input id="player-progress" type="range" min="0" max="100" value="0" step="0.1" class="h-1.5 w-full cursor-pointer accent-[#713deb]">
                    <span id="player-duration">0:00</span>
                </div>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <span class="text-xs text-[#8b8d95]">Vol</span>
                <input id="player-volume" type="range" min="0" max="1" step="0.01" value="0.7" class="h-1.5 flex-1 cursor-pointer accent-[#713deb]">
                <button id="player-close" type="button" class="flex h-9 w-9 items-center justify-center rounded-full border border-[#e0e2e8] text-[#555761] hover:bg-[#f7f6fc]" aria-label="Tutup player">
                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-none stroke-current" stroke-width="1.7"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </div>
        <audio id="player-audio" preload="metadata"></audio>
    </div>
</section>
