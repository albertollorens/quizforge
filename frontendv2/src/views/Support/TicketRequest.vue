<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
    >
      <div class="p-6 bg-gray-50 dark:bg-slate-900 min-h-screen">

        <form @submit.prevent="submitTicket" class="space-y-6">

          <!-- ASUNTO -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              Assumpte *
            </label>
            <input
              type="text"
              v-model="form.subject"
              required
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              placeholder="Descriu breument el problema..."
            />
          </div>

          <!-- CATEGORIA -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              Categoria *
            </label>
            <select
              v-model="form.category"
              required
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
              <option value="">Selecciona una categoria</option>
              <option value="technical">Problema tècnic</option>
              <option value="account">Compte d'usuari</option>
              <option value="billing">Facturació</option>
              <option value="feature">Sol·licitud de funcionalitat</option>
              <option value="other">Altres</option>
            </select>
          </div>

          <!-- PRIORIDAD -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              Prioritat
            </label>
            <select
              v-model="form.priority"
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
              <option value="low">Baixa</option>
              <option value="medium">Mitjana</option>
              <option value="high">Alta</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <!-- DESCRIPCION -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              Descripció *
            </label>
            <textarea
              rows="6"
              v-model="form.description"
              required
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              placeholder="Descriu detalladament el problema o la sol·licitud..."
            ></textarea>
          </div>

          <!-- ARCHIVOS ADJUNTOS -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              Arxius adjunts (opcional)
            </label>
            <input
              type="file"
              multiple
              @change="handleFileUpload"
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            />
            <p class="text-xs text-gray-500 mt-1">
              Pots adjuntar captures de pantalla o arxius relacionats (màx. 10MB)
            </p>
          </div>

          <!-- BOTONES -->
          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
              {{ loading ? 'Enviant...' : 'Enviar ticket' }}
            </button>

            <button
              type="button"
              @click="cancel"
              class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
            >
              Cancel·lar
            </button>
          </div>

        </form>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '../../components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

const currentPageTitle = ref('Nou ticket de suport')
const router = useRouter()

// Formulario
const form = ref({
  subject: '',
  category: '',
  priority: 'medium',
  description: ''
})

const loading = ref(false)
const attachments = ref([])

// Manejar subida de archivos
function handleFileUpload(event) {
  const files = Array.from(event.target.files)
  attachments.value = files
}

// Enviar ticket
async function submitTicket() {
  loading.value = true

  try {
    // Aquí iría la lógica para enviar el ticket
    // Por ejemplo, llamar a una API

    console.log('Ticket data:', {
      ...form.value,
      attachments: attachments.value
    })

    // Simular envío
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Redirigir a la lista de tickets
    router.push('/ticketlist')

  } catch (error) {
    console.error('Error creating ticket:', error)
    alert('Error al crear el ticket. Inténtalo de nuevo.')
  } finally {
    loading.value = false
  }
}

// Cancelar
function cancel() {
  router.push('/ticketlist')
}
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>