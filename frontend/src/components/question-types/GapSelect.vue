<script setup>
import { ref } from 'vue'
const emit = defineEmits(['submit'])

function createEmptyPlaceholders() {
  return []
}

const title = ref('')
const statement = ref('')
const placeholders = ref(createEmptyPlaceholders())
const answers = ref('')

/* ==========================
   ANALITZAR TEXT MANUALMENT
========================== */

function analyzeText() {

  if (!statement.value.trim()) {
    placeholders.value = []
    answers.value = {}
    return
  }

  const regex = /\[\[(.*?)\]\]/g
  const matches = [...statement.value.matchAll(regex)]
  const keys = matches.map(m => m[1])

  // Eliminem duplicats
  const uniqueKeys = [...new Set(keys)]

  placeholders.value = uniqueKeys

  // Mantindre respostes existents
  const updatedAnswers = {}
  uniqueKeys.forEach(key => {
    updatedAnswers[key] = answers.value[key] || ''
  })

  answers.value = updatedAnswers
}


function submit() {
  emit('submit', {
    title: title.value,
    statement: statement.value,
    answers: { ...answers.value }
  })

  resetForm()
}

function resetForm() {
  title.value = ''
  statement.value = ''
  placeholders.value = createEmptyPlaceholders()
}
</script>

<template>
  <div>
    <div class="form-floating mb-3">
      <input id="titol" v-model="title" class="form-control"/>
      <label for="titol">Títol</label>
    </div>
    <div class="form-floating mb-3">
      <textarea 
        id="enunciat" 
        v-model="statement" 
        class="form-control" 
        @blur="analyzeText"
        placeholder="Exemple: La capital de [[1]] és [[2]]">
      </textarea>
    </div>
    <div class="form-floating mb-3">
      <ul v-if="placeholders && placeholders.length">
        <li v-for="key in placeholders" :key="key">
        [[{{ key }}]]
        <input
          type="text"
          class="form-control mt-1"
          v-model="answers[key]"
        >
      </li>
      </ul>      
    </div>

    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>