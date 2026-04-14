import './assets/main.css'
// Import Swiper styles
/*import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'*/
import 'jsvectormap/dist/jsvectormap.css'
import 'flatpickr/dist/flatpickr.css'
import 'leaflet/dist/leaflet.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VueApexCharts from 'vue3-apexcharts'
import i18n from './i18n.ts'

const app = createApp(App)
app.use(i18n)    // Usar i18n

app.use(router)
app.use(VueApexCharts)

app.mount('#app')
