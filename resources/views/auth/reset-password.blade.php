<div>
    <x-bliss::auth-card>
        <!-- Validation Errors -->
        <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />

        <form wire:submit.prevent="onSubmit">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" :value="$token">

            <!-- Email Address -->
            <div>
                <x-bliss::input id="email" :label="__('Email')" type="email" name="email" :value="$email" required autofocus wire:model.defer="email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-bliss::input id="password" :label="__('Password')" type="password" name="password" required wire:model.defer="password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-bliss::input id="password_confirmation" :label="__('Confirm Password')"
                                    type="password"
                                    name="password_confirmation" required wire:model.defer="password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-bliss::button>
                    {{ __('Reset Password') }}
                </x-bliss::button>
            </div>
        </form>
    </x-bliss::auth-card>
</div>
