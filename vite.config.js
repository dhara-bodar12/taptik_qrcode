import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      protocol: 'wss', // because ngrok uses https
      host: '062b-2405-201-2029-6071-c422-6a88-9ce1-aac2.ngrok-free.app', // 👈 change this!
      port: 443, // ngrok serves via HTTPS
    },
  },
});
