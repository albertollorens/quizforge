import { createApp } from 'vue'
import i18n from './i18n'
import App from './App.vue'
import router from './router'

// Bootstrap base
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

// SB Admin
import './assets/sbadmin/css/sb-admin-2.min.css'
import './assets/sbadmin/vendor/fontawesome-free/css/all.min.css'

// CSS global propi
import './assets/main.css'


// Crear l'aplicació Vue
const app = createApp(App)
app.use(i18n)    // Usar i18n
app.use(router)  // Usar el router

//Per a debug, llevar en PRODUCCIÓ
app.config.devtools = true

// Montar l'app
app.mount('#app')

/* 🔍 Script per comprovar que els JSON estan incrustats
console.log('Traduccions disponibles a i18n:', i18n.global.availableLocales)
console.log('Claus i contingut EN:', i18n.global.getLocaleMessage('en'))
console.log('Claus i contingut ES:', i18n.global.getLocaleMessage('es'))
console.log('Claus i contingut VA:', i18n.global.getLocaleMessage('va'))*/