<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="p-6 bg-gray-50 dark:bg-slate-900 min-h-screen">

        <!-- HEADER -->
        <div class="text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
            Centre d'ajuda
          </h1>
          <p class="text-gray-500 mt-2">
            Com podem ajudar-te?
          </p>

          <!-- SEARCH -->
          <div class="mt-6 max-w-xl mx-auto">
            <input
              v-model="search"
              type="text"
              placeholder="Buscar..."
              class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none"
            />
          </div>
        </div>

        <!-- CATEGORIES -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
          <div
            v-for="cat in categories"
            :key="cat.title"
            class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow hover:shadow-md transition cursor-pointer"
          >
            <div class="text-2xl mb-2">{{ cat.icon }}</div>
            <h3 class="font-semibold text-gray-800 dark:text-white">
              {{ cat.title }}
            </h3>
            <p class="text-sm text-gray-500">
              {{ cat.desc }}
            </p>
          </div>
        </div>

        <!-- FAQ -->
        <div class="max-w-3xl mx-auto space-y-4">

          <div
            v-for="(faq, index) in filteredFaqs"
            :key="index"
            class="bg-white dark:bg-slate-800 rounded-xl shadow"
          >

            <!-- QUESTION -->
            <div
              @click="toggle(index)"
              class="flex justify-between items-center p-4 cursor-pointer"
            >
              <h3 class="font-medium text-gray-800 dark:text-white">
                {{ faq.q }}
              </h3>
              <span class="text-gray-400">
                {{ openIndex === index ? '−' : '+' }}
              </span>
            </div>

            <!-- ANSWER -->
            <div
              v-if="openIndex === index"
              class="px-4 pb-4 text-gray-600 dark:text-gray-300 text-sm"
            >
              {{ faq.a }}
            </div>

          </div>

        </div>

        <!-- CONTACT -->
        <div class="mt-12 text-center">
          <p class="text-gray-500 mb-3">
            No trobes el que busques?
          </p>
          <button
            class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-xl"
          >
            Contactar suport
          </button>
        </div>

      </div>

    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { ref, computed } from 'vue'
const currentPageTitle = ref('Ajuda')

const search = ref('')
const openIndex = ref(null)

// 📚 Categories
const categories = [
  { icon: '🚀', title: 'Com començar', desc: 'Primers passos amb l’app' },
  { icon: '💳', title: 'Plans i facturació', desc: 'Subscripcions i pagaments' },
  { icon: '⚙️', title: 'Configuració', desc: 'Preferències i perfil' }
]

// ❓ FAQs
const faqs = [
  {
    q: 'Com crear un quiz?',
    a: 'Ves a Dashboard i fes clic a "Crear quiz".'
  },
  {
    q: 'Com funciona el pla Free?',
    a: 'El pla Free té limitacions d’ús mensual.'
  },
  {
    q: 'Puc canviar el meu pla?',
    a: 'Sí, des de la secció de facturació.'
  },
  {
    q: 'Com recuperar la contrasenya?',
    a: 'Fes clic a "Has oblidat la contrasenya?" al login.'
  }
]

// 🔍 Filtre
const filteredFaqs = computed(() =>
  faqs.filter(f =>
    f.q.toLowerCase().includes(search.value.toLowerCase())
  )
)

// 🔽 Toggle FAQ
function toggle(index) {
  openIndex.value = openIndex.value === index ? null : index
}
</script>
