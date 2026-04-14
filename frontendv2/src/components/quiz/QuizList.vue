<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  quizzes: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false }
})

const emit = defineEmits(['edit','delete'])

const handleEdit = id => emit('edit', id)

const handleDelete = id => {
  if (confirm('Aquesta acció esborrarà el quiz de la BD. Està segur que vol eliminar-lo?'))
    emit('delete', id)
}

// Descarrega de fitxers (GIFT o XML)
function downloadFile(content, filename, type) {
  if (!content) {
    alert('Aquest fitxer no existeix')
    return
  }

  const blob = new Blob([content], { type })
  const url = URL.createObjectURL(blob)

  const link = document.createElement('a')

  link.href = url
  link.download = filename
  link.click()

  URL.revokeObjectURL(url)
}

// Format de la data del quiz
function formatDate(quizDate) {
  const formattedDate = new Date(quizDate)
  return formattedDate.toLocaleDateString('es-ES', {year: 'numeric', month: 'long', day: '2-digit'})
}
</script>

<template>
  <div class="p-4">

    <!-- LOADING -->
    <div v-if="loading" class="flex items-center justify-center gap-2 text-gray-500 my-6">
      <div class="w-4 h-4 border-2 border-gray-300 border-t-transparent rounded-full animate-spin"></div>
      Carregant quizzes...
    </div>

    <!-- EMPTY -->
    <div v-else-if="!quizzes.length" class="text-gray-500">
      Encara no tens cap quiz creat.
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <div
        v-for="quiz in quizzes"
        :key="quiz.id"
        class="bg-white dark:bg-slate-800 rounded-2xl shadow hover:shadow-md transition flex flex-col"
      >

        <!-- HEADER -->
        <div class="flex justify-between items-center p-4 border-b dark:border-slate-700">
          <h5 class="font-semibold text-gray-800 dark:text-white">
            {{ quiz.group }} ({{ quiz.course }})
          </h5>
          <span class="text-xs text-gray-400">
            {{ formatDate(quiz.created_at) }}
          </span>
        </div>

        <!-- BODY -->
        <div class="p-4 flex-1">
          <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
            {{ quiz.title }}
          </h5>
          <p class="text-sm text-gray-500 line-clamp-2">
            {{ quiz.description }}
          </p>
        </div>

        <!-- FOOTER -->
        <div class="p-4 border-t dark:border-slate-700 flex flex-col gap-3">

          <!-- DOWNLOADS -->
          <div class="flex gap-2 flex-wrap">
            <button
              class="flex items-center gap-1 px-3 py-1.5 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200"
              @click="downloadFile(quiz.gift, quiz.title + '.gift', 'text/plain')"
            >
              ⬇ GIFT
            </button>

            <button
              class="flex items-center gap-1 px-3 py-1.5 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200"
              @click="downloadFile(quiz.xml, quiz.title + '.xml', 'application/xml')"
            >
              ⬇ XML
            </button>
          </div>

          <!-- ACTIONS -->
          <div class="flex gap-2 flex-wrap">
            <button
              class="flex items-center gap-1 px-3 py-1.5 text-sm bg-indigo-500 text-white rounded-lg hover:bg-indigo-600"
              @click="handleEdit(quiz.id)"
            >
              ✏ Editar
            </button>

            <button
              class="flex items-center gap-1 px-3 py-1.5 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600"
              @click="handleDelete(quiz.id)"
            >
              🗑 Eliminar
            </button>
          </div>

        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>
.quiz-list {
  margin-top: 1rem;
}

.card-text.text-truncate {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>