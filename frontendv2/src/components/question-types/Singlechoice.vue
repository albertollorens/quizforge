<script setup>
import { ref } from 'vue'
import QuizFactory from '@/factories/quizFactory.js'

const emit = defineEmits(['submit'])

// Inicialitzem la pregunta via factory
const question = ref(QuizFactory.createQuestion('singlechoice'))

function setCorrect(index) {
  answers.value.forEach((a, i) => {
    a.correct = i === index
  })
}

function submit() {  
  emit('submit', question.value)
  resetForm()
}

function resetForm() {
  question.value = QuizFactory.createQuestion('singlechoice')
}
</script>

<template>
  <div>
    <div class="form-floating mb-3">
      <input id="titol" class="form-control" v-model="title" />
      <label for="titol">Títol</label>
    </div>
    <div class="form-floating mb-3">
      <textarea id="enunciat" class="form-control" v-model="statement"></textarea>
      <label for="enunciat">Enunciat</label>
    </div>

    <div v-for="(a, index) in answers" :key="index">
      <div class="input-group mb-2">
        <span class="input-group-text">
          <input class="form-check mt-0" type="radio" name="choice" :checked="a.correct" @change="setCorrect(index)" />
        </span>
        <input type="text" class="form-control" placeholder="Resposta" v-model="a.text">
      </div>
    </div>
    <br/>
    <button class="btn btn-success" @click="submit">Afegir</button>
  </div>
</template>