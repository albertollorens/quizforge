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

        <!-- ✨ fake IA output -->
        <div class="fake-output mt-4">
          <div class="fake-line w-75"></div>
          <div class="fake-line w-50"></div>
          <div class="fake-line w-100"></div>
        </div>

      </div>

      <!-- RESULT -->
      <div v-if="step==='result'" class="mx-auto animate-fade" style="max-width:600px">

        <div class="card demo-card p-4 mb-3">

          <strong class="fs-5">1. {{ topic }}</strong>

          <ul class="mt-3 list-unstyled">
            <li class="option">A. Lorem ipsum</li>
            <li class="option correct">B. Correcta</li>
            <li class="option">C. Lorem ipsum</li>
          </ul>

        </div>

        <button class="btn btn-outline-secondary" @click="resetDemo">
          {{ t('demo.reset') }}
        </button>

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
let timeout = null

function runDemo() {
  if (!topic.value.trim()) return

  step.value = 'loading'

  // simular temps variable (més realista)
  const delay = 1200 + Math.random() * 800

  timeout = setTimeout(() => {
    step.value = 'result'
  }, delay)
}

function resetDemo() {
  step.value = 'input'
  topic.value = ''
  clearTimeout(timeout)
}
</script>
<style scoped>
.demo-section {
  background: #f8fafc;
}

/* ✨ caixa input */
.demo-box {
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* 🎴 resultat */
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
  transition: all 0.2s ease;
}

.option:hover {
  transform: translateX(5px);
}

.correct {
  background: #dcfce7;
  font-weight: 600;
  color: #166534;
}

/* 🤖 fake loading */
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

/* ✨ animació entrada */
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

/* 🌙 dark mode */
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