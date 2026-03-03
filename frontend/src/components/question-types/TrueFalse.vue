<script setup>
import { ref, computed } from 'vue'
import QuizFactory from '@/factories/QuizFactory.js'

const emit = defineEmits(['submit'])

const isFormValid = computed(() =>
  question.value.title.trim() !== '' &&
  question.value.statement.trim() !== ''
)

function submit() {
  if (!isFormValid.value) return alert('Formulari invàlid')

  // Assignem l'answer correcta
  question.value.answer = question.value.answer ?? true

  emit('submit', question.value)
  resetForm()
}

function resetForm() {
  question.value = QuizFactory.createQuestion('truefalse')
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

    <div class="form-check">
      <input class="form-check-input" type="radio" name="radioT" id="radioT1" v-model="question.answer" :value="true">
      <label class="form-check-label" for="radioT">
        True
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="radioT" id="radioT1" v-model="question.answer" :value="false">
      <label class="form-check-label" for="radioT">
        False
      </label>
    </div>    
    <br/>
    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>