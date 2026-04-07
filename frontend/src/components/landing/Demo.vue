<template>
  <section id="demo" class="reveal py-5 bg-light text-center">
    <div class="container">

      <h2>{{ t('demo.title') }}</h2>
      <p class="text-muted mb-4">{{ t('demo.sub') }}</p>

      <!-- INPUT -->
      <div v-if="step==='input'" class="mx-auto" style="max-width:500px">
        <input v-model="topic"
               class="form-control mb-3"
               :placeholder="t('demo.label')" />

        <button class="btn btn-dark w-100" @click="runDemo">
          {{ t('demo.btn') }}
        </button>
      </div>

      <!-- LOADING -->
      <div v-if="step==='loading'" class="py-4">
        <div class="spinner-border"></div>
        <p class="mt-3">{{ t('demo.loading') }}</p>
      </div>

      <!-- RESULT -->
      <div v-if="step==='result'" class="text-start mx-auto" style="max-width:600px">
        <div class="card p-3 mb-3">
          <strong>1. {{ topic }}</strong>

          <ul class="mt-2">
            <li>A</li>
            <li class="text-success fw-bold">Correcta</li>
            <li>C</li>
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

function runDemo() {
  if (!topic.value) return
  step.value = 'loading'

  setTimeout(() => {
    step.value = 'result'
  }, 1500)
}

function resetDemo() {
  step.value = 'input'
  topic.value = ''
}
</script>