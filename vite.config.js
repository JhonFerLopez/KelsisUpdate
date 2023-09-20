import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [/*'packages/css/app.css',*/ 'packages/Webkul/Velocity/src/Resources/assets/js/app.js'],
            refresh: true,
        }),
    ],
});