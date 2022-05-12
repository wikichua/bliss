<div x-data="{
        reauthPassword:'',
        init() {
            if (this.reauthEnabled === true) {
                $wire.on('checkLastAuth', (reauthExpired) => {
                    if (reauthExpired) {
                        $data.openReauthModal = true;
                    } else {
                        let ap = this;
                        ap[this.method](...this.argument);
                    }
                });
                $wire.on('canProceedProcess', (confirmedPassword) => {
                    if (confirmedPassword) {
                        $data.openReauthModal = false;
                        let ap = this;
                        ap[this.method](...this.argument);
                    }
                });
            }
        },
        onSubmitPrevent () {
            let pw = this.reauthPassword;
            $wire.reauthPassword = Crypt.encrypt(pw);
            $wire.onSubmit();
        },
    }"
>
    <x-bliss::reauth-modal>
        <x-slot name="actionButtons">
            <a class="action-button" href="{{ route('user.create') }}">Create <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></a>
        </x-slot>
        <x-slot name="modalTitle">
            Reauthenticate Password
        </x-slot>
        <x-slot name="modalBody">
            <x-bliss::search-input type="password" id="reauthPassword" label="Confirm Password" x-model="reauthPassword" x-ref="reauthPassword" autocomplete="off" />
        </x-slot>
        <x-slot name="modalButtons">
            <button type="button" class="modal-button" x-on:click="onSubmitPrevent" x-on:click="open = false">Submit</button>
        </x-slot>
    </x-bliss::search-modal>

</div>
