<script setup>
import { ref, computed, onMounted } from 'vue'
import QuizList from '@/components/QuizList.vue'
import QuizBuilder from '@/components/QuizBuilder.vue'
import {
  getUserQuizzes,
  createQuiz,
  updateQuiz,
  getQuiz,
  deleteQuiz
} from '@/services/quizService'

const quizzes = ref([])
const currentView = ref('list')
const editingQuiz = ref(null)
const loading = ref(false)

function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
}

const builderMode = computed(
  () => editingQuiz.value ? 'edit' : 'create'
)


async function loadQuizzes() {
  loading.value = true
  await delay(800) // ← retard artificial
  try {
    const res = await getUserQuizzes()
    quizzes.value = res.data.data
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(loadQuizzes)

function handleNewQuiz() {
  editingQuiz.value = null
  currentView.value = 'builder'
}

async function handleEditQuiz(id) {
  const res = await getQuiz(id)
  editingQuiz.value = res.data
  currentView.value = 'builder'
}

function handleCancelQuiz() {
  editingQuiz.value = null
  currentView.value = 'list'
}

async function handleSaveQuiz(payload) {
  if (payload.id) {
    await updateQuiz(payload.id, payload)
  } else {
    await createQuiz(payload)
  }

  await loadQuizzes()
  currentView.value = 'list'
}

async function handleDeleteQuiz(id) {
  await deleteQuiz(id)
  await loadQuizzes()
}

</script>

<template>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <h2>Dashboard</h2>
      <button class="btn btn-primary mb-3" 
        v-if="currentView === 'list'" 
        @click="handleNewQuiz">
        <i class="bi bi-plus-circle"></i> Nou Quiz
      </button>

      <button class="btn btn-secondary mb-3" 
        v-if="currentView === 'builder'" 
        @click="handleCancelQuiz"> 
        <i class="bi bi-arrow-left"></i> Tornar 
      </button>
    </header>

    <section class="dashboard-content"> 
      <!-- Llistat de quizzes -->
      <QuizList 
        v-if="currentView === 'list'" 
        :quizzes="quizzes" 
        :loading="loading"
        @edit="handleEditQuiz" 
        @delete="handleDeleteQuiz" />

      <QuizBuilder 
        v-if="currentView === 'builder'" 
        :key="editingQuiz?.id || 'new'" :quiz="editingQuiz" :mode="builderMode"
        @cancel="handleCancelQuiz" 
        @save="handleSaveQuiz" />
    </section>
  </div>
</template>

<style scoped> 
.dashboard-container { 
  padding: 2rem; 
} 

.dashboard-header { 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  margin-bottom: 2rem; 
}

.dashboard-content { 
  margin-top: 1rem; 
}
</style>