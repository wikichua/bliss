export default (reauthEnabled) => ({
    darkMode: Alpine.$persist(false).as('darkMode'),
    openReauthModal: false,
    reauthEnabled: reauthEnabled,
    argument: [],
    method: '',
    toast: null,

    init() {
        this.$nextTick(() => {
            if (this.toast == null) {
                this.toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            }

            window.addEventListener('flash-status', (evt) => {
                this.toast.fire({
                    icon: evt.detail.icon || 'success',
                    title: evt.detail.status
                });
            });
        });
    },

    // emit without confirmation
    emitWithoutConfirm (...argument) {
        this.argument = argument;
        this.method = 'proceedEmit';
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));

        if (this.reauthEnabled === true) {
            wire.emitTo('reauth', 'lastAuth');
        } else {
            this.proceedEmit(...argument);
        }
    },
    // emit without confirm and reauth
    emitWithoutConfirmAndReauth (...argument) {
        let originalReauthEnabled = this.reauthEnabled;
        this.reauthEnabled = false;
        this.emitWithoutConfirm (...argument);
        this.reauthEnabled = originalReauthEnabled;
    },

    // emitTo without confirm
    emitToWithoutConfirm (...argument) {
        this.argument = argument;
        this.method = 'proceedEmitTo';
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));

        if (this.reauthEnabled === true) {
            wire.emitTo('reauth', 'lastAuth');
        } else {
            this.proceedEmitTo(...argument);
        }
    },
    // emitTo without confirm and reauth
    emitToWithoutConfirmAndReauth (...argument) {
        let originalReauthEnabled = this.reauthEnabled;
        this.reauthEnabled = false;
        this.emitToWithoutConfirm (...argument);
        this.reauthEnabled = originalReauthEnabled;
    },

    // emit with confirm
    emitWithConfirm (...argument) {
        this.argument = argument;
        this.method = 'confirmToProceedEmit';
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));

        if (this.reauthEnabled === true) {
            wire.emitTo('reauth', 'lastAuth');
        } else {
            this.confirmToProceedEmit(...argument);
        }
    },
    // emit with confirm no reauth
    emitWithConfirmNoReauth (...argument) {
        let originalReauthEnabled = this.reauthEnabled;
        this.reauthEnabled = false;
        this.emitWithConfirm (...argument);
        this.reauthEnabled = originalReauthEnabled;
    },

    // emitTo with confirm
    emitToWithConfirm (...argument) {
        this.argument = argument;
        this.method = 'confirmToProceedEmitTo';
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));

        if (this.reauthEnabled === true) {
            wire.emitTo('reauth', 'lastAuth');
        } else {
            this.confirmToProceedEmitTo(...argument);
        }
    },
    // emitTo with confirm no reauth
    emitToWithConfirmNoReauth (...argument) {
        let originalReauthEnabled = this.reauthEnabled;
        this.reauthEnabled = false;
        this.emitToWithConfirm (...argument);
        this.reauthEnabled = originalReauthEnabled;
    },

    // direct proceed emit
    proceedEmit (...argument) {
        // this.$wire.emit(...argument);
        // this.$wire.onSubmit(...argument);
        // const wire = this.$wire;
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));
        let call = '';
        let otherArguments = [];
        _.forEach(argument, (arg, index) => {
            if (index == 0) {
                call = arg;
            } else {
                otherArguments.push(arg);
            }
        });
        wire[call](...otherArguments);
        this.argument = [];
    },
    // direct proceed emit with confirmation
    confirmToProceedEmit (...argument) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm to proceed this.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, please proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                // this.$wire.emit(...argument);
                this.proceedEmit(...argument);
            }
        });
        this.argument = [];
    },

    // direct proceed emit to
    proceedEmitTo (...argument) {
        // const wire = this.$wire;
        const wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));
        wire.emitTo(...argument);
        this.argument = [];
    },
    // direct proceed emit to with confirmation
    confirmToProceedEmitTo (...argument) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm to proceed this.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, please proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.proceedEmitTo(...argument);
            }
        });
        this.argument = [];
    },
});
