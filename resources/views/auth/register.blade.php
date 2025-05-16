<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box p-6 w-xs">
            <legend class="fieldset-legend text-lg font-semibold">{{ __('Register') }}</legend>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="label">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" 
                       class="input input-bordered w-full" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="label">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
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

            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('login') }}" 
                   class="link hover:underline">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="btn btn-neutral">
                    {{ __('Register') }}
                </button>
            </div>
        </fieldset>
    </form>
</x-guest-layout>
