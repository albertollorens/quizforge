<template>
  <div class="card p-4">
    <h3 class="mb-3">Generador IA de preguntes</h3>

    <!-- FORM -->
    <div class="row form-floating mb-3">
      <input
        id="topic"
        v-model="topic"
        class="form-control"
        placeholder="Tema"
      />
      <label for="topic">Tema</label>
    </div>

    <div class="row">
      <div class="col form-floating mb-3">
        <select id="level" v-model="level" class="form-control">
          <option value="basico">Bàsic</option>
          <option value="medio">Mitjà</option>
          <option value="avanzado">Avançat</option>
        </select>
        <label for="level">Nivell</label>
      </div>

      <div class="col form-floating mb-3">
        <select id="language" v-model="language" class="form-control">
          <option value="valencia">Valencià</option>
          <option value="castellano">Castellà</option>
          <option value="ingles">Anglès</option>
        </select>
        <label for="language">Idioma</label>
      </div>

      <div class="col form-floating mb-3">
        <input
          type="number"
          id="numQuestions"
          v-model="numQuestions"
          class="form-control"
          placeholder="Nombre de preguntes"
        />
        <label for="numQuestions">Nº preguntes</label>
      </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
      <button
        class="btn btn-primary d-inline-block"
        @click="generate"
        :disabled="loading || !topic"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
        {{ loading ? "Generant..." : "Generar preguntes amb OpenAI" }}
      </button>
    </div>

    <!-- ERROR -->
    <div v-if="error" class="alert alert-danger mt-3">
      {{ error }}
    </div>

    <!-- QUESTIONS -->
    <div v-if="isGenerating" class="text-muted mt-3">
      Generant preguntes amb IA...
    </div>

    <div v-if="questions.length" class="mt-3">
      <div
        v-for="(q, index) in questions"
        :key="index"
        class="mb-4 p-3 border rounded"
      >
        <strong :class="{ typing: isTyping && index === questions.length - 1 }">
          {{ q.question }} (tipus: {{ q.question_type }})
        </strong>

        <ul class="list-group mt-2">
          <li
            class="list-group-item"
            v-for="(opt, i) in q.options"
            :key="i"
          >
            <span
              v-if="q.type === 'matching' || isOptionCorrect(opt, q.correct_answer)"
              class="badge badge-success"
              ><i class="bi bi-check"></i>
            </span>
            <span
              v-if="!isOptionCorrect(opt, q.correct_answer)"
              class="badge badge-danger"
              ><i class="bi bi-x"></i>
            </span>
            {{ opt }}
          </li>
        </ul>

        <small
          v-if="q.type !== 'essay'"
          class="text-muted"
          :class="{ typing: isTyping && index === questions.length - 1 }"
        >
          <i><strong>Feedback: </strong>{{ q.explanation }}</i>
        </small>
      </div>
    </div>

    <div class="d-flex align-items-center mt-3">
      <button
        v-if="questions.length > 0 && !isTyping && !isGenerating"
        class="btn btn-info me-2"
        @click="downloadGIFT"
      >
        <i class="bi bi-download"></i> Descarregar GIFT
      </button>
      <button
        v-if="questions.length > 0 && !isTyping && !isGenerating"
        class="btn btn-info"
        @click="downloadXML"
      >
        <i class="bi bi-download"></i> Descarregar XML
      </button>
      <button
        v-if="questions.length > 0 && !isTyping && !isGenerating"
        class="btn btn-success btn-lg ms-auto"
        @click="save"
      >
        <i class="bi bi-floppy"></i> Guardar
      </button>
    </div>

    <!-- Modal Loading -->
    <div
      v-if="saving"
      class="modal fade show"
      style="display:block; background:rgba(0,0,0,0.5);"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center p-5">
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <h5>Guardant quiz...</h5>
            <p class="text-muted mb-0">Espereu un moment</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue"
import { useRouter } from "vue-router"
import authService from '../services/authService'
import { generateQuestions, saveAIQuiz } from "@/services/aiService"
import { generateGIFT, generateXML } from '@/helpers/quizExport.js'
import { normalizeQuestionFromAPI, createQuestion } from '@/factories/quizFactory.js'

const router = useRouter()
const user = computed(() => authService.getUser())

const topic = ref("")
const level = ref("basico")
const language = ref("valencia")
const numQuestions = ref(5)

const questions = ref([])

const loading = ref(false)
const isTyping = ref(false)
const isGenerating = ref(false)
const error = ref("")
const saving = ref(false)

// --------------------
// GENERAR PREGUNTES IA
// --------------------
async function generate() {
  error.value = ""
  questions.value = []
  loading.value = true
  isTyping.value = true
  isGenerating.value = true

  try {
    const res = await generateQuestions({
      topic: topic.value,
      level: level.value,
      language: language.value,
      numQuestions: numQuestions.value
    })

    const fullQuestions = normalizeQuestions(res.data.data.questions)
    await typeQuestions(fullQuestions)
  } catch (err) {
    error.value = err.response?.data?.error || err.message || "Error generant preguntes"
  } finally {
    loading.value = false
    isTyping.value = false
    isGenerating.value = false
  }
}

// --------------------
// NORMALITZAR JSON IA
// --------------------
function normalizeQuestions(rawQuestions) {
  return rawQuestions.map(q => {
    const normalized = normalizeQuestionFromAPI(q)
    let options = []
    let correct_answer

    if (q.type === "singlechoice") {
      options = normalized.answers.map(a => a.text)
      correct_answer = normalized.answers.find(a => a.correct)?.text || ""
    } else if (q.type === "multichoice") {
      options = normalized.answers.map(a => a.text)
      correct_answer = normalized.answers.filter(a => a.correct).map(a => a.text)
    } else if (q.type === "truefalse" || q.type === "shortanswer") {
      options = normalized.answers.map(a => a.text)
      correct_answer = normalized.answers.filter(a => a.correct).map(a => a.text)
    } else if (q.type === "matching") {
      options = normalized.answers.map(a => `${a.text} -> ${a.match_pair}`)
      correct_answer = normalized.answers.map(a => a.match_pair)
    } else if (q.type === "essay") {
      options = []
      correct_answer = ""
    }

    return {
      question: normalized.statement,
      question_type: normalized.type,
      options,
      correct_answer,
      explanation: ""
    }
  })
}

// --------------------
// EFECTE TYPING
// --------------------
async function typeQuestions(fullQuestions) {
  isTyping.value = true
  questions.value = []

  for (const q of fullQuestions) {
    const newQuestion = {
      question: "",
      question_type: q.question_type,
      options: [],
      correct_answer: q.correct_answer,
      explanation: ""
    }
    questions.value.push(newQuestion)

    for (let i = 0; i < q.question.length; i++) {
      newQuestion.question += q.question[i]
      await sleep(15)
    }

    for (const opt of q.options) {
      let optionText = ""
      newQuestion.options.push(optionText)
      for (let i = 0; i < opt.length; i++) {
        optionText += opt[i]
        newQuestion.options[newQuestion.options.length - 1] = optionText
        await sleep(10)
      }
    }

    for (let i = 0; i < q.explanation.length; i++) {
      newQuestion.explanation += q.explanation[i]
      await sleep(10)
    }
  }

  isTyping.value = false
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
}

// --------------------
// EXPORTACIÓ GIFT / XML
// --------------------
function prepareQuestionsForExport(uiQuestions) {
  return uiQuestions.map(q => {
    const questionObj = createQuestion(q.question_type)
    questionObj.title = q.question
    questionObj.statement = q.question
    questionObj.answers = q.options.map((opt, i) => {
      const answer = createQuestion(q.question_type).answers[i] || createQuestion(q.question_type).answers[0]
      answer.text = opt

      if (q.question_type === 'singlechoice') {
        answer.correct = opt === q.correct_answer
        answer.weight = answer.correct ? 100 : 0
      } else if (q.question_type === 'multichoice') {
        answer.correct = Array.isArray(q.correct_answer) ? q.correct_answer.includes(opt) : false
        answer.weight = answer.correct ? 100 / q.correct_answer.length : 0
      } else if (q.question_type === 'truefalse' || q.question_type === 'shortanswer') {
        answer.correct = Array.isArray(q.correct_answer) ? q.correct_answer.includes(opt) : opt === q.correct_answer
      } else if (q.question_type === 'matching') {
        answer.match_pair = q.correct_answer[i] || ''
      }

      return answer
    })

    return questionObj
  })
}

function downloadGIFT() {
  const exportQuestions = prepareQuestionsForExport(questions.value)
  const giftContent = generateGIFT(exportQuestions)
  downloadFile(giftContent, "quiz.gift", "text/plain")
}

function downloadXML() {
  const exportQuestions = prepareQuestionsForExport(questions.value)
  const xmlContent = generateXML(exportQuestions)
  downloadFile(xmlContent, "quiz.xml", "application/xml")
}

function downloadFile(content, filename, type) {
  const blob = new Blob([content], { type })
  const url = URL.createObjectURL(blob)
  const link = document.createElement("a")
  link.href = url
  link.download = filename
  link.click()
  URL.revokeObjectURL(url)
}

// --------------------
// FUNCIONS AUXILIARS
// --------------------
function isOptionCorrect(opt, correct) {
  if (Array.isArray(correct)) return correct.includes(opt)
  if (typeof correct === "object") return Object.values(correct).includes(opt)
  return opt === correct
}

// --------------------
// GUARDAR QUIZ
// --------------------
async function save() {
  saving.value = true
  try {
    const formattedQuestions = prepareQuestionsForExport(questions.value)

    const payload = {
      userid: user.value.id,
      title: topic.value,
      course: new Date().getFullYear(),
      group: "AI Generated",
      description: `Quiz generat amb l'API d'OpenAI: ${topic.value} de nivell ${level.value} en idioma ${language.value}`,
      questions: formattedQuestions,
      giftformat: generateGIFT(formattedQuestions),
      xmlformat: generateXML(formattedQuestions)
    }

    await saveAIQuiz(payload)
    router.push("/dashboard")
  } catch (e) {
    console.error(e)
    alert("Error guardant el qüestionari")
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.form-floating > label {
  left: auto;
}

.typing::after {
  content: "|";
  animation: blink 1s infinite;
}

@keyframes blink {
  0%, 50%, 100% { opacity: 1;}
  25%, 75% { opacity: 0; }
}
</style>