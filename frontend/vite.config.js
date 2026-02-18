import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    outDir: resolve(__dirname, '../backend/public'),
    emptyOutDir: true
  }
})


//Per a treballar amb npm run dev redireccionant les peticions a la api al servidor wamp
/*server: {
    port: 5173,             // Puerto del frontend dev
    proxy: {
      // Todo lo que vaya a /api será redirigido a Slim
      '/api': {
        target: 'http://quizforge.local', // URL de tu backend Slim
        changeOrigin: true,
        //rewrite: path => path.replace(/^\/api/, '/api')
      }
    }
  }*/
