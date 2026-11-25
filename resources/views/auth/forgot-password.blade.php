@extends('layouts.app', ['minimal' => true])

@section('title', 'Forgot Password - OpenTune')

@section('content')
    <div class="relative z-10 flex min-h-[calc(100vh-4rem)] items-center justify-center">
        <div class="w-full max-w-[420px] rounded-3xl bg-white px-8 py-10 shadow-lg shadow-[#5b3ff0]/10 ring-1 ring-black/[0.03] sm:px-10">
            <a href="{{ route('landing') }}" class="mb-6 flex items-center justify-center gap-2">
                <img src="{{ asset('images/opentune-logo.png') }}" alt="Logo OpenTune" class="h-8 w-8 object-contain" />
                <span class="text-xl font-semibold tracking-tight text-[#5b34f0]">OpenTune</span>
            </a>

            <div class="mb-7 text-center">
                <h1 class="text-2xl font-bold text-[#2b2b2f]">Forgot Password?</h1>
                <p class="mt-2 text-sm text-[#55555c]">Enter your email and we will send reset instructions if the account exists.</p>
            </div>

            @if (session('status'))
                <p role="status" class="mb-4 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium text-[#1f1f23]">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        maxlength="100"
                        required
                        autofocus
                        class="block w-full rounded-md border bg-white px-3 py-2.5 text-xs text-[#2b2b2f] outline-none focus:border-[#5b34f0] focus:ring-2 focus:ring-[#5b34f0]/20 @error('email') border-red-400 @else border-[#dcdce1] @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full rounded-full bg-[#5b34f0] py-2.5 text-xs font-medium text-white hover:bg-[#4b27d8]">
                    Send Reset Link
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-[#1f1f23]">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-[#5b34f0] hover:underline">Log In</a>
            </p>
        </div>
    </div>
@endsection
