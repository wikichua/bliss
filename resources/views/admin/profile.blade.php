<div id="wire">
    <x-bliss::content-card x-data="{
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                    onSavePassword () {
                        let cpw = this.current_password;
                        $wire.current_password = Crypt.encrypt(cpw);
                        let pw = this.password;
                        $wire.password = Crypt.encrypt(pw);
                        let pwc = this.password_confirmation;
                        $wire.password_confirmation = Crypt.encrypt(pwc);
                        $wire.onSavePassword();
                    },
                }">
        <x-slot name="left">
            <div class="row-auto mb-3">
                <x-bliss::form wire:submit.prevent="onSaveAvatar">
                    <x-bliss::form-file label="Avatar" wire:model.defer="avatar" :file="$avatar" mode="profile" profileClass="w-36 h-36 rounded-lg" accept="image/jpg,image/jpeg,image/png" />

                    <x-slot name="buttons">
                        <x-bliss::form-button type="submit">
                            Save
                        </x-bliss::form-button>
                    </x-slot>
                </x-bliss::form>
            </div>

            <div class="row-auto mb-3">
                <x-bliss::form wire:submit.prevent="onSaveProfile">
                    <x-bliss::form-input type="text" wire:model.defer="name" label="Full Name" />
                    <x-bliss::form-input type="email" wire:model.defer="email" label="Email" />
                    <x-bliss::form-select wire:model.defer="timezone" label="Timezone" :options="timezones()" />

                    <x-slot name="buttons">
                        <x-bliss::form-button type="submit">
                            Save
                        </x-bliss::form-button>
                    </x-slot>
                </x-bliss::form>
            </div>

            <div class="row-auto mb-3">
                <x-bliss::form x-on:submit.prevent="onSavePassword" autocomplete="off">
                    <x-bliss::form-input type="password" x-model="current_password" label="Current Password" />
                    <x-bliss::form-input type="password" x-model="password" label="Password" />
                    <x-bliss::form-input type="password" x-model="password_confirmation" label="Password Confirmation" />

                    <x-slot name="buttons">
                        <x-bliss::form-button type="submit">
                            Save
                        </x-bliss::form-button>
                    </x-slot>
                </x-bliss::form>
            </div>
        </x-slot>

        <x-slot name="right">
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
