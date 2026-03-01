<template>
  <div class="container my-4">
    <h2 class="mb-4">{{ isEditMode ? 'Editar Quiz' : 'Nou Quiz' }}</h2>

    <!-- Informació -->
    <div class="card shadow-sm mb-4">
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
          />
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
            />

            <button
              class="btn btn-info mt-2"
              @click="downloadFile(giftOutput,'quiz.gift','text/plain')"
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
            />

            <button
              class="btn btn-info mt-2"
              @click="downloadFile(xmlOutput,'quiz.xml','application/xml')"
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
      >
        <i class="bi bi-floppy"></i> Guardar
      </button>

      <button
        class="btn btn-danger"
        @click="emit('cancel')"
      >
        <i class="bi bi-x"></i> Cancel·lar
      </button>
    </div>

  </div>
</template>


<script setup>
import { ref, computed, watch } from 'vue'
import QuestionForm from '@/components/QuestionForm.vue'

const emit = defineEmits(['save','cancel'])

const props = defineProps({
  quiz:Object,
  mode:String
})

const isEditMode = computed(
  ()=> props.mode === 'edit'
)


/* STATE */
const id = ref(null)
const quizTitle = ref('')
const course = ref('')
const group = ref('')
const description = ref('')
const questions = ref([])


/* WATCH QUIZ (IMPORTANT) */
watch(
  ()=>props.quiz,
  (quiz)=>{
    if(!quiz){
      resetForm()
      return
    }

    id.value = quiz.id
    quizTitle.value = quiz.title || ''
    course.value = quiz.course || ''
    group.value = quiz.group || ''
    description.value = quiz.description || ''

    questions.value = quiz.questions
      ? structuredClone(quiz.questions)
      : []
  },
  { immediate:true }
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
  questions.value.push(q)
}

function removeQuestion(index){
  questions.value.splice(index,1)
}

/* GIFT FORMAT GENERATOR */
const giftOutput = computed(() => {
  return questions.value.map((q, index) => {
    const title = `::${q.title}::`

    // SINGLECHOICE
    if (q.type === 'singlechoice') {
      const answers = q.answers.map(a =>
        a.correct ? `=${a.text}` : `~${a.text}`
      ).join('\n')

      return `${title}[html]${q.statement} {\n${answers}\n}`
    }

    // MULTIPLECHOICE
    if (q.type === 'multichoice') {
      const answers = q.answers.map(a => {
        const weight = a.weight
        if (a.correct) {
          return `~%${weight}%${a.text}`
        }
        return `~%${weight}%${a.text}`
      }).join('\n')

      return `${title}[html]${q.statement} {\n${answers}\n}`
    }

    // TRUE / FALSE
    if (q.type === 'truefalse') {
      const answer = q.answer === true ? 'TRUE' : 'FALSE'
      return `${title}[html]${q.statement} {${answer}}`
    }

    // SHORT ANSWER
    if (q.type === 'shortanswer') {
      const answers = q.answers.map(a=>{
        return `=${a} `
      }).join(' ')
      return `${title}[html]${q.statement} {${answers}}`
    }

    // ESSAY
    if (q.type === 'essay') {
      return `${title}[html]${q.statement} {}` 
    }

    // MATCHING
    if (q.type === 'matching') {
      const pairs = q.pairs.map(p => {        
        return `=${p.left}->${p.right}`
      }).join('\n')

      return `${title}[html]${q.statement} {\n${pairs}\n}`
    }
    return ''

  }).join('\n\n')

})

/* XML FORMAT GENERATOR */
const xmlOutput = computed(() => {
  let xml = `<?xml version="1.0" encoding="UTF-8"?>\n<quiz>\n`
  questions.value.forEach((q, index) => {

    // SINGLECHOICE
    if (q.type === 'singlechoice') {
      xml += `
        <question type="multichoice">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>
          <single>true</single>
      `

      q.answers.forEach(a => {
        const fraction = a.correct ? "100" : "0"
        xml += `
            <answer fraction="${fraction}">
              <text><![CDATA[${a.text}]]></text>
            </answer>
        `
      })
      xml += `  </question>\n`
    }

    // MULTIPLECHOICE
    if (q.type === 'multichoice') {
      xml += `
        <question type="multichoice">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>
          <single>false</single>
      `
      q.answers.forEach(a => {        
        const weight = a.weight

        xml += `
            <answer fraction="${weight}">
              <text><![CDATA[${a.text}]]></text>
            </answer>
        `
      })
      xml += `  </question>\n`
    }

    // TRUE FALSE
    if (q.type === 'truefalse') {
      xml += `
        <question type="truefalse">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>
          <answer fraction="${q.answer ? 100 : 0}">
            <text>true</text>
          </answer>
          <answer fraction="${!q.answer ? 100 : 0}">
            <text>false</text>
          </answer>
        </question>
      `
    }

    // SHORT ANSWER
    if (q.type === 'shortanswer') {
      xml += `
        <question type="shortanswer">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>          
      `
      q.answers.forEach(a => {
        xml+=`<answer fraction="100">
                <text><![CDATA[${a}]]></text>
              </answer>
        `
      })

      xml +=`</question>`
    }

    // ESSAY
    if (q.type === 'essay') {
      xml += `
        <question type="essay">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>
        </question>
      `
    }

    // MATCHING
    if (q.type === 'matching') {
      xml += `
        <question type="matching">
          <name><text>${q.title}</text></name>
          <questiontext format="html">
            <text><![CDATA[${q.statement}]]></text>
          </questiontext>
      `
      q.pairs.forEach(p => {
        xml += `
            <subquestion>
              <text><![CDATA[${p.left}]]></text>
              <answer>
                <text><![CDATA[${p.right}]]></text>
              </answer>
            </subquestion>
        `
      })
      xml += `  </question>\n`
    }
  })

  xml += `</quiz>`

  return xml
})


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
function handleSave(){
  if(!quizTitle.value){
    alert('Nom requerit')
    return
  }

  emit('save',{ //payload
    id:id.value,
    title:quizTitle.value,
    course:course.value,
    group:group.value,
    description:description.value,
    questions:questions.value,
    gift:giftOutput.value,
    xml:xmlOutput.value
  })

}
</script>