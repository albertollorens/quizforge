<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div
      class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/5 xl:px-10 xl:py-12"
    >
      <div class="mx-auto w-full text-center">
        <h3
          class="mb-6 text-2xl font-semibold text-gray-800 dark:text-white/90"
        >
          Generador IA de preguntes
        </h3>

        <!-- CARD -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/5 text-left">

          <!-- FORM -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
              Tema
            </label>
            <input
              id="topic"
              v-model="topic"
              placeholder="Tema"
              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            />
          </div>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <!-- LEVEL -->
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                Nivell
              </label>
              <select
                id="level"
                v-model="level"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              >
                <option value="basico">Bàsic</option>
                <option value="medio">Mitjà</option>
                <option value="avanzado">Avançat</option>
              </select>
            </div>

            <!-- LANGUAGE -->
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                Idioma
              </label>
              <select
                id="language"
                v-model="language"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              >
                <option value="valencia">Valencià</option>
                <option value="castellano">Castellà</option>
                <option value="ingles">Anglès</option>
              </select>
            </div>

            <!-- NUMBER QUESTIONS -->
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                Nº preguntes
              </label>
              <input
                type="number"
                id="numQuestions"
                v-model="numQuestions"
                placeholder="Nombre de preguntes"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              />
            </div>
          </div>

          <!-- BUTTON GENERATE -->
          <div class="mt-6 flex justify-center">
            <button
              class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
              @click="generate"
              :disabled="loading || !topic"
            >
              <span
                v-if="loading"
                class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
              ></span>
              {{ loading ? "Generant..." : "Generar preguntes amb OpenAI" }}
            </button>
          </div>

          <!-- ERROR -->
          <div
            v-if="error"
            class="mt-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700"
          >
            {{ error }}
          </div>

          <!-- GENERATING -->
          <div v-if="isGenerating" class="mt-4 text-sm text-gray-500">
            Generant preguntes amb IA...
          </div>

          <!-- QUESTIONS -->
          <div v-if="questions.length" class="mt-6 space-y-4">

            <div
              v-for="(q, index) in questions"
              :key="index"
              class="rounded-lg border border-gray-200 p-4 dark:border-gray-800"
            >
              <strong
                class="block text-gray-800 dark:text-white"
                :class="{ 'animate-pulse': isTyping && index === questions.length - 1 }"
              >
                {{ q.question }} (tipus: {{ q.question_type }})
              </strong>

              <ul class="mt-3 space-y-2">
                <li
                  v-for="(opt, i) in q.options"
                  :key="i"
                  class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200"
                >
                  <span v-if="q.type === 'matching' || isOptionCorrect(opt, q.correct_answer)">
                    <i class="bi bi-check-circle-fill text-green-500"></i>
                  </span>
                  <span v-else>
                    <i class="bi bi-x-circle-fill text-red-500"></i>
                  </span>
                  {{ opt }}
                </li>
              </ul>

              <small
                v-if="q.type !== 'essay'"
                class="mt-2 block text-sm text-gray-500"
                :class="{ 'animate-pulse': isTyping && index === questions.length - 1 }"
              >
                <i><strong>Feedback:</strong> {{ q.explanation }}</i>
              </small>
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div class="mt-6 flex items-center gap-3">

            <button
              v-if="questions.length > 0 && !isTyping && !isGenerating"
              class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-700"
              @click="downloadGIFT"
            >
              <i class="bi bi-download"></i> GIFT
            </button>

            <button
              v-if="questions.length > 0 && !isTyping && !isGenerating"
              class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-700"
              @click="downloadXML"
            >
              <i class="bi bi-download"></i> XML
            </button>

            <button
              v-if="questions.length > 0 && !isTyping && !isGenerating"
              class="ml-auto inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2 text-sm font-medium text-white hover:bg-green-700"
              @click="save"
            >
              <i class="bi bi-floppy"></i> Guardar
            </button>
          </div>

          <!-- MODAL LOADING -->
          <div
            v-if="saving"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
          >
            <div class="w-full max-w-sm rounded-xl bg-white p-6 text-center shadow-lg dark:bg-gray-900">
              <div class="mx-auto mb-4 h-10 w-10 animate-spin rounded-full border-4 border-blue-500 border-t-transparent"></div>
              <h5 class="text-lg font-semibold text-gray-800 dark:text-white">
                Guardant quiz...
              </h5>
              <p class="text-sm text-gray-500">Espereu un moment</p>
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

const currentPageTitle = ref("Qüestionaris amb IA");
import { ref, computed } from "vue"
import { useRouter } from "vue-router"
import authService from '../../services/authService'
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
    router.push("/dashboard/quizzes")
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