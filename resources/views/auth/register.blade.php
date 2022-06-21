<div>
    <x-bliss::auth-card>
        <!-- Validation Errors -->
        <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />

        <form wire:submit.prevent="onSubmit">
            @csrf
            <x-honeypot wire:model.defer="honeypotFields" />
            <!-- Name -->
            <div>
                <x-bliss::input id="name" :label="__('Name')" type="text" name="name" :value="old('name')" required autofocus wire:model.defer="name" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-bliss::input id="email" :label="__('Email')" type="email" name="email" :value="old('email')" required wire:model.defer="email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-bliss::input id="password" :label="__('Password')"
                                type="password"
                                name="password"
                                required autocomplete="new-password" wire:model.defer="password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-bliss::input id="password_confirmation" :label="__('Confirm Password')"
                                type="password"
                                name="password_confirmation" required wire:model.defer="password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-bliss::button class="ml-4" wire:loading.target="onSubmit" wire:loading.attr="disabled">
                    {{ __('Register') }}
                </x-bliss::button>
            </div>
        </form>
    </x-bliss::auth-card>
</div>
