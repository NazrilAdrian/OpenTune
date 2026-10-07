@extends('layouts.app', ['minimal' => true])

@section('title', 'Sign Up - OpenTune')

@section('content')
    {{-- Font Poppins (sesuai desain) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Background watercolor / cloud ungu-putih --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden bg-[#f5f5f7]" aria-hidden="true">
        <div class="absolute -right-24 -top-24 h-[420px] w-[620px] rounded-full bg-[#e6dcf6] opacity-80 blur-3xl"></div>
        <div class="absolute right-0 top-1/4 h-[360px] w-[560px] rounded-[45%] bg-[#d3c6ea] opacity-70 blur-3xl"></div>
        <div class="absolute left-[-6%] top-[28%] h-[300px] w-[520px] rounded-[50%] bg-[#d9cff0] opacity-70 blur-3xl"></div>
        <div class="absolute bottom-[-8%] left-[8%] h-[300px] w-[420px] rounded-[50%] bg-[#cfc2e8] opacity-70 blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[6%] h-[260px] w-[420px] rounded-[50%] bg-[#d6cbec] opacity-60 blur-3xl"></div>
        <div class="absolute left-[2%] top-[8%] h-24 w-56 rounded-full bg-white opacity-70 blur-2xl"></div>
        <div class="absolute left-[18%] top-[55%] h-20 w-48 rounded-full bg-white opacity-80 blur-2xl"></div>
    </div>

    <div class="relative z-10 flex min-h-[calc(100vh-4rem)] items-center justify-center font-['Poppins',sans-serif]">
        <div class="w-full max-w-[420px] rounded-3xl bg-white px-8 py-8 shadow-lg shadow-[#5b3ff0]/10 ring-1 ring-black/[0.03] sm:px-10">

            {{-- Logo --}}
            <a href="{{ url('/landing') }}" class="mb-3 flex items-center justify-center gap-2">
                <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <linearGradient id="otLogoGradRegister" x1="4" y1="4" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#B07CF5" />
                            <stop offset="1" stop-color="#5B34F0" />
                        </linearGradient>
                    </defs>
                    <circle cx="20" cy="20" r="18" stroke="url(#otLogoGradRegister)" stroke-width="3" />
                    <path d="M12 23v-2.5a8 8 0 0 1 16 0V23" stroke="url(#otLogoGradRegister)" stroke-width="2.6" stroke-linecap="round" />
                    <rect x="10.5" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradRegister)" />
                    <rect x="25" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradRegister)" />
                </svg>
                <span class="text-xl font-semibold tracking-tight text-[#5b34f0]">OpenTune</span>
            </a>

            {{-- Heading --}}
            <div class="mb-5 text-center">
                <h1 class="text-[30px] font-bold leading-tight tracking-tight text-[#2b2b2f]">Create an Account</h1>
                <p class="mx-auto mt-1.5 max-w-[250px] text-[11px] leading-snug text-[#3a3a40]">
                    Join OpenTune to discover, create, and share your music journey.
                </p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" novalidate class="space-y-3.5">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Username</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        autocomplete="username"
                        autofocus
                        required
                        maxlength="50"
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('username') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="user@example.com"
                        autocomplete="email"
                        required
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('email') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="password" class="block text-xs font-medium text-[#1f1f23]">Password</label>
                        {{-- centang muncul otomatis saat password >= 8 karakter --}}
                        <svg data-check-password class="hidden h-4 w-4 text-[#1f1f23]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12.5l4.5 4.5L19 7.5" />
                        </svg>
                    </div>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="new-password"
                            required
                            minlength="8"
                            class="block w-full rounded-md border bg-white py-2.5 pl-3 pr-10 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('password') border-red-400 @else border-[#dcdce1] @enderror"
                        >
                        <button type="button" data-toggle-password="password" aria-label="Show or hide password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#3a3a40] hover:text-[#5b34f0]">
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
                    <p class="mt-1 text-[10px] leading-snug text-[#3a3a40]">Must be at least 8 characters long.</p>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="password_confirmation" class="block text-xs font-medium text-[#1f1f23]">Confirm Password</label>
                        {{-- centang muncul otomatis saat konfirmasi cocok --}}
                        <svg data-check-confirm class="hidden h-4 w-4 text-[#1f1f23]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12.5l4.5 4.5L19 7.5" />
                        </svg>
                    </div>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm your password"
                            autocomplete="new-password"
                            required
                            class="block w-full rounded-md border border-[#dcdce1] bg-white py-2.5 pl-3 pr-10 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20"
                        >
                        <button type="button" data-toggle-password="password_confirmation" aria-label="Show or hide password confirmation"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#3a3a40] hover:text-[#5b34f0]">
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
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terms of Service --}}
                <div class="pt-1">
                    <label for="terms" class="flex cursor-pointer items-center gap-2 text-[11px] text-[#1f1f23]">
                        <input
                            id="terms"
                            type="checkbox"
                            name="terms"
                            value="1"
                            required
                            {{ old('terms') ? 'checked' : '' }}
                            class="h-4 w-4 shrink-0 cursor-pointer rounded-sm border-[#9b9ba3] text-[#5b34f0] focus:ring-[#5b34f0]/30"
                        >
                        <span>I agree to OpenTune's Terms of Service and Privacy Policy</span>
                    </label>
                    @error('terms')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sign Up --}}
                <button type="submit"
                        class="w-full rounded-full bg-[#5b34f0] py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#4b27d8] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2 active:scale-[0.99]">
                    Sign Up
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-2.5 flex items-center gap-3" role="separator">
                <span class="h-px flex-1 bg-[#2b2b2f]/70"></span>
                <span class="text-[10px] text-[#2b2b2f]">or</span>
                <span class="h-px flex-1 bg-[#2b2b2f]/70"></span>
            </div>

            {{-- Google (placeholder, belum ada integrasi OAuth) --}}
            <button type="button"
                    class="w-full rounded-full border border-[#dcdce1] bg-white py-2.5 text-xs font-medium text-[#1f1f23] transition hover:bg-[#f7f7fa] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/20">
                Continue with Google
            </button>

            {{-- Link ke login --}}
            <p class="mt-4 text-center text-[11px] text-[#1f1f23]">
                Already have an account?
                <a href="{{ route('login') }}" class="ml-1 text-xs font-medium text-[#5b34f0] hover:underline">Log In</a>
            </p>
        </div>
    </div>

    <script>
        // Toggle show/hide password
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.dataset.togglePassword);
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.querySelector('[data-eye]').classList.toggle('hidden', !show);
                btn.querySelector('[data-eye-off]').classList.toggle('hidden', show);
            });
        });

        // Indikator centang pada Password & Confirm Password
        (function () {
            var pw = document.getElementById('password');
            var confirm = document.getElementById('password_confirmation');
            var checkPw = document.querySelector('[data-check-password]');
            var checkConfirm = document.querySelector('[data-check-confirm]');

            function update() {
                checkPw.classList.toggle('hidden', pw.value.length < 8);
                checkConfirm.classList.toggle('hidden', !(confirm.value.length > 0 && confirm.value === pw.value));
            }

            pw.addEventListener('input', update);
            confirm.addEventListener('input', update);
            update();
        })();
    </script>
@endsection
