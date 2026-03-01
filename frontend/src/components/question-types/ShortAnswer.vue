<script setup>
import { ref } from 'vue'
const emit = defineEmits(['submit'])

const title = ref('')
const statement = ref('')
const answer = ref('')

function submit() {
  // 1.- Dividim el text per ", " i eliminem possibles espais en blanc al principi/final
  const answersArray = answer.value
    .split(', ')
    .map(a => a.trim())
    .filter(a => a.length > 0) // eliminem valors buits

  // 2.- Map a objectes correctes
  const answersPayload = answersArray.map(a => ({ text: a, correct: true }))

  emit('submit', {
    title: title.value,
    statement: statement.value,
    answers: answersPayload
  })

  resetForm()
}

function resetForm() {
  title.value = ''
  statement.value = ''
  answer.value = ''
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
      <label for="resposta">Resposta correcta, per a més d'una separar per , (coma+espai)</label>
    </div>    

    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>