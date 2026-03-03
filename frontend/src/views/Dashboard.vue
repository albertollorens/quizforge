<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import QuizList from '@/components/QuizList.vue'
//import QuizBuilder from '@/components/QuizBuilder.vue'
import {
  getUserQuizzes,
  createQuiz,
  updateQuiz,
  getQuiz,
  deleteQuiz
} from '@/services/quizService'

const router = useRouter()
const quizzes = ref([])
const editingQuiz = ref(null)
const loading = ref(false)  //Per mostrar un loading mentre es carreguen els quizzes
const saving = ref(false) // Per mostrar un loading mentre es guarda el quiz

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

async function handleNewQuiz() {
  editingQuiz.value = null
  router.push('/dashboard/nouquiz')
}

// Editar quiz → només enviem l'ID del quiz, després carreguem dades
async function handleEditQuiz(quizId) {
  try {
    editingQuiz.value = null  // netejar abans
    const res = await getQuiz(quizId)
    editingQuiz.value = res.data
    router.push(`/dashboard/editquiz/${quizId}`)
  } catch (error) {
    console.error('Error carregant el quiz:', error)
  }
}

async function handleCancelQuiz() {  
  editingQuiz.value = null
  router.push('/dashboard')
}

async function handleSaveQuiz(payload) {  
  saving.value = true

  try {
    if (payload.id) {
      await updateQuiz(payload.id, payload)
    } else {
      await createQuiz(payload)
    }

    await loadQuizzes()
    router.push('/dashboard')
  } catch(e){
    console.error(e)
    alert("Error guardant el qüestionari")
  } finally{
    saving.value = false    
  }
}

// Elimina el quiz
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
        v-if="$route.path === '/dashboard'" 
        @click="handleNewQuiz">
        <i class="bi bi-plus-circle"></i> Nou Quiz
      </button>

      <button class="btn btn-secondary mb-3" 
        v-if="$route.path !== '/dashboard'" 
        @click="handleCancelQuiz"> 
        <i class="bi bi-arrow-left"></i> Tornar 
      </button>
    </header>

    <section class="dashboard-content"> 
      <!-- Llistat de quizzes -->
      <QuizList 
        v-if="$route.path === '/dashboard'" 
        :quizzes="quizzes" 
        :loading="loading" 
        @edit="handleEditQuiz" 
        @delete="handleDeleteQuiz" />

      <router-view 
        v-else
        :key="editingQuiz?.id || 'new'" 
        :quiz="editingQuiz"
        :mode="builderMode"
        :loading="saving"    
        @cancel="handleCancelQuiz" 
        @save="handleSaveQuiz"         
      />
            
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