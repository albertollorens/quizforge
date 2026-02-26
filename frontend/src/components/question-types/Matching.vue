<script setup>
import { ref } from 'vue'

const emit = defineEmits(['submit'])

/*
Matching structure compatible amb Moodle:
left → pregunta
right → resposta correcta
*/
function createEmptyPairs() {
  return [
    { left: '', right: '' },
    { left: '', right: '' },
    { left: '', right: '' }
  ]
}

const title = ref('')
const statement = ref('')
const pairs = ref(createEmptyPairs())

/*
Afegir nova parella
*/
function addPair() {
  pairs.value.push({ left: '', right: '' })
}

/*
Eliminar parella
*/
function removePair(index) {
  if (pairs.value.length > 2)
    pairs.value.splice(index, 1)
}

/*
Reset complet formulari
*/
function resetForm() {
  title.value = ''
  statement.value = ''
  pairs.value = createEmptyPairs()
}

/*
Submit pregunta
*/
function submit() {

  // validar mínim 2 parelles
  const validPairs = pairs.value.filter(
    p => p.left.trim() && p.right.trim()
  )

  if (validPairs.length < 2) {
    alert('Cal mínim 2 parelles')
    return
  }

  emit('submit', {
    type: 'matching',
    title: title.value,
    statement: statement.value,
    pairs: validPairs
  })

  resetForm()
}
</script>

<template>
    <!-- TITLE -->
    <div class="form-floating mb-3">
      <input v-model="title" class="form-control" placeholder="Títol">
      <label>Títol</label>
    </div>

    <!-- STATEMENT -->
    <div class="form-floating mb-3">
      <textarea v-model="statement" class="form-control" placeholder="Enunciat" style="height:100px"></textarea>
      <label>Enunciat</label>
    </div>

    <!-- PAIRS -->
    <label class="form-label">Parelles</label>

    <div
      v-for="(pair,index) in pairs"
      :key="index"
      class="row mb-2 align-items-center"
    >
      <div class="col">
        <input v-model="pair.left"class="form-control" placeholder="Element esquerre">
      </div>
      <div class="col">
        <input v-model="pair.right" class="form-control" placeholder="Element dret">
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