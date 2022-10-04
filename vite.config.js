import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        hmr: {
          protocol: 'ws',
        },
    },
    build: {
        rollupOptions: {
          output: {
            // entryFileNames: `assets/[name].js`,
            // chunkFileNames: `assets/[name].js`,
            assetFileNames: `assets/[name].[ext]`
          }
        }
    },
    plugins: [
        splitVendorChunkPlugin(),
        laravel([
            'resources/css/mail.css',
            'resources/css/app.css',
            'resources/css/styles.css',
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
