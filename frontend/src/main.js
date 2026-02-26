import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Bootstrap base
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

// SB Admin
import './assets/sbadmin/css/sb-admin-2.min.css'
import './assets/sbadmin/vendor/fontawesome-free/css/all.min.css'

// Crear l'aplicació Vue
const app = createApp(App)

// Usar el router
app.use(router)

// Montar l'app
app.mount('#app')
