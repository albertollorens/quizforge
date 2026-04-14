<template>
  <admin-layout>
    <div class="grid grid-cols-12 gap-4 md:gap-6">

      <!-- SOLO ADMIN -->
      <template v-if="isAdmin">
        <div class="col-span-12">
          <div class="bg-white dark:bg-slate-900 shadow rounded-2xl p-6 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold">
                Benvingut, {{ user.username }} ({{ user.rol }}) 👋
              </h2>
            </div>
          </div>
        </div>

        <div class="col-span-12 space-y-6 xl:col-span-7">
          <quiz-metrics />
          <monthly-target />
        </div>
        <div class="col-span-12 xl:col-span-5">
          <monthly-sale />
        </div>

        <div class="col-span-12">
          <statistics-chart />
        </div>

        <div class="col-span-12 xl:col-span-5">
          <customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
          <recent-orders />
        </div>
      </template>

      <!-- USUARIO NORMAL -->
      <template v-else>
        <!-- WELCOME CARD -->
        <div class="col-span-12">
          <div class="bg-white dark:bg-slate-900 shadow rounded-2xl p-6 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold">
                Benvingut, {{ user.username }} ({{ user.rol }})👋
              </h2>
              <p class="text-slate-500 mt-1">
                Continua creant quizzes i millorant els resultats.
              </p>
            </div>

            <div class="text-right">
              <span class="text-sm text-slate-400">Pla actual</span>
              <p class="text-lg font-semibold text-blue-600">
                {{ user.planStr }}
              </p>
            </div>
          </div>
        </div>

        <!-- PLAN CARD -->
        <div v-if="user.plan==='free'" class="col-span-12 md:col-span-6 xl:col-span-4">
          <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow rounded-2xl p-6">
            <h3 class="text-xl font-bold">Millora el teu pla 🚀</h3>

            <p class="mt-2 text-sm opacity-90">
              Accedeix a quizzes il·limitats, IA avançada i estadístiques completes.
            </p>

            <ul class="mt-4 space-y-2 text-sm">
              <li>✔ Quizzes il·limitats</li>
              <li>✔ Generació amb IA millorada</li>
              <li>✔ Exportació de resultats</li>
            </ul>

            <button
              class="mt-6 w-full bg-white text-blue-600 font-semibold py-2 rounded-xl hover:bg-slate-100 transition"
            >
              Upgrade PRO plan
            </button>
          </div>
        </div>

        <div v-if="user.plan==='pro'" class="col-span-12 md:col-span-6 xl:col-span-4">
          <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow rounded-2xl p-6">
            <h3 class="text-xl font-bold">Maximitza el teu pla 🚀</h3>

            <p class="mt-2 text-sm opacity-90">
              Accedeix a totes les característiques per a centres/empreses.
            </p>

            <ul class="mt-4 space-y-2 text-sm">
              <li>✔ Gestió d'equips</li>
              <li>✔ Suport prioritari</li>
              <li>✔ Implantació personalitzada</li>
            </ul>

            <button
              class="mt-6 w-full bg-white text-blue-600 font-semibold py-2 rounded-xl hover:bg-slate-100 transition"
            >
              Upgrade PREMIUM plan
            </button>
          </div>
        </div>

        <!-- STATS CARD -->
        <div class="col-span-8 md:col-span-8 xl:col-span-8">
          <div class="bg-white dark:bg-slate-900 shadow rounded-2xl p-6 grid grid-cols-3 gap-4">

            <div class="text-center">
              <p class="text-2xl font-bold text-blue-600">{{ stats.quizzes }}</p>
              <p class="text-sm text-slate-500">Quizzes creats</p>
            </div>

            <!--<div class="text-center">
              <p class="text-2xl font-bold text-green-600">{{ stats.completed }}</p>
              <p class="text-sm text-slate-500">Completats</p>
            </div>-->

            <div class="text-center">
              <p class="text-2xl font-bold text-purple-600">{{ stats.usage != 10 ? '0.01':stats.quizzes * 100 / stats.usage }}%</p>
              <p class="text-sm text-slate-500">Taxa d'ús (%)</p>
            </div>
            
            <div class="text-center">
              <p class="text-2xl font-bold text-orange-500">{{ stats.usage }}</p>
              <p class="text-sm text-slate-500">Ús del pla</p>
            </div>

          </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="col-span-12">
          <div class="bg-white dark:bg-slate-900 shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">Accions ràpides</h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left">
                <p class="font-semibold">➕ Crear quiz</p>
                <p class="text-sm text-slate-500">Crea un nou quiz</p>
              </button>
              
              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left">
                <p class="font-semibold">➕ IA quiz</p>
                <p class="text-sm text-slate-500">Crea un nou quiz amb IA</p>
              </button>

              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left">
                <p class="font-semibold">📊 Vore resultats</p>
                <p class="text-sm text-slate-500">Revisa el teu progrés</p>
              </button>

              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left">
                <p class="font-semibold">⚙️ Configuració</p>
                <p class="text-sm text-slate-500">Gestiona el teu compte</p>
              </button>

            </div>
          </div>
        </div>

      </template>
    </div>
  </admin-layout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '../components/layout/AdminLayout.vue'
import QuizMetrics from '../components/ecommerce/QuizMetrics.vue'
import MonthlyTarget from '../components/ecommerce/MonthlySale.vue'
import MonthlySale from '../components/ecommerce/MonthlyTarget.vue'
import CustomerDemographic from '../components/ecommerce/CustomerDemographic.vue'
import StatisticsChart from '../components/ecommerce/StatisticsChart.vue'
import RecentOrders from '../components/ecommerce/RecentOrders.vue'
import { useAuth } from '@/composables/useAuth'

// 🔐 Auth
const { user, loadUser } = useAuth()
loadUser()

// 📊 Stats (reactivo)
const stats = computed(() => {
  const quizzes = user.value?.quizzes || 0

  return {
    quizzes,
    completed: Math.floor(Math.random() * (quizzes || 1)),
    score: 65,
    usage: getUsage(user.value?.plan)
  }
})

// 👑 Rol
const isAdmin = computed(() => user.value?.rol === 'admin')

// 🧠 Función helper
function getUsage(plan) {
  switch (plan) {
    case 'free':
      return 10
    default:
      return 'Unlimited'
  }
}
</script>
