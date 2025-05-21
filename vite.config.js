import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Force HTTPS for assets
    server: {
        https: true,
    },
    // Ensure proper asset URL protocol
    build: {
        manifest: true,
        // Force assets to use the same protocol as the page
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
