<div>
    <x-bliss::auth-card>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <!-- Validation Errors -->
        <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />

        <form wire:submit.prevent="onSubmit">
            @csrf

            <!-- Password -->
            <div>
                <x-bliss::input id="password" :label="__('Password')"
                                type="password"
                                name="password"
                                required autocomplete="current-password" wire:model.defer="password"/>
            </div>

            <div class="flex justify-end mt-4">
                <x-bliss::button>
                    {{ __('Confirm') }}
                </x-bliss::button>
            </div>
        </form>
    </x-bliss::auth-card>
</div>
