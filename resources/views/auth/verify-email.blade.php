<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Email verification is disabled for this application. Accounts are active immediately after registration.') }}
        </div>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Log In') }}</a>
        </div>
    </x-authentication-card>
</x-guest-layout>
