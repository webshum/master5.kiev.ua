import dotenv from 'dotenv';
import { defineConfig } from 'vite';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

dotenv.config();

export default defineConfig({
  publicDir: 'resources/static',
  build: {
    assetsDir: '',
    emptyOutDir: true,
    manifest: true,
    outDir: `public/themes/${process.env.WP_DEFAULT_THEME}/assets`,
    rollupOptions: {
      input: 'resources/js/index.js',
    },
  },
  plugins: [
    {
      name: 'php',
      handleHotUpdate({ file, server }) {
        if (file.endsWith('.php')) {
          server.ws.send({ type: 'full-reload', path: '*' });
        }
      },
    },
  ],
  css: {
    postcss: {
      plugins: [
        tailwindcss,
        autoprefixer,
      ],
    }
  }
});
