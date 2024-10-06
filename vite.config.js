import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        https: true, // Add this line to enable HTTPS
        key: "E:/wamp/bin/apache/apache2.4.59/conf/key/obv2.key",
        cert: "E:/wamp/bin/apache/apache2.4.59/conf/key/obv2.cert"
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
