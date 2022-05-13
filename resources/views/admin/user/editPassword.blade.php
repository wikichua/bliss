<div id="wire">
    <x-bliss::content-card x-data="{
        password: '',
        password_confirmation: '',
        confirmSubmit () {
            let pw = $refs.password.value;
            $wire.password = Crypt.encrypt(pw);
            let pwc = $refs.password_confirmation.value;
            $wire.password_confirmation = Crypt.encrypt(pwc);
            $wire.onSubmit();
        },
        onSubmitPrevent () {
            this.argument = [];
            this.method = 'confirmSubmit';

            if (this.reauthEnabled === true) {
                $wire.emitTo('reauth', 'checkShouldConfirmPassword');
            } else {
                this.confirmSubmit();
            }
        },
    }">
        <x-slot name="left">
            <x-bliss::form
                x-on:submit.prevent="onSubmitPrevent"
            >
                <x-bliss::form-input type="password" x-ref="password" x-model="password" label="Password" />
                <x-bliss::form-input type="password" x-ref="password_confirmation" x-model="password_confirmation" label="Password Confirmation" />

                <x-slot name="buttons">
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="history.back()">
                        Back
                    </x-bliss::form-link>
                    <x-bliss::form-button type="submit">
                        Submit
                    </x-bliss::form-button>
                </x-slot>
            </x-bliss::form>
        </x-slot>

        <x-slot name="right">
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
