import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            buildDirectory: 'dist',
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
    base: '/', // Ensure assets are referenced relative to the root
    build: {
        outDir: 'public/dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name].js',
                assetFileNames: ({ name }) => {
                    if (/\.css$/.test(name ?? '')) {
                        return 'css/[name]-[hash][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
    server: {
        https: false, // Local dev uses HTTP; Vercel handles HTTPS
        host: '0.0.0.0',
    },
});
