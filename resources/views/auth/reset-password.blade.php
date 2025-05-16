<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box p-6 w-xs">
            <legend class="fieldset-legend text-lg font-semibold">{{ __('Reset Password') }}</legend>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="label">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                    class="input input-bordered w-full" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="label">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="input input-bordered w-full" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="input input-bordered w-full" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="btn btn-neutral">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </fieldset>
    </form>
</x-guest-layout>
