<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="p-6 min-h-screen bg-gray-50 dark:bg-slate-900">

        <!-- HEADER -->
        <div class="text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
            Millora el teu pla 🚀
          </h1>
          <p class="text-gray-500 mt-2">
            Desbloqueja tot el potencial de QuizForge
          </p>
        </div>

        <!-- PLANS -->
        <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">

          <!-- FREE -->
          <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow">
            <h3 class="text-xl font-semibold mb-2">Free</h3>
            <p class="text-3xl font-bold mb-4">0€</p>

            <ul class="text-sm text-gray-500 space-y-2 mb-6">
              <li>✔ 10 quizzes / mes</li>
              <li>✔ Accés bàsic</li>
            </ul>

            <button
              class="w-full py-2 rounded-lg bg-gray-200 text-gray-600 cursor-not-allowed"
              disabled
            >
              Pla actual
            </button>
          </div>

          <!-- PRO -->
          <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow border-2 border-indigo-500">
            <h3 class="text-xl font-semibold mb-2">Pro</h3>
            <p class="text-3xl font-bold mb-4">9.99€/mes</p>

            <ul class="text-sm text-gray-500 space-y-2 mb-6">
              <li>✔ Quizzes il·limitats</li>
              <li>✔ IA avançada</li>
              <li>✔ Exportacions</li>
            </ul>

            <button
              @click="upgrade('pro')"
              :disabled="loading"
              class="w-full py-2 rounded-lg bg-indigo-500 hover:bg-indigo-600 text-white"
            >
              {{ loading ? 'Processant...' : 'Millorar a Pro' }}
            </button>
          </div>

          <!-- ENTERPRISE -->
          <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow">
            <h3 class="text-xl font-semibold mb-2">Enterprise</h3>
            <p class="text-3xl font-bold mb-4">Contacte</p>

            <ul class="text-sm text-gray-500 space-y-2 mb-6">
              <li>✔ Tot Pro</li>
              <li>✔ Suport prioritari</li>
              <li>✔ Personalització</li>
            </ul>

            <button
              @click="contactSales"
              class="w-full py-2 rounded-lg bg-gray-800 text-white"
            >
              Contactar
            </button>
          </div>

        </div>

      </div>
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref } from 'vue'
const currentPageTitle = ref('Pla')

// 🚀 Upgrade (Stripe)
async function upgrade(plan) {
  try {
    loading.value = true

    const { data } = await api.post('/payments/create-checkout-session', {
      plan
    })

    // Redirigir a Stripe Checkout
    window.location.href = data.url

  } catch (err) {
    console.error(err)
    alert('Error en el pagament')
  } finally {
    loading.value = false
  }
}

// 📞 Contacte Enterprise
function contactSales() {
  window.location.href = 'mailto:sales@quizforge.com'
}
</script>
