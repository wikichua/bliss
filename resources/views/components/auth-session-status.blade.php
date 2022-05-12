@props(['status'])

@if ($status)
<span
    x-data="{
        init() {
            this.$nextTick(() => {
                this.toast.fire({
                    icon: 'success',
                    title: '{{ $status }}'
                });
            });
        },
    }"
>
</span>
@endif
