@extends('layouts.app')

@section('title', 'Edit Profile - OpenTune')

@section('content')
    {{-- Font Poppins (sesuai desain) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="mx-auto w-full max-w-3xl font-['Poppins',sans-serif] lg:mx-0">

        <h1 class="mb-3 text-2xl font-bold tracking-tight text-black sm:text-[28px]">Edit Profile &amp; Account Setting</h1>

        {{-- ===== Edit Profile ===== --}}
        <section class="rounded-xl border border-[#e4def5] bg-gradient-to-b from-[#b79cf0] via-[#d3c6ee] to-[#f1eefa] p-5 shadow-sm">
            <h2 class="text-2xl font-bold text-black">Edit Profile</h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate
                  class="mt-1 grid gap-6 md:grid-cols-[minmax(0,1fr)_minmax(0,1.25fr)]">
                @csrf
                @method('PUT')

                {{-- Profile Photo --}}
                <div>
                    <p class="text-lg text-[#1f1f23]">Profile Photo</p>

                    <div class="mt-2 flex items-center gap-6">
                        <label for="profile_picture" class="group relative block h-36 w-36 shrink-0 cursor-pointer overflow-hidden rounded-full border-4 border-white/70 bg-white/40 shadow-md">
                            <img id="photoPreview"
                                 src="{{ $user->profile_picture_url }}"
                                 alt="Foto profil {{ $user->username }}"
                                 class="h-full w-full object-cover opacity-40 transition group-hover:opacity-60">
                            <span class="absolute inset-0 flex items-center justify-center text-[#3a3a40]">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 8a2 2 0 0 1 2-2h2l1.5-2h7L17 6h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z" />
                                    <circle cx="12" cy="13" r="3.5" />
                                </svg>
                            </span>
                        </label>

                        <label for="profile_picture" class="cursor-pointer text-sm font-medium text-[#5b34f0] hover:underline">Change Photo</label>
                        <input id="profile_picture" type="file" name="profile_picture" accept="image/png,image/jpeg" class="sr-only">
                    </div>

                    <p class="mt-2 text-[10px] text-[#1f1f23]">Format: JPG, PNG. Max 2MB</p>
                    @error('profile_picture')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Account Details --}}
                <div>
                    <p class="text-lg text-[#1f1f23]">Account Details</p>

                    <div class="mt-2 space-y-3">
                        <div>
                            <label for="username" class="mb-1 block text-xs font-medium text-[#1f1f23]">Username</label>
                            <input id="username" type="text" name="username"
                                   value="{{ old('username', $user->username) }}"
                                   placeholder="Enter your username"
                                   maxlength="50" required autocomplete="username"
                                   class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('username') border-red-400 @else border-[#dcdce1] @enderror">
                            @error('username')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-1 block text-xs font-medium text-[#1f1f23]">Email Address</label>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="Enter your email address"
                                   required autocomplete="email"
                                   class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('email') border-red-400 @else border-[#dcdce1] @enderror">
                            @error('email')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <button type="submit"
                                class="flex-1 rounded-full bg-[#5b34f0] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#4b27d8] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2">
                            Save Changes
                        </button>
                        <a href="{{ route('profile.show') }}"
                           class="flex-1 rounded-full border border-[#dcdce1] bg-white px-5 py-2 text-center text-xs font-semibold text-[#5b34f0] transition hover:bg-[#f7f7fa]">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </section>

        {{-- ===== Danger Zone ===== --}}
        <h2 class="mb-2 mt-6 text-2xl font-bold text-black">Danger Zone</h2>
        <section class="rounded-xl border border-red-200 bg-gradient-to-b from-[#fbbcbc] via-[#fcd0d0] to-[#fde6e6] p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-[#b00000]">Permanently Delete Account</h3>
            <p class="mt-1 text-sm font-medium leading-snug text-[#3a3a40]">
                This action is permanent and cannot be undone. All your personal data, playlists, and account settings will be permanently deleted.
            </p>
            <button type="button" id="openDeleteModal"
                    class="mt-3 rounded-full bg-[#ef3340] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#d71f2c] focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                Delete Account
            </button>
        </section>
    </div>

    {{-- ===== Modal Confirm Account Deletion ===== --}}
    <div id="deleteModal"
         class="{{ $errors->has('password') ? 'flex' : 'hidden' }} fixed inset-0 z-[100] items-center justify-center bg-black/40 px-4 font-['Poppins',sans-serif] backdrop-blur-[2px]"
         role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl ring-1 ring-black/5">
            <h3 id="deleteModalTitle" class="text-xl font-bold text-[#b00000]">Confirm Account Deletion</h3>
            <p class="mt-1 text-lg font-semibold leading-snug text-[#3a3a40]">Enter your password to confirm account deletion</p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4" novalidate>
                @csrf
                @method('DELETE')

                <label for="delete_password" class="mb-1 block text-xs font-medium text-[#1f1f23]">Password</label>
                <div class="relative">
                    <input id="delete_password" type="password" name="password"
                           placeholder="Enter your password" required autocomplete="current-password"
                           class="block w-full rounded-md border bg-white py-2.5 pl-3 pr-10 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-200 @error('password') border-red-400 @else border-[#dcdce1] @enderror">
                    <button type="button" id="toggleDeletePassword" aria-label="Show or hide password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-[#3a3a40] hover:text-red-600">
                        <svg data-eye-off class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3l18 18" />
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" />
                            <path d="M9.4 5.5A9.8 9.8 0 0 1 12 5c5 0 8.5 3.5 10 7a13.5 13.5 0 0 1-3.2 4.3M6.1 6.1A13.6 13.6 0 0 0 2 12c1.5 3.5 5 7 10 7a9.7 9.7 0 0 0 4-.9" />
                        </svg>
                        <svg data-eye class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12c1.5-3.5 5-7 10-7s8.5 3.5 10 7c-1.5 3.5-5 7-10 7S3.5 15.5 2 12z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex items-center gap-3">
                    <button type="submit"
                            class="rounded-full bg-[#ef3340] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#d71f2c] focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                        Confirm Delete
                    </button>
                    <button type="button" id="closeDeleteModal"
                            class="flex-1 rounded-full bg-[#8f8f94] px-5 py-2 text-xs font-semibold text-white transition hover:bg-[#7a7a80] focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            // ----- Modal hapus akun -----
            var modal = document.getElementById('deleteModal');
            var pwInput = document.getElementById('delete_password');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(function () { pwInput.focus(); }, 50);
            }
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                pwInput.value = '';
            }

            document.getElementById('openDeleteModal').addEventListener('click', openModal);
            document.getElementById('closeDeleteModal').addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            // ----- Toggle show/hide password di modal -----
            var toggle = document.getElementById('toggleDeletePassword');
            toggle.addEventListener('click', function () {
                var show = pwInput.type === 'password';
                pwInput.type = show ? 'text' : 'password';
                toggle.querySelector('[data-eye]').classList.toggle('hidden', !show);
                toggle.querySelector('[data-eye-off]').classList.toggle('hidden', show);
            });

            // ----- Preview foto profil yang baru dipilih -----
            var fileInput = document.getElementById('profile_picture');
            var preview = document.getElementById('photoPreview');
            fileInput.addEventListener('change', function () {
                var file = fileInput.files && fileInput.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('opacity-40');
                }
            });
        })();
    </script>
@endsection
