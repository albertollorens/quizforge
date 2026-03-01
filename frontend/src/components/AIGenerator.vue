<template>
  <div class="card p-4">
    <h3 class="mb-3">
      Generador IA de preguntes
    </h3>
    <!-- FORM -->
    <div class="row form-floating mb-3">
      <input
        id="topic"
        v-model="topic"
        class="form-control"
        placeholder="Tema"
      >
      <label for="topic">Tema</label>
    </div>

    <div class="row">
        <div class="col form-floating mb-3">
            <select
                id="level"
                v-model="level"
                class="form-control"
            >
                <option value="basico">Bàsic</option>
                <option value="medio">Mitjà</option>
                <option value="avanzado">Avançat</option>
            </select>
            <label for="level">Nivell</label>
        </div>
        <div class="col form-floating mb-3">            
            <select
            id="language"
            v-model="language"
            class="form-control"
            >
                <option value="valencia">Valencià</option>
                <option value="castellano">Castellà</option>
                <option value="ingles">Anglès</option>
            </select>
            <label for="language">Idioma</label>
        </div>
        <div class="col form-floating mb-3">
            <input type="number"
            id="numQuestions"
            v-model="numQuestions"
            class="form-control"
            placeholder="Nombre de preguntes"
            value="5"
            >
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
          <li class="list-group-item" v-for="(opt, i) in q.options" :key="i">            
              <span v-if="isOptionCorrect(opt, q.correct_answer)" class="badge badge-success"><i class="bi bi-check"></i></span>
              <span v-if="!isOptionCorrect(opt, q.correct_answer)" class="badge badge-danger"><i class="bi bi-x"></i></span>
              {{ opt }}
          </li>
        </ul>

        <small class="text-muted"
          :class="{ typing: isTyping && index === questions.length - 1 }"
        >
          <i><strong>Feedback: </strong>{{ q.explanation }}</i>
        </small>
      </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <button v-if="questions.length > 0 && !isTyping && !isGenerating"
            class="btn btn-success d-inline-block"
            @click="saveQuiz"
            >
            Guardar Quiz a la BD
        </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import { generateQuestions } from "@/services/aiService"

const topic = ref("")
const level = ref("basico")
const language = ref("valencia")
const numQuestions = ref(5)

const questions = ref([])
const loading = ref(false)
const isTyping = ref(false)
const isGenerating = ref(false)
const error = ref("")

/*
|--------------------------------------------------------------------------
| Generate questions
|--------------------------------------------------------------------------
*/
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
  }
  catch (err) {
    error.value =
      err.response?.data?.error ||
      err.message ||
      "Error generant preguntes"
  }
  finally {
    loading.value = false
    isTyping.value = false
    isGenerating.value = false
  }
}

// Normalitza les dades del JSON en funció dels tipus de preguntes
function normalizeQuestions(rawQuestions) {
  return rawQuestions.map(q => {
    let options = [];

    if (Array.isArray(q.options)) {
      options = q.options;
    } else if (q.options && typeof q.options === "object") {
      options = Object.entries(q.options).map(([key, val]) => `${key}: ${val}`);
    }

    return {
      question: q.question || "",
      question_type: q.question_type || "",
      options,
      correct_answer: q.correct_answer,
      explanation: q.explanation || ""
    };
  });
}

function isOptionCorrect(opt, correct) {
  if (Array.isArray(correct)) return correct.includes(opt);
  if (typeof correct === "object") return Object.values(correct).includes(opt);
  return opt === correct;
}

// Efecte Typing
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


// Sleep helper
function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
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
  0%, 50%, 100% {
    opacity: 1;
  }
  25%, 75% {
    opacity: 0;
  }
}
</style>