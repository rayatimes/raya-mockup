import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

// Template mockup portal: masing-masing punya entry CSS & JS sendiri (tidak saling berbagi resource).
const templates = ['default', 'lensa', 'wacana', 'default-mobile', 'lensa-mobile', 'wacana-mobile'];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                ...templates.flatMap((name) => [
                    `resources/templates/${name}/css/app.css`,
                    `resources/templates/${name}/js/app.js`,
                ]),
            ],
            refresh: ['resources/templates/**', 'resources/data/**', 'routes/**'],
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
