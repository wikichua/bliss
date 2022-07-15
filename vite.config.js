import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        hmr: {
          protocol: 'ws',
        },
    },
    plugins: [
        splitVendorChunkPlugin(),
        laravel([
            'resources/css/app.css',
            'resources/css/mail.css',
            'resources/js/app.js',
            'resources/js/alpine.js',
        ]),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
});
