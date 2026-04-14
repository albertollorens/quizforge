<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div
      class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12"
    >
      <div class="mx-auto w-full text-center">

        <!-- QUIZ -->
        <div class="dashboard-container">
          <section class="dashboard-content"> 
            <!-- Llistat de quizzes -->
            <QuizList 
              v-if="$route.path === '/dashboard/quizzes'" 
              :quizzes="quizzes" 
              :loading="loading" 
              @edit="handleEditQuiz" 
              @delete="handleDeleteQuiz" />

            <QuizBuilder 
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
        <!-- QUICK ACTIONS -->
        <div class="col-span-12">
          <div class="bg-white dark:bg-slate-900 shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">Accions ràpides</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left"
                    @click="handleNewQuiz"
              >
                <p class="font-semibold">➕ Crear quiz</p>
                <p class="text-sm text-slate-500">Crea un nou quiz</p>
              </button>
              
              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left"
                    @click="handleNewAIQuiz"
              >
                <p class="font-semibold">➕ IA quiz</p>
                <p class="text-sm text-slate-500">Crea un nou quiz amb IA</p>
              </button>

              <button class="p-4 rounded-xl border hover:bg-slate-50 transition text-left"
                    @click="handleSettings"
              >
                <p class="font-semibold">⚙️ Configuració</p>
                <p class="text-sm text-slate-500">Gestiona el teu compte</p>
              </button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/components/layout/AdminLayout.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";

const currentPageTitle = ref("Qüestionaris");
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import QuizList from '@/components/quiz/QuizList.vue'
import QuizBuilder from '@/components/quiz/QuizBuilder.vue'
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
  router.push('/dashboard/quizzes')
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
    router.push('/dashboard/quizzes')
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
