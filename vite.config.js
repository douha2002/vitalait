import { defineConfig } from 'vite';
import laravel from 'vite-plugin-laravel';

export default defineConfig({
  plugins: [laravel()],
  build: {
    outDir: 'public/build',
    assetsDir: 'assets', // ensure assets are output to the correct folder
  },
});