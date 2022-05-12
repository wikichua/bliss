<div>
    <x-bliss::auth-card>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-bliss::auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />

        <form wire:submit.prevent="onSubmit">
            @csrf

            <!-- Email Address -->
            <div>
                <x-bliss::input id="email" type="email" name="email" :value="$email" required autofocus wire:model.defer="email" :label="__('Email')" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-bliss::button>
                    {{ __('Email Password Reset Link') }}
                </x-bliss::button>
            </div>
        </form>
    </x-bliss::auth-card>
</div>
