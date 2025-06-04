import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            buildDirectory: 'dist', // Ensure assets are built to public/dist
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/dist', // Output to public/dist for Vercel
        emptyOutDir: true, // Clear the output directory before building
        manifest: true, // Generate manifest.json for Laravel integration
        rollupOptions: {
            output: {
                // Ensure consistent chunk names for Vercel
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name].js',
                assetFileNames: ({ name }) => {
                    if (/\.css$/.test(name ?? '')) {
                        return 'css/[name][extname]';
                    }
                    return 'assets/[name][extname]';
                },
            },
        },
    },
});
