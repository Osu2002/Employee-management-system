import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/Backend/app.js',
                'resources/js/Frontend/app.js', // changed for frontend 
            ],
            refresh: [
                ...refreshPaths,
            ],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js/Backend',
            '@@': '/resources/js/Frontend',  // newly add for forntend 
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue.m'),
        }
    }
});