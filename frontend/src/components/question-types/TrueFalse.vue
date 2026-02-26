<script setup>
import { ref } from 'vue'
const emit = defineEmits(['submit'])

const title = ref('')
const statement = ref('')
const correct = ref(true)

function submit() {
  emit('submit', {
    title: title.value,
    statement: statement.value,
    answer: correct.value
  })

  resetForm()
}

function resetForm() {
  title.value = ''
  statement.value = ''
  correct.value = true
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

    <div class="form-check">
      <input class="form-check-input" type="radio" name="radioT" id="radioT1" v-model="correct" :value="true">
      <label class="form-check-label" for="radioT">
        True
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="radioT" id="radioT1" v-model="correct" :value="false">
      <label class="form-check-label" for="radioT">
        False
      </label>
    </div>    
    <br/>
    <button class="btn btn-primary" @click="submit">Afegir</button>
  </div>
</template>