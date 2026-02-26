<script setup>
import { computed, ref } from 'vue'

const emit = defineEmits(['submit'])

function createEmptyAnswer() {
  return {
    text: '',
    correct: false,
    weight: 0
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

const title = ref('')
const statement = ref('')
const answers = ref(createEmptyAnswers())

const weightOptions = [
  100, 90, 83.333, 80, 75, 66.666, 60, 50, 40, 33.333,
  25, 20, 16.666, 14.285, 12.5, 11.111, 10, 5, 0,
  -5, -10, -11.111, -12.5, -14.285, -16.666, -20,
  -25, -33.333, -40, -50, -60, -66.666, -75, -80,
  -83.333, -90, -100
]

const totalWeight = computed(() => {
  return answers.value
    .filter(a => a.correct)
    .reduce((sum, a) => sum + (Number(a.weight) || 0), 0)
})

const TOLERANCE = 0.01

const isWeightValid = computed(() => {
  return Math.abs(totalWeight.value - 100) < TOLERANCE
})

const hasEnoughAnswers = computed(() => {
  return answers.value.filter(a => a.text.trim() !== '').length >= 2
})

const isFormValid = computed(() => {
  return (
    title.value.trim() !== '' &&
    statement.value.trim() !== '' &&
    hasEnoughAnswers.value &&
    isWeightValid.value
  )
})

function addAnswer() {
  answers.value.push(createEmptyAnswer())
}

function removeAnswer(index) {
  if (answers.value.length <= 2) return
  answers.value.splice(index, 1)
}

function submit() {

  if (!isFormValid.value) {
    alert('Formulari invàlid')
    return
  }

  emit('submit', {
    type: 'multichoice',
    title: title.value,
    statement: statement.value,

    answers: answers.value
      .filter(a => a.text.trim() !== '')
      .map(a => ({
        text: a.text,
        correct: a.correct,
        weight: a.correct ? a.weight : 0
      }))
  })

  resetForm()
}

function resetForm() {
  title.value = ''
  statement.value = ''
  answers.value = createEmptyAnswers()
}
</script>

<template>
<div>
  <!-- TITLE -->
  <div class="form-floating mb-3">
    <input
      id="title"
      v-model="title"
      class="form-control"
      placeholder="Títol"
    />
    <label for="title">Títol</label>
  </div>

  <!-- STATEMENT -->
  <div class="form-floating mb-3">
    <textarea
      id="statement"
      v-model="statement"
      class="form-control"
      placeholder="Enunciat"
      style="height:100px"
    ></textarea>
    <label for="statement">Enunciat</label>
  </div>

  <!-- ANSWERS -->
  <div
    v-for="(a, index) in answers"
    :key="index"
    class="input-group mb-2"
  >
    <!-- CHECK + WEIGHT -->
    <div class="input-group-text">
      <input
        class="form-check mt-0"
        type="checkbox"
        v-model="a.correct"
      >

      <select
        v-if="a.correct"
        v-model.number="a.weight"
        class="form-select ms-2"
        style="max-width:140px"
      >
        <option
          v-for="w in weightOptions"
          :key="w"
          :value="w"
        >
          {{ w > 0 ? '+' : '' }}{{ w }}%
        </option>
      </select>
    </div>

    <!-- TEXT -->
    <input
      v-model="a.text"
      type="text"
      class="form-control"
      placeholder="Resposta"
    >

    <!-- DELETE -->
    <button
      class="btn btn-outline-danger"
      @click="removeAnswer(index)"
      :disabled="answers.length <= 2"
      title="Eliminar resposta"
    >
      ✕
    </button>

  </div>

  <!-- ADD BUTTON -->
  <button
    class="btn btn-outline-primary btn-sm mt-2"
    @click="addAnswer"
  >
    + Afegir resposta
  </button>

  <!-- PROGRESS BAR -->
  <div class="progress mt-3">
    <div
      class="progress-bar"
      :class="isWeightValid ? 'bg-success' : 'bg-danger'"
      :style="{ width: totalWeight + '%' }"
    >
      {{ totalWeight.toFixed(2) }}%
    </div>
  </div>

  <small
    :class="isWeightValid ? 'text-success' : 'text-danger'"
  >
    Pes total respostes correctes: {{ totalWeight.toFixed(2) }}%
  </small>

  <div
    v-if="!isWeightValid"
    class="text-danger small"
  >
    El pes total ha de ser 100%
  </div>

  <!-- SUBMIT -->
  <button
    class="btn btn-success mt-3"
    @click="submit"
    :disabled="!isFormValid"
  >
    Afegir pregunta
  </button>

</div>
</template>