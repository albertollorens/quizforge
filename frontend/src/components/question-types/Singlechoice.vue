<script setup>
import { ref } from 'vue'
const emit = defineEmits(['submit'])

const title = ref('')
const statement = ref('')
const answers = ref([
  { text: '', correct: true },
  { text: '', correct: false },
  { text: '', correct: false },
  { text: '', correct: false }
])

function setCorrect(index) {
  answers.value.forEach((a, i) => {
    a.correct = i === index
  })
}

function submit() {  
  emit('submit', {
    title: title.value,
    statement: statement.value,
    answers: answers.value
  })
  resetForm()
}

function resetForm() {
  title.value = ''
  statement.value = ''
  answers.value = createEmptyAnswers()
}

function createEmptyAnswer() {
  return {
    text: '',
    correct: false
  }
}

function createEmptyAnswers() {
  return [
    createEmptyAnswer(),
    createEmptyAnswer(),
    createEmptyAnswer(),
    createEmptyAnswer()
  ]
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