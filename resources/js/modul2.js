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

    if (!player || !audio) return;

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

    document.querySelectorAll('[data-file-label]').forEach((input) => {
        input.addEventListener('change', () => {
            const container = input.closest('div, label')?.parentElement || input.parentElement;
            const label = container?.querySelector('[data-file-name]');
            if (label && input.files?.length) label.textContent = input.files[0].name;
        });
    });

    document.querySelectorAll('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const target = document.querySelector(input.dataset.imageInput);
            const file = input.files?.[0];
            if (!target || !file) return;
            target.src = URL.createObjectURL(file);
        });
    });

    audio.volume = Number(volume?.value || 0.7);
})();
