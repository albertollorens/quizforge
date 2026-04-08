<template>
  <section id="demo" class="reveal py-5 demo-section text-center">
    <div class="container">

      <h2 class="fw-bold mb-2">{{ t('demo.title') }}</h2>
      <p class="text-muted mb-4">{{ t('demo.sub') }}</p>

      <!-- INPUT -->
      <div v-if="step==='input'" class="mx-auto demo-box animate-fade" style="max-width:500px">
        <input 
          v-model="topic"
          class="form-control form-control-lg mb-3"
          :placeholder="t('demo.label')"
          @keyup.enter="runDemo"
        />

        <button 
          class="btn btn-dark w-100 btn-lg"
          :disabled="!topic.trim()"
          @click="runDemo"
        >
          {{ t('demo.btn') }}
        </button>
      </div>

      <!-- LOADING -->
      <div v-if="step==='loading'" class="py-5 animate-fade">
        <div class="spinner-border mb-3"></div>
        <p class="text-muted">{{ t('demo.loading') }}</p>

        <div class="fake-output mt-4">
          <div class="fake-line w-75"></div>
          <div class="fake-line w-50"></div>
          <div class="fake-line w-100"></div>
        </div>
      </div>

      <!-- RESULT -->
      <div v-if="step==='result'" class="mx-auto animate-fade" style="max-width:650px">

        <div 
          v-for="(q, qIndex) in questions" 
          :key="qIndex"
          class="card demo-card p-4 mb-3"
        >
          <strong class="fs-5">
            {{ qIndex + 1 }}. {{ q.displayed }}
          </strong>

          <ul class="mt-3 list-unstyled">
            <li 
              v-for="(opt, i) in q.options"
              :key="i"
              class="option"
              :class="getOptionClass(q, i)"
              @click="selectOption(q, i)"
            >
              {{ String.fromCharCode(65 + i) }}. {{ opt }}
            </li>
          </ul>
        </div>

        <!-- prompt -->
        <p class="small text-muted mt-2">
          Prompt: "{{ topic }}"
        </p>

        <!-- accions -->
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button class="btn btn-primary" @click="runDemo">
            {{ t('demo.more') }}
          </button>

          <button class="btn btn-outline-secondary" @click="resetDemo">
            {{ t('demo.reset') }}
          </button>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const topic = ref('')
const step = ref('input')
const questions = ref([])
let timeout = null

// 🧠 generar preguntes fake però creïbles
function generateFakeQuestion(topic) {
  return {
    question: `Quina de les següents afirmacions sobre ${topic} és correcta?`,
    options: [
      `${topic} és un concepte secundari`,
      `${topic} és un element fonamental`,
      `${topic} no té aplicació pràctica`
    ],
    correctIndex: 1,
    selected: null,
    displayed: ''
  }
}

// ✨ typing effect
function typeEffect(q) {
  let i = 0
  const text = q.question

  const interval = setInterval(() => {
    q.displayed += text[i]
    i++
    if (i >= text.length) clearInterval(interval)
  }, 15)
}

function runDemo() {
  if (!topic.value.trim()) return

  step.value = 'loading'

  const delay = 1200 + Math.random() * 800

  timeout = setTimeout(() => {
    questions.value = [
      generateFakeQuestion(topic.value),
      generateFakeQuestion(topic.value)
    ]

    step.value = 'result'

    // aplicar typing a cada pregunta
    questions.value.forEach((q, i) => {
      setTimeout(() => typeEffect(q), i * 300)
    })

  }, delay)
}

function selectOption(q, index) {
  if (q.selected !== null) return
  q.selected = index
}

function getOptionClass(q, i) {
  return {
    correct: q.selected === i && i === q.correctIndex,
    wrong: q.selected === i && i !== q.correctIndex
  }
}

function resetDemo() {
  step.value = 'input'
  topic.value = ''
  questions.value = []
  clearTimeout(timeout)
}
</script>

<style scoped>
.demo-section {
  background: #f8fafc;
}

/* caixa input */
.demo-box {
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* resultat */
.demo-card {
  border-radius: 16px;
  border: none;
  box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

/* opcions */
.option {
  padding: 10px 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  background: #f1f5f9;
  cursor: pointer;
  opacity: 0;
  transform: translateY(10px);
  animation: optionIn 0.4s ease forwards;
}

.option:nth-child(1) { animation-delay: 0.1s }
.option:nth-child(2) { animation-delay: 0.2s }
.option:nth-child(3) { animation-delay: 0.3s }

.option:hover {
  transform: translateX(5px);
}

/* estats */
.correct {
  background: #dcfce7;
  color: #166534;
  font-weight: 600;
}

.wrong {
  background: #fee2e2;
  color: #991b1b;
}

/* loading fake */
.fake-output {
  max-width: 400px;
  margin: auto;
}

.fake-line {
  height: 10px;
  background: #e2e8f0;
  border-radius: 5px;
  margin-bottom: 10px;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity: 0.6 }
  50% { opacity: 1 }
  100% { opacity: 0.6 }
}

/* animacions */
.animate-fade {
  animation: fade 0.4s ease;
}

@keyframes fade {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes optionIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* dark */
body.dark .demo-section {
  background: #020617;
}

body.dark .demo-box,
body.dark .demo-card {
  background: #0f172a;
}

body.dark .option {
  background: #1e293b;
}
</style>