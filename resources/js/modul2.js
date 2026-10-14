(() => {
    const player = document.getElementById('music-player');
    const audio = document.getElementById('player-audio');
    const playButton = document.getElementById('player-toggle');
    const playIcon = document.getElementById('player-play-icon');
    const title = document.getElementById('player-title');
    const artist = document.getElementById('player-artist');
    const cover = document.getElementById('player-cover');
    const progress = document.getElementById('player-progress');
    const volume = document.getElementById('player-volume');
    const current = document.getElementById('player-current-time');
    const duration = document.getElementById('player-duration');
    const close = document.getElementById('player-close');

    if (player && audio) {
        const playPath = '<path d="M8 5v14l11-7z"/>';
        const pausePath = '<path d="M7 5h4v14H7zM13 5h4v14h-4z"/>';

        const formatTime = (seconds) => {
            if (!Number.isFinite(seconds)) return '0:00';
            const min = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${min}:${sec}`;
        };

        const setPlayingIcon = (playing) => {
            playIcon.innerHTML = playing ? pausePath : playPath;
            playButton.setAttribute('aria-label', playing ? 'Pause' : 'Putar');
        };

        const loadSong = ({ title: songTitle, artist: songArtist, cover: songCover, audio: songAudio }) => {
            title.textContent = songTitle || 'Tanpa judul';
            artist.textContent = songArtist || 'Unknown Artist';
            cover.src = songCover || 'https://placehold.co/100x100/e9e5ff/5b36e8?text=Song';
            audio.src = songAudio;
            audio.load();
            player.classList.remove('hidden');
            audio.play().catch(() => {});
            setPlayingIcon(true);
        };

        document.addEventListener('click', (event) => {
            const trigger = event.target.closest('[data-player-audio]');
            if (!trigger) return;

            loadSong({
                title: trigger.dataset.playerTitle,
                artist: trigger.dataset.playerArtist,
                cover: trigger.dataset.playerCover,
                audio: trigger.dataset.playerAudio,
            });
        });

        playButton?.addEventListener('click', () => {
            if (!audio.src) return;
            if (audio.paused) audio.play().catch(() => {});
            else audio.pause();
        });

        audio.addEventListener('play', () => setPlayingIcon(true));
        audio.addEventListener('pause', () => setPlayingIcon(false));
        audio.addEventListener('loadedmetadata', () => {
            duration.textContent = formatTime(audio.duration);
        });
        audio.addEventListener('timeupdate', () => {
            current.textContent = formatTime(audio.currentTime);
            progress.value = audio.duration ? ((audio.currentTime / audio.duration) * 100).toString() : '0';
        });

        progress?.addEventListener('input', () => {
            if (!audio.duration) return;
            audio.currentTime = (Number(progress.value) / 100) * audio.duration;
        });

        volume?.addEventListener('input', () => {
            audio.volume = Number(volume.value);
        });

        close?.addEventListener('click', () => {
            audio.pause();
            audio.removeAttribute('src');
            player.classList.add('hidden');
            setPlayingIcon(false);
        });

        audio.volume = Number(volume?.value || 0.7);
    }

    document.querySelectorAll('[data-file-label]').forEach((input) => {
        input.addEventListener('change', () => {
            const selector = input.dataset.fileLabel;
            const label = selector ? document.querySelector(selector) : input.parentElement?.querySelector('[data-file-name]');
            if (!label) return;

            if (input.files?.length) {
                label.textContent = input.files[0].name;
                label.classList.add('text-[#5b32eb]');
                label.setAttribute('title', input.files[0].name);
            }
        });
    });

    // Upload chooser: user picks whether to upload one song or an album ZIP.
    const uploadModal = document.getElementById('upload-choice-modal');
    const openUploadModal = () => {
        if (!uploadModal) return;
        uploadModal.classList.remove('hidden');
        uploadModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        uploadModal.querySelector('[data-upload-choice-close]')?.focus();
    };
    const closeUploadModal = () => {
        if (!uploadModal) return;
        uploadModal.classList.add('hidden');
        uploadModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-open-upload-modal]').forEach((button) => {
        button.addEventListener('click', openUploadModal);
    });
    uploadModal?.querySelectorAll('[data-upload-choice-close]').forEach((button) => {
        button.addEventListener('click', closeUploadModal);
    });
    uploadModal?.addEventListener('click', (event) => {
        if (event.target === uploadModal) closeUploadModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && uploadModal && !uploadModal.classList.contains('hidden')) {
            closeUploadModal();
        }
    });

    document.querySelectorAll('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const target = document.querySelector(input.dataset.imageInput);
            const file = input.files?.[0];
            if (!target || !file) return;
            target.src = URL.createObjectURL(file);
        });
    });

})();
