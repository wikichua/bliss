<div>
    <x-bliss::auth-card>
        <!-- Session Status -->
        <x-bliss::auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />

        <form x-data="{ password: '' }"
            x-on:submit.prevent="() => {
                let pw = this.password._x_model.get();
                @this.password = Crypt.encrypt(pw);
                $wire.onSubmit();
            }"
        >
            @csrf

            <!-- Email Address -->
            <div>
                <x-bliss::input id="email" type="email" name="email" :label="__('Email')" :value="old('email')" required autofocus wire:model.defer="email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-bliss::input id="password" type="password" name="password" required autocomplete="current-password" x-model="password" :label="__('Password')" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" wire:model.defer="remember" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="remember_me" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Remember me') }}</label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-bliss::button class="ml-3" wire:loading.target="onSubmit" wire:loading.attr="disabled">
                    {{ __('Log in') }}
                </x-bliss::button>
            </div>
        </form>
    </x-bliss::auth-card>
</div>
