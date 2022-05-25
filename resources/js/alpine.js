import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import persist from '@alpinejs/persist'

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.plugin(persist);

import alpineInitHtml from './alpineInitHtml';
Alpine.data('xHtml', alpineInitHtml);

import alpineInitTable from './alpineInitTable';
Alpine.data('xTable', alpineInitTable);

document.addEventListener('alpine:init', () => {
    // Magic: $tooltip
    Alpine.magic('tooltip', el => message => {
        let instance = tippy(el, { content: message, trigger: 'manual' });

        instance.show();

        setTimeout(() => {
            instance.hide()

            setTimeout(() => instance.destroy(), 150)
        }, 2000);
    });

    // Directive: x-tooltip
    Alpine.directive('tooltip', (el, { expression }) => {
        tippy(el, { content: expression });
    });
});

Alpine.start();
