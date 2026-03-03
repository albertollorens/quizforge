<script setup>
import { ref, computed } from 'vue'
import QuizFactory from '@/factories/QuizFactory.js'

const emit = defineEmits(['submit'])

// Inicialitzem la pregunta via factory
const question = ref(QuizFactory.createQuestion('shortanswer'))

const answerInput = ref('') // text en brut de l'usuari

const isFormValid = computed(() =>
  question.value.title.trim() !== '' &&
  question.value.statement.trim() !== ''
)

function submit() {
  if (!isFormValid.value) return

  // Convertim l'input de l'usuari en array d'answers
  const answersArray = answerInput.value
    .split(' ')
    .map(a => a.trim())
    .filter(a => a.length > 0)
    .map(a => ({ text: a, correct: '' }))

  question.value.answers = answersArray

  emit('submit', question.value)
  resetForm()
}

function resetForm() {
  question.value = QuizFactory.createQuestion('shortanswer')
  answerInput.value = ''
}
</script>

<template>
  <div>
    <div class="form-floating mb-3">
      <input id="titol" v-model="question.title" class="form-control"/>
      <label for="titol">Títol</label>
    </div>
    <div class="form-floating mb-3">
      <textarea id="enunciat" v-model="question.statement" class="form-control"></textarea>
      <label for="enunciat">Enunciat</label>
    </div>
    
    <div class="form-floating mb-3">
      <input id="resposta" v-model="answerInput" class="form-control"/>
      <label for="resposta">Resposta correcta, per a més d'una separar-les amb un espai en blanc</label>
    </div>    

    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>