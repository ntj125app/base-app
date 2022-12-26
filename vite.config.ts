import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
      host: true, /* Expose to all IP */
      hmr: {
        host: 'docker.local', /* Set base URL for Hot Module Reload */
      },
    },
    plugins: [
      laravel({
        input: ['resources/ts/app.ts'],
        refresh: true,
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
        'vue': 'vue/dist/vue.esm-bundler.js',
      },
    },
    build: {
      rollupOptions: {
        output: {
          compact: true,
          manualChunks: {
            vendor: [
              'lodash',
              'axios',
              '@nathanaelytj/sweetalert2/dist/sweetalert2.all',
              'vue',
              'vue-router',
              'pinia',
              'laravel-echo',
              'pusher-js',
            ],
          },
        }
      }
    },
});