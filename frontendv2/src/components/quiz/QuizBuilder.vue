<template>
  <div class="mx-auto my-6 max-w-6xl px-4">

    <h2 class="mb-6 text-2xl font-semibold text-gray-800 dark:text-white">
      {{ isEditMode ? 'Editar Quiz' : 'Nou Quiz' }}
    </h2>

    <!-- INFORMACIÓ -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/5">

      <button
        class="mb-4 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
        @click="loadTestData"
      >
        Carregar dades de prova
      </button>

      <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
            Nom del quiz
          </label>
          <input
            type="text"
            v-model="quizTitle"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
          />
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
            Curs
          </label>
          <input
            type="text"
            v-model="course"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
          />
        </div>

        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
            Grup
          </label>
          <input
            type="text"
            v-model="group"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
          />
        </div>
      </div>

      <div class="mt-4">
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
          Descripció
        </label>
        <textarea
          rows="2"
          v-model="description"
          class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        ></textarea>
      </div>
    </div>

    <!-- QUESTION FORM -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/5">
      <QuestionForm @add="addQuestion" />
    </div>

    <!-- LLISTAT -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/5">

      <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
        Preguntes afegides
      </h4>

      <div v-if="questions.length">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 text-gray-600 dark:border-gray-700 dark:text-gray-300">
              <tr>
                <th class="py-2">#</th>
                <th class="py-2">Tipus</th>
                <th class="py-2">Títol</th>
                <th class="py-2">Enunciat</th>
                <th class="py-2"></th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(q,index) in questions"
                :key="index"
                class="border-b border-gray-100 dark:border-gray-800"
              >
                <td class="py-2">{{ index + 1 }}</td>
                <td class="py-2">{{ q.type }}</td>
                <td class="py-2">{{ q.title }}</td>
                <td class="py-2">{{ q.statement }}</td>

                <td class="py-2">
                  <button
                    class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-700"
                    @click="removeQuestion(index)"
                  >
                    <i class="bi bi-trash"></i>
                    Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <p v-else class="text-sm text-gray-500">
        Encara no hi ha preguntes.
      </p>

    </div>

    <!-- OUTPUT -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/5">

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <!-- GIFT -->
        <div>
          <h4 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">
            GIFT
          </h4>

          <textarea
            class="h-64 w-full rounded-lg border border-gray-300 bg-white p-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            :value="giftOutput"
            readonly
          ></textarea>

          <button
            class="mt-2 rounded-lg bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-700 disabled:opacity-50"
            @click="downloadFile(giftOutput,'quiz.gift','text/plain')"
            :disabled="!questions.length"
          >
            <i class="bi bi-download"></i> Descarregar
          </button>
        </div>

        <!-- XML -->
        <div>
          <h4 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">
            XML
          </h4>

          <textarea
            class="h-64 w-full rounded-lg border border-gray-300 bg-white p-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            :value="xmlOutput"
            readonly
          ></textarea>

          <button
            class="mt-2 rounded-lg bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-700 disabled:opacity-50"
            @click="downloadFile(xmlOutput,'quiz.xml','application/xml')"
            :disabled="!questions.length"
          >
            <i class="bi bi-download"></i> Descarregar
          </button>
        </div>

      </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-between">

      <button
        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
        @click="handleSave"
        :disabled="questions.length === 0 || props.loading"
      >
        <span
          v-if="props.loading"
          class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
        ></span>

        <i class="bi bi-floppy"></i>
        {{ props.loading ? 'Guardant...' : 'Guardar' }}
      </button>

      <button
        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
        @click="emit('cancel')"
        :disabled="props.loading"
      >
        <i class="bi bi-x"></i>
        Cancel·lar
      </button>

    </div>

  </div>

  <!-- MODAL LOADING -->
  <div
    v-if="props.loading"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  >
    <div class="w-full max-w-sm rounded-xl bg-white p-6 text-center shadow-lg dark:bg-gray-900">

      <div class="mx-auto mb-4 h-10 w-10 animate-spin rounded-full border-4 border-blue-500 border-t-transparent"></div>

      <h5 class="text-lg font-semibold text-gray-800 dark:text-white">
        Guardant quiz...
      </h5>

      <p class="text-sm text-gray-500">
        Espera un moment
      </p>

    </div>
  </div>

</template>


<script setup>
import { ref, computed, watch } from 'vue'
import QuestionForm from '@/components/quiz/QuestionForm.vue'
import authService from '../../services/authService'
import { generateGIFT, generateXML } from '@/helpers/quizExport.js'
import { normalizeQuestionFromAPI } from '@/factories/quizFactory'
import testQuizData from '@/data/testQuizData' //llevar per a producció

const emit = defineEmits(['save','cancel'])

const props = defineProps({
  quiz:Object,
  mode:String,
  loading: Boolean
})

const isEditMode = computed(
  ()=> props.mode === 'edit'
)

const user = computed(() => authService.getUser())

/* STATE */
const id = ref(null)
const quizTitle = ref('')
const course = ref('')
const group = ref('')
const description = ref('')
const questions = ref([])

/* WATCH QUIZ (IMPORTANT) */
watch(()=>props.quiz, (quiz)=>{
    if(!quiz){
      resetForm()
      return
    }

    id.value = quiz.id
    quizTitle.value = quiz.title || ''
    course.value = quiz.course || ''
    group.value = quiz.group || ''
    description.value = quiz.description || ''
    // Normalització
    questions.value = quiz.questions
      ? quiz.questions.map(q => normalizeQuestionFromAPI(q))
      : []
  }, { immediate:true }
)

function resetForm(){
  id.value=null
  quizTitle.value=''
  course.value=''
  group.value=''
  description.value=''
  questions.value=[]
}

/* QUESTIONS */
function addQuestion(q){
  questions.value.push(normalizeQuestionFromAPI(q))
}

function removeQuestion(index){
  questions.value.splice(index,1)
}

/* GIFT FORMAT GENERATOR */
const giftOutput = computed(() => generateGIFT(questions.value))

/* XML FORMAT GENERATOR */
const xmlOutput = computed(() => generateXML(questions.value))


/* DOWNLOADS */
function downloadFile(content,filename,type){
  const blob = new Blob([content],{type})
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')

  link.href=url
  link.download=filename
  link.click()

  URL.revokeObjectURL(url)
}


/* SAVE */
async function handleSave(){  
  if(!quizTitle.value) return alert('Nom requerit')
  emit('save',{
      userid: user.value.id,
      id:id.value,
      title:quizTitle.value,
      course:course.value,
      group:group.value,
      description:description.value,
      questions:questions.value,
      giftformat:giftOutput.value,
      xmlformat:xmlOutput.value
  })  
}

function loadTestData() {
  quizTitle.value = testQuizData.title
  course.value = testQuizData.course
  group.value = testQuizData.group
  description.value = testQuizData.description
  questions.value = JSON.parse(JSON.stringify(testQuizData.questions))
}
</script>