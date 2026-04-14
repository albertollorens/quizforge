<template>
  <admin-layout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
        <div class="p-6 min-h-screen bg-gray-50 dark:bg-slate-900">

          <!-- HEADER -->
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
              Preferències d’usuari ⚙️
            </h1>
            <p class="text-gray-500">
              Gestiona les teues preferències
            </p>
          </div>

          <div class="grid md:grid-cols-3 gap-6">

            <!-- LEFT MENU -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-4">
              <ul class="space-y-2">
                <li
                  v-for="tab in tabs"
                  :key="tab"
                  @click="activeTab = tab"
                  class="cursor-pointer px-3 py-2 rounded-lg"
                  :class="activeTab === tab
                    ? 'bg-indigo-100 text-indigo-600 dark:bg-slate-700'
                    : 'hover:bg-gray-100 dark:hover:bg-slate-700'"
                >
                  {{ tab }}
                </li>
              </ul>
            </div>

            <!-- CONTENT -->
            <div class="md:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow p-6">

              <!-- PREFERENCIAS -->
              <div v-if="activeTab === 'Preferències'">
                <h2 class="text-lg font-semibold mb-4">Preferències</h2>

                <div class="space-y-4">

                  <!-- Tema -->
                  <div>
                    <label class="block text-sm mb-1">Tema</label>
                    <select v-model="form.theme"
                      class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
                      <option value="light">Clar</option>
                      <option value="dark">Fosc</option>
                    </select>
                  </div>

                  <!-- Idioma -->
                  <div>
                    <label class="block text-sm mb-1">Idioma</label>
                    <select v-model="form.language"
                      class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
                      <option value="ca">Català</option>
                      <option value="es">Español</option>
                      <option value="en">English</option>
                    </select>
                  </div>

                  <button @click="savePreferences"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Guardar preferències
                  </button>
                </div>
              </div>

              <!-- NOTIFICACIONES -->
              <div v-if="activeTab === 'Notificacions'">
                <h2 class="text-lg font-semibold mb-4">Notificacions</h2>

                <div class="space-y-3">
                  <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.emailNotif" />
                    Email notifications
                  </label>

                  <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.pushNotif" />
                    Push notifications
                  </label>
                </div>

                <button @click="saveNotifications"
                  class="mt-4 bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                  Guardar
                </button>
              </div>

              <!-- SEGURIDAD -->
              <div v-if="activeTab === 'Seguretat'">
                <h2 class="text-lg font-semibold mb-4">Seguretat</h2>

                <div class="space-y-4">
                  <input type="password" v-model="password.old"
                    placeholder="Contrasenya actual"
                    class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600" />

                  <input type="password" v-model="password.new"
                    placeholder="Nova contrasenya"
                    class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600" />

                  <button @click="changePassword"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg">
                    Canviar contrasenya
                  </button>
                </div>
              </div>

            </div>

          </div>

        </div>
    </div>
  </admin-layout>
</template>

<script setup>
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
const currentPageTitle = ref('Preferències')

import { ref, onMounted } from 'vue'
//import api from '@/services/api'
import { useAuth } from '@/composables/useAuth'

const { user, loadUser } = useAuth()
loadUser()

const activeTab = ref('Preferències')

const tabs = ['Preferències', 'Notificacions', 'Seguretat']

const form = ref({
  name: '',
  email: '',
  theme: 'light',
  language: 'ca',
  emailNotif: true,
  pushNotif: false
})

const password = ref({
  old: '',
  new: ''
})

// 📥 Cargar datos usuario
onMounted(() => {
  if (user.value) {
    form.value.name = user.value.username
    form.value.email = user.value.email
  }
})

// 💾 Guardar perfil
async function saveProfile() {
  await api.put('/user/profile', form.value)
  alert('Perfil actualitzat')
}

// ⚙️ Preferencias
function savePreferences() {
  localStorage.setItem('preferences', JSON.stringify({
    theme: form.value.theme,
    language: form.value.language
  }))
  alert('Preferències guardades')
}

// 🔔 Notificaciones
function saveNotifications() {
  localStorage.setItem('notifications', JSON.stringify({
    email: form.value.emailNotif,
    push: form.value.pushNotif
  }))
  alert('Notificacions guardades')
}

// 🔐 Password
async function changePassword() {
  await api.post('/user/change-password', password.value)
  alert('Contrasenya canviada')
}
</script>
