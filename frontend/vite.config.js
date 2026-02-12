import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173,             // Puerto del frontend dev
    proxy: {
      // Todo lo que vaya a /api será redirigido a Slim
      '/api': {
        target: 'http://quizforge.local', // URL de tu backend Slim
        changeOrigin: true,
        rewrite: path => path.replace(/^\/api/, '/api')
      }
    }
  }
})
