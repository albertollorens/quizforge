<template>
  <div class="container my-4">
    <h2 class="mb-4">{{ isEditMode ? 'Editar Quiz' : 'Nou Quiz' }}</h2>

    <!-- Informació -->
    <div class="card shadow-sm mb-4">
      <button
        class="btn btn-success mb-3"
        @click="loadTestData"
      >
        Carregar dades de prova
      </button>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Nom del quiz</label>
            <input
              type="text"
              class="form-control"
              v-model="quizTitle"
            >
          </div>

          <div class="col-md-4">
            <label class="form-label">Curs</label>
            <input
              type="text"
              class="form-control"
              v-model="course"
            >
          </div>

          <div class="col-md-4">
            <label class="form-label">Grup</label>
            <input
              type="text"
              class="form-control"
              v-model="group"
            >
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Descripció</label>
          <textarea
            class="form-control"
            rows="2"
            v-model="description"
          ></textarea>
        </div>
      </div>
    </div>

    <!-- QuestionForm -->
    <div class="card mb-4">
      <div class="card-body">
        <QuestionForm @add="addQuestion" />
      </div>
    </div>

    <!-- Llistat -->
    <div class="card mb-4">
      <div class="card-body">
        <h4>Preguntes afegides</h4>
        <table
          class="table mt-3"
          v-if="questions.length"
        >
          <thead>
            <tr>
              <th>#</th>
              <th>Tipus</th>
              <th>Títol</th>
              <th>Enunciat</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(q,index) in questions"
              :key="index"
            >
              <td>{{ index+1 }}</td>
              <td>{{ q.type }}</td>
              <td>{{ q.title }}</td>
              <td>{{ q.statement }}</td>

              <td>
                <button
                  class="btn btn-danger btn-sm"
                  @click="removeQuestion(index)"
                >
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-else class="text-muted">
          Encara no hi ha preguntes.
        </p>

      </div>
    </div>

    <!-- OUTPUT -->
    <div class="card mb-4">
      <div class="row">
        <div class="col">
          <div class="card-body">
            <h4>GIFT</h4>
            <textarea
              class="form-control"
              rows="10"
              :value="giftOutput"
              readonly
            ></textarea>

            <button
              class="btn btn-info mt-2"
              @click="downloadFile(giftOutput,'quiz.gift','text/plain')"
              :disabled="!questions.length>0"
            >
              <i class="bi bi-download"></i> Descarregar
            </button>

          </div>
        </div>

        <div class="col">
          <div class="card-body">
            <h4>XML</h4>
            <textarea
              class="form-control"
              rows="10"
              :value="xmlOutput"
              readonly
            ></textarea>

            <button
              class="btn btn-info mt-2"
              @click="downloadFile(xmlOutput,'quiz.xml','application/xml')"
              :disabled="!questions.length>0"
            >
              <i class="bi bi-download"></i> Descarregar
            </button>

          </div>
        </div>
      </div>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between">
      <button
        class="btn btn-success"
        @click="handleSave"
        :disabled="questions.length === 0 || props.loading"
      >
        <span v-if="props.loading" class="spinner-border spinner-border-sm me-2"></span>  
        <i class="bi bi-floppy"></i> {{ props.loading ? 'Guardant...' : 'Guardar' }}
      </button>

      <button
        class="btn btn-danger"
        @click="emit('cancel')"
        :disabled="props.loading"
      >
        <i class="bi bi-x"></i> Cancel·lar
      </button>
    </div>
  </div>

  <!-- Modal Loading -->
  <div
    v-if="props.loading"
    class="modal fade show"
    style="display:block; background:rgba(0,0,0,0.5);"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center p-5">
          <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <h5>Guardant quiz...</h5>
          <p class="text-muted mb-0">
            Espera un moment
          </p>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, watch } from 'vue'
import { defineProps, defineEmits } from 'vue'
import QuestionForm from '@/components/QuestionForm.vue'
import authService from '../services/authService'
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