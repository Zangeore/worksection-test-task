import { defineConfig } from 'vite';
import path from 'path';
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js';

export default defineConfig({
  plugins: [
    cssInjectedByJsPlugin({
      relativeCSSInjection: true
    })
  ],
  build: {
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'resources/js/main.js'),
      },
      output: {
        entryFileNames: 'js/main.js',
        dir: 'public',
      },
    },
    emptyOutDir: false,
    outDir: 'public',
    minify: false,
    cssCodeSplit: true,
  }
});
