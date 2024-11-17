import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Allows binding to all network interfaces
        port: 5173,      // Optional: Set a custom port
        hmr: {
            host: '34.101.240.136', // Replace with your machine's IP
        },
    },
});

