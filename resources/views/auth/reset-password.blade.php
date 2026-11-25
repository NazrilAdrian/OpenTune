@extends('layouts.app', ['minimal' => true])

@section('title', 'Reset Password - OpenTune')

@section('content')
    <div class="relative z-10 flex min-h-[calc(100vh-4rem)] items-center justify-center">
        <div class="w-full max-w-[420px] rounded-3xl bg-white px-8 py-10 shadow-lg shadow-[#5b3ff0]/10 ring-1 ring-black/[0.03] sm:px-10">
            <a href="{{ route('landing') }}" class="mb-6 flex items-center justify-center gap-2">
                <img src="{{ asset('images/opentune-logo.png') }}" alt="Logo OpenTune" class="h-8 w-8 object-contain" />
                <span class="text-xl font-semibold tracking-tight text-[#5b34f0]">OpenTune</span>
            </a>

            <div class="mb-7 text-center">
                <h1 class="text-2xl font-bold text-[#2b2b2f]">Reset Password</h1>
                <p class="mt-2 text-sm text-[#55555c]">Choose a new password with at least 8 characters.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', request('email')) }}"
                        autocomplete="email"
                        maxlength="100"
                        required
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] outline-none focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('email') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">New Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] outline-none focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('password') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                        required
                        class="block w-full rounded-md border border-[#dcdce1] bg-white px-3 py-2.5 text-xs text-[#2b2b2f] outline-none focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20"
                    >
                </div>

                <button type="submit" class="w-full rounded-full bg-[#5b34f0] py-2.5 text-xs font-medium text-white hover:bg-[#4b27d8]">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection
