<script setup>
import { ref, computed } from 'vue'

import Singlechoice from './question-types/Singlechoice.vue'
import Multichoice from './question-types/Multichoice.vue'
import Matching from './question-types/Matching.vue'
import TrueFalse from './question-types/TrueFalse.vue'
import ShortAnswer from './question-types/ShortAnswer.vue'
import Essay from './question-types/Essay.vue'

const emit = defineEmits(['add'])

const type = ref('')

const components = {
  singlechoice: Singlechoice,
  multichoice: Multichoice,
  matching: Matching,
  truefalse: TrueFalse,
  shortanswer: ShortAnswer,
  essay: Essay
}

const currentComponent = computed(() => components[type.value])

function handleSubmit(questionData) {
  emit('add', {
    type: type.value,
    ...questionData
  })
}
</script>

<template>
  <div>
    <h4>Crear nova pregunta</h4>
    <div class="mb-3">
      <select v-model="type" class="form-select">
        <option value="">Selecciona el tipus de pregunta</option>
        <option value="singlechoice">Opció única</option>
        <option value="multichoice">Multiresposta</option>
        <option value="matching">Emparellaments</option>
        <option value="truefalse">True / False</option>
        <option value="shortanswer">Resposta curta</option>
        <option value="essay">Assaig</option>
      </select>
    </div>

    <component
      :is="currentComponent"
      @submit="handleSubmit"
    />
  </div>
</template>