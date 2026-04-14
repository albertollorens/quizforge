import { fileURLToPath, URL } from 'node:url'
import { resolve } from 'path'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  build: {
    outDir: resolve(__dirname, '../backend/public'),
    emptyOutDir: true
  },
  server: {
    port: 5173,             // Puerto del frontend dev
    proxy: {
      // Todo lo que vaya a /api será redirigido a Slim
      '/api': {
        target: 'http://quizforge.local', // URL de tu backend Slim
        changeOrigin: true,
        //rewrite: path => path.replace(/^\/api/, '/api')
      }
    }
  }
})
