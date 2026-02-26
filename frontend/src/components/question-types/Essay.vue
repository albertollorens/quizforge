<script setup>
import { ref } from 'vue'
const emit = defineEmits(['submit'])

const title = ref('')
const statement = ref('')
const answer = ref('')

function resetForm() {
  title.value = ''
  statement.value = ''
  answer.value = ''
}

function submit() {
  emit('submit', {
    title: title.value,
    statement: statement.value,
    answers: []
  })

  resetForm()
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