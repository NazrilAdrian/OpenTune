@extends('layouts.app', ['minimal' => true])

@section('title', 'Sign In - OpenTune')

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
        <div class="w-full max-w-[420px] rounded-3xl bg-white px-8 py-10 shadow-lg shadow-[#5b3ff0]/10 ring-1 ring-black/[0.03] sm:px-10">

            {{-- Logo --}}
            <a href="{{ url('/landing') }}" class="mb-6 flex items-center justify-center gap-2">
                <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <linearGradient id="otLogoGradLogin" x1="4" y1="4" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#B07CF5" />
                            <stop offset="1" stop-color="#5B34F0" />
                        </linearGradient>
                    </defs>
                    <circle cx="20" cy="20" r="18" stroke="url(#otLogoGradLogin)" stroke-width="3" />
                    <path d="M12 23v-2.5a8 8 0 0 1 16 0V23" stroke="url(#otLogoGradLogin)" stroke-width="2.6" stroke-linecap="round" />
                    <rect x="10.5" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradLogin)" />
                    <rect x="25" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradLogin)" />
                </svg>
                <span class="text-xl font-semibold tracking-tight text-[#5b34f0]">OpenTune</span>
            </a>

            {{-- Heading --}}
            <div class="mb-7 text-center">
                <h1 class="text-[34px] font-bold leading-tight tracking-tight text-[#2b2b2f]">Welcome back</h1>
                <p class="mt-2 text-sm text-[#55555c]">Please enter your detail to sign in.</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" novalidate class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        autocomplete="email"
                        autofocus
                        required
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('email') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                            class="block w-full rounded-md border bg-white py-2.5 pl-3 pr-10 text-xs text-[#2b2b2f] placeholder-[#8a8a92] outline-none transition focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('password') border-red-400 @else border-[#dcdce1] @enderror"
                        >
                        <button type="button" data-toggle-password="password" aria-label="Show or hide password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#3a3a40] hover:text-[#5b34f0]">
                            {{-- eye-off (default: password tersembunyi) --}}
                            <svg data-eye-off class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3l18 18" />
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" />
                                <path d="M9.4 5.5A9.8 9.8 0 0 1 12 5c5 0 8.5 3.5 10 7a13.5 13.5 0 0 1-3.2 4.3M6.1 6.1A13.6 13.6 0 0 0 2 12c1.5 3.5 5 7 10 7a9.7 9.7 0 0 0 4-.9" />
                            </svg>
                            {{-- eye (password terlihat) --}}
                            <svg data-eye class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12c1.5-3.5 5-7 10-7s8.5 3.5 10 7c-1.5 3.5-5 7-10 7S3.5 15.5 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me & Forgot password --}}
                <div class="flex items-center justify-between">
                    <label for="remember" class="flex cursor-pointer items-center gap-2 text-xs text-[#1f1f23]">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-3.5 w-3.5 cursor-pointer rounded-sm border-[#9b9ba3] text-[#5b34f0] focus:ring-[#5b34f0]/30"
                        >
                        Remember Me
                    </label>
                    <a href="#" class="text-xs font-medium text-[#5b34f0] hover:underline">Forgot Password?</a>
                </div>
                @error('remember')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror

                {{-- Sign in --}}
                <button type="submit"
                        class="w-full rounded-full bg-[#5b34f0] py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#4b27d8] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2 active:scale-[0.99]">
                    Sign in
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-3 flex items-center gap-3" role="separator">
                <span class="h-px flex-1 bg-[#2b2b2f]/70"></span>
                <span class="text-[10px] text-[#2b2b2f]">or</span>
                <span class="h-px flex-1 bg-[#2b2b2f]/70"></span>
            </div>

            {{-- Google (placeholder, belum ada integrasi OAuth) --}}
            <button type="button"
                    class="w-full rounded-full border border-[#dcdce1] bg-white py-2.5 text-xs font-medium text-[#1f1f23] transition hover:bg-[#f7f7fa] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/20">
                Continue with Google
            </button>

            {{-- Link ke register --}}
            <p class="mt-6 text-center text-[11px] text-[#1f1f23]">
                Don't have an account?
                <a href="{{ route('register') }}" class="ml-1 text-xs font-medium text-[#5b34f0] hover:underline">Sign Up</a>
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
    </script>
@endsection
