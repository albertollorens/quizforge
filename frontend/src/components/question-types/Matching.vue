<script setup>
import { ref, computed } from 'vue'
import QuizFactory from '@/factories/QuizFactory.js'

const emit = defineEmits(['submit'])

// Inicialitzem la pregunta via factory
const question = ref(QuizFactory.createQuestion('matching'))

const validPairs = computed(() =>
  question.value.answers.filter(p => p.text.trim() && p.match_pair.trim())
)

const isFormValid = computed(() => validPairs.value.length >= 2)

function addPair() {
  question.value.answers.push({ text: '', match_pair: '' })
}

function removePair(index) {
  if (question.value.answers.length <= 2) return
  question.value.answers.splice(index, 1)
}

function resetForm() {
  question.value = QuizFactory.createQuestion('matching')
}

function submit() {
  if (!isFormValid.value) {
    alert('Cal mínim 2 parelles')
    return
  }

  emit('submit', question.value)
  resetForm()
}
</script>

<template>
    <!-- TITLE -->
    <div class="form-floating mb-3">
      <input v-model="question.title" class="form-control" placeholder="Títol">
      <label>Títol</label>
    </div>

    <!-- STATEMENT -->
    <div class="form-floating mb-3">
      <textarea v-model="question.statement" class="form-control" placeholder="Enunciat" style="height:100px"></textarea>
      <label>Enunciat</label>
    </div>

    <!-- PAIRS -->
    <label class="form-label">Parelles</label>

    <div
      v-for="(pair,index) in question.answers"
      :key="index"
      class="row mb-2 align-items-center"
    >
      <div class="col">
        <input v-model="pair.text"class="form-control" placeholder="Element esquerre">
      </div>
      <div class="col">
        <input v-model="pair.match_pair" class="form-control" placeholder="Element dret">
      </div>
      <div class="col-auto">
        <button
          class="btn btn-danger btn-sm"
          @click="removePair(index)"
        >
          ×
        </button>
      </div>
    </div>

    <!-- ADD -->
    <button
      class="btn btn-secondary btn-sm mb-3"
      @click="addPair"
    >
      Afegir parella
    </button>
    <hr>
    <!-- SUBMIT -->
    <button
      class="btn btn-success"
      @click="submit"
    >
      Afegir pregunta
    </button>
</template>