import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        https: true, // Add this line to enable HTTPS
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/filament/studio/theme.css',
                'resources/css/filament/dashboard/theme.css'
            ],
            refresh: true,
        }),
    ],
});
