<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box p-6 w-xs">
            <legend class="fieldset-legend text-lg font-semibold">{{ __('Reset Password') }}</legend>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="label">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="input input-bordered w-full" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="btn btn-neutral">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </fieldset>
    </form>
</x-guest-layout>
