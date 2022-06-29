import './bootstrap';
import './crypt';
import './codemirror';

import 'flowbite';

import TomSelect from 'tom-select/dist/js/tom-select.complete.js';
// import 'tom-select/dist/js/plugins/remove_button';
window.TomSelect = TomSelect;
// window.TomSelect = require('tom-select/dist/js/tom-select.complete.js');

import Swal from 'sweetalert2';
window.Swal = Swal;

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

import FilePond from 'filepond';
window.FilePond = FilePond;
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFilePoster);

import Chart from 'chart.js/auto';
window.Chart = Chart;

import tippy from 'tippy.js';
window.tippy = tippy;
