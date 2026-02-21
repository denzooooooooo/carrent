@extends('layouts.app')

@section('title', __('Register') . ' - Carré Premium')

@push('styles')
<!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<style>
  .iti {
    width: 100%;
  }
  .iti__flag-container {
    padding: 0;
  }
  .iti__selected-flag {
    padding: 0 8px 0 12px;
  }
  #phone {
    padding-left: 52px !important;
  }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 flex">
  <!-- Image Section -->
  <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-purple-600 to-amber-600 items-center justify-center p-12">
    <div class="max-w-md text-center">
      <div class="mb-8">
        <svg class="mx-auto h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
      </div>
      <h1 class="text-4xl font-black text-white mb-4">{{ __('Join Carré Premium') }}</h1>
      <p class="text-white/90 text-lg">
        {{ __('Create your account and access exclusive offers and unique travel experiences.') }}
      </p>
      <div class="mt-8 grid grid-cols-2 gap-4 text-white/80">
        <div class="text-center">
          <div class="text-2xl font-bold">24/7</div>
          <div class="text-sm">{{ __('Support') }}</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold">100%</div>
          <div class="text-sm">{{ __('Secure') }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Section -->
  <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
    <div>
      <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-r from-purple-600 to-amber-600">
        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
      </div>
      <h2 class="mt-6 text-center text-3xl font-black text-gray-900">
        {{ __('Create your account') }}
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        {{ __('Or') }}
        <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
          {{ __('sign in to an existing account') }}
        </a>
      </p>
    </div>
    <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
      @csrf
      <div class="rounded-md shadow-sm -space-y-px">
        <div>
          <label for="civility" class="sr-only">{{ __('Title') }}</label>
          <select
            id="civility"
            name="civility"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
          >
            <option value="">{{ __('Select your title') }}</option>
            <option value="Monsieur" {{ old('civility') == 'Monsieur' ? 'selected' : '' }}>{{ __('Mr.') }}</option>
            <option value="Madame" {{ old('civility') == 'Madame' ? 'selected' : '' }}>{{ __('Mrs.') }}</option>
            <option value="Mademoiselle" {{ old('civility') == 'Mademoiselle' ? 'selected' : '' }}>{{ __('Miss') }}</option>
          </select>
        </div>
        <div>
          <label for="first_name" class="sr-only">{{ __('First name') }}</label>
          <input
            id="first_name"
            name="first_name"
            type="text"
            autocomplete="given-name"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('First name') }}"
            value="{{ old('first_name') }}"
          />
        </div>
        <div>
          <label for="last_name" class="sr-only">{{ __('Last name') }}</label>
          <input
            id="last_name"
            name="last_name"
            type="text"
            autocomplete="family-name"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('Last name') }}"
            value="{{ old('last_name') }}"
          />
        </div>
        <div>
          <label for="email" class="sr-only">{{ __('Email address') }}</label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('Email address') }}"
            value="{{ old('email') }}"
          />
        </div>
        <div>
          <label for="phone" class="sr-only">{{ __('Phone') }}</label>
          <input
            id="phone"
            name="phone"
            type="tel"
            autocomplete="tel"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('Phone number') }}"
            value="{{ old('phone') }}"
          />
          <input type="hidden" name="phone_country_code" id="phone_country_code" value="+225">
        </div>
        <div>
          <label for="password" class="sr-only">{{ __('Password') }}</label>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('Password') }}"
          />
        </div>
        <div>
          <label for="password_confirmation" class="sr-only">{{ __('Confirm password') }}</label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
            placeholder="{{ __('Confirm password') }}"
          />
        </div>
      </div>

      <div class="flex items-center">
        <input
          id="terms"
          name="terms"
          type="checkbox"
          required
          class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
        />
        <label for="terms" class="ml-2 block text-sm text-gray-900">
          {{ __('I accept the') }}
          <a href="{{ route('terms') }}" class="text-purple-600 hover:text-purple-500">{{ __('terms and conditions') }}</a>
          {{ __('and the') }}
          <a href="{{ route('privacy') }}" class="text-purple-600 hover:text-purple-500">{{ __('privacy policy') }}</a>
        </label>
      </div>

      <div>
        <button
          type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all"
        >
          {{ __('Create my account') }}
        </button>
      </div>

      @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                {{ __('Registration error') }}
              </h3>
              <div class="mt-2 text-sm text-red-700">
                <ul role="list" class="list-disc pl-5 space-y-1">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      @endif
    </form>

    

      <div class="mt-6 space-y-3">
        <a
          href="#"
          class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200"
        >
          <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          {{ __('Sign up with Google') }}
        </a>

        <a
          href="#"
          class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200"
        >
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
          {{ __('Sign up with Facebook') }}
        </a>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<!-- intl-tel-input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const phoneInput = document.querySelector("#phone");
  const phoneCountryCodeInput = document.querySelector("#phone_country_code");
  
  // Initialize intl-tel-input
  const iti = window.intlTelInput(phoneInput, {
    // Options
    initialCountry: "ci", // Côte d'Ivoire par défaut
    preferredCountries: ["ci", "fr", "sn", "ml", "bf", "ne", "tg", "bj", "gn"], // Pays africains francophones en priorité
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js",
    autoPlaceholder: "aggressive",
    formatOnDisplay: true,
    nationalMode: false,
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
      return "ex: " + selectedCountryPlaceholder;
    }
  });

  // Update hidden input when country changes
  phoneInput.addEventListener('countrychange', function() {
    const countryData = iti.getSelectedCountryData();
    phoneCountryCodeInput.value = '+' + countryData.dialCode;
  });

  // Set initial country code
  const initialCountryData = iti.getSelectedCountryData();
  phoneCountryCodeInput.value = '+' + initialCountryData.dialCode;

  // Validation on form submit
  const form = phoneInput.closest('form');
  form.addEventListener('submit', function(e) {
    // Get the full international number
    const fullNumber = iti.getNumber();
    
    // Validate the number
    if (!iti.isValidNumber()) {
      e.preventDefault();
      alert('{{ __("Please enter a valid phone number") }}');
      phoneInput.focus();
      return false;
    }
    
    // Update the phone input with the full international number
    phoneInput.value = fullNumber;
  });

  // Format as user types
  phoneInput.addEventListener('blur', function() {
    if (phoneInput.value.trim()) {
      const fullNumber = iti.getNumber();
      if (iti.isValidNumber()) {
        phoneInput.value = fullNumber;
      }
    }
  });
});
</script>
@endpush
@endsection
