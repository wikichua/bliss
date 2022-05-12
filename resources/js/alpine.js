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

Alpine.start();
