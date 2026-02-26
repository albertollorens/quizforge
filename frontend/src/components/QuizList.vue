<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  quizzes: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits([
  'edit',
  'delete'
])

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
  <div class="quiz-list">
    <h3 class="mb-4">Els meus Quizzes</h3>
    <div v-if="loading" class="text-center text-muted my-4">
      <div class="spinner-border spinner-border-sm me-2"></div> Carregant quizzes...
    </div>
    <div v-else-if="!quizzes.length" class="text-muted">
      Encara no tens cap quiz creat.
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col" v-for="quiz in quizzes" :key="quiz.id">
        <div class="card h-100 shadow-sm">
          <div class="card-header d-flex justify-content-between">
            <h5>{{quiz.group}} ({{ quiz.course }})</h5>
            <small>{{ formatDate(quiz.created_at) }}</small>
          </div>
          <div class="card-body">            
            <h5 class="card-title">{{ quiz.title }}</h5>
            <p class="card-text text-truncate">{{ quiz.description }}</p>
          </div>
          <div class="card-footer d-flex justify-content-between flex-wrap gap-2"> <!-- Grup 1: Descàrregues -->
            <div class="d-flex gap-2"> 
              <button class="btn btn-info btn-sm" 
                @click="downloadFile(quiz.gift,quiz.title+'.gift','text/plain')">
                <i class="bi bi-download"></i> Descarregar GIFT
              </button>
              <button class="btn btn-info btn-sm" 
                @click="downloadFile(quiz.xml,quiz.title+'.xml','application/xml')">
                <i class="bi bi-download"></i> Descarregar XML
              </button>
            </div> <!-- Grup 2: Accions -->
            <div class="d-flex gap-2"> 
              <button class="btn btn-primary btn-sm"
                @click="handleEdit(quiz.id)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm"
                @click="handleDelete(quiz.id)">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </div>
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