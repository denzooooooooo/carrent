@extends('layouts.app')

@section('title', __('Account Verification') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-purple-600 to-amber-600">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-black text-gray-900">
                {{ __('Verify your account') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('A verification code has been sent to your email') }}
            </p>
            <p class="mt-1 text-sm font-medium text-purple-600">
                {{ $email }}
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Warning Message -->
        @if(session('warning'))
            <div class="rounded-md bg-yellow-50 border border-yellow-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">
                            {{ session('warning') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Message -->
        @if(session('info'))
            <div class="rounded-md bg-blue-50 border border-blue-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            {{ session('info') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ __('Error') }}
                    </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Verification Form -->
        <form class="mt-8 space-y-6" action="{{ route('verify.code') }}" method="POST">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Verification code') }}
                </label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    required
                    autofocus
                    class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-center text-2xl font-bold tracking-widest"
                    placeholder="000000"
                    value="{{ old('code') }}"
                />
                <p class="mt-2 text-xs text-gray-500 text-center">
                    {{ __('Enter the 6-digit code received by email') }}
                </p>
            </div>

            <div>
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all"
                >
                    {{ __('Verify my account') }}
                </button>
            </div>
        </form>

        <!-- Resend Options -->
        <div class="mt-6 space-y-4">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">
                        {{ __('Did not receive the code?') }}
                    </span>
                </div>
            </div>

            <!-- Resend Email Button -->
            <form action="{{ route('verify.resend') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    id="resend-btn"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ __('Resend code') }}
                    <span id="countdown" class="ml-2 text-purple-600 font-semibold"></span>
                </button>
            </form>

            <!-- Change Method to SMS -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">
                    {{ __('Prefer to receive the code by SMS?') }}
                </p>
                <form action="{{ route('verify.change-method') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="method" value="sms">
                    <button
                        type="submit"
                        class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-500"
                    >
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        {{ __('Receive by SMS') }} ({{ $phone }})
                    </button>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        {{ __('Need help?') }}
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>
                            {{ __('If you encounter difficulties, contact our support:') }}
                            <br>
                            <a href="mailto:support@carrepremium.ci" class="font-medium underline">support@carrepremium.ci</a>
                            <br>
                            <a href="tel:+2252721594258" class="font-medium underline">+225 27 21 59 42 58</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Link -->
        <div class="text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus and auto-submit on 6 digits
    const codeInput = document.getElementById('code');
    const form = codeInput.closest('form');
    
    codeInput.addEventListener('input', function(e) {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Auto-submit when 6 digits entered
        if (this.value.length === 6) {
            setTimeout(() => {
                form.submit();
            }, 300);
        }
    });

    // Countdown timer for resend button (60 seconds)
    const resendBtn = document.getElementById('resend-btn');
    const countdownSpan = document.getElementById('countdown');
    
    @if(session('last_verification_sent'))
        const lastSent = new Date('{{ session('last_verification_sent') }}');
        const now = new Date();
        const secondsElapsed = Math.floor((now - lastSent) / 1000);
        const secondsRemaining = Math.max(0, 60 - secondsElapsed);
        
        if (secondsRemaining > 0) {
            startCountdown(secondsRemaining);
        }
    @endif
    
    function startCountdown(seconds) {
        resendBtn.disabled = true;
        resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        let remaining = seconds;
        countdownSpan.textContent = `(${remaining}s)`;
        
        const interval = setInterval(() => {
            remaining--;
            if (remaining > 0) {
                countdownSpan.textContent = `(${remaining}s)`;
            } else {
                clearInterval(interval);
                countdownSpan.textContent = '';
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }, 1000);
    }
    
    // Start countdown on resend button click
    resendBtn.addEventListener('click', function() {
        if (!this.disabled) {
            startCountdown(60);
        }
    });
});
</script>
@endpush
@endsection
