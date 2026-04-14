<script setup>
import { ref, computed } from 'vue'
import QuizFactory from '@/factories/QuizFactory.js'

const emit = defineEmits(['submit'])

// Creem la pregunta via factory
const question = ref(QuizFactory.createQuestion('essay'))

const isFormValid = computed(() =>
  question.value.title.trim() !== '' &&
  question.value.statement.trim() !== ''
)

function submit() {
  if (!isFormValid.value) return alert('Formulari invàlid')

  emit('submit', question.value)
  resetForm()
}

function resetForm() {
  question.value = QuizFactory.createQuestion('essay')
}
</script>

<template>
  <div>
    <div class="form-floating mb-3">
      <input id="titol" v-model="title" class="form-control"/>
      <label for="titol">Títol</label>
    </div>
    <div class="form-floating mb-3">
      <textarea id="enunciat" v-model="statement" class="form-control"></textarea>
      <label for="enunciat">Enunciat</label>
    </div>
    
    <div class="form-floating mb-3">
      <input id="resposta" v-model="answer" class="form-control"/>
      <label for="resposta">Feedback</label>
    </div>    

    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>