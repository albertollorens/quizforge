<template>
    <div class="card p-4">
    <h3>Generador IA de preguntes</h3>
    <div class="form-floating mb-3">
        <input id="topic" v-model="topic" placeholder="Escriu el tema sobre el que desitjes generar les preguntes" class="form-control mb-2">
        <label for="topic">Tema</label>
    </div>

    <div class="form-floating mb-3">
        <select id="level" v-model="level" class="form-control mb-2">        
            <option value="basic">Bàsic</option>
            <option value="medium">Mitjà</option>
            <option value="advanced">Avançat</option>
        </select>
        <label for="level">Nivell</label>
    </div>

    <button @click="generate" class="btn btn-primary">
        Generar
    </button>

    <div v-if="questions.length">
        <div v-for="q in questions" class="mt-3">
        <strong>{{ q.question }}</strong>
        <ul>
            <li v-for="opt in q.options">{{ opt }}</li>
        </ul>
        <small>{{ q.explanation }}</small>
        </div>
    </div>
    </div>
</template>

<script setup>
import { ref } from "vue"
import { generateQuestions } from '@/services/aiService'

const topic = ref("")
const level = ref("")
const questions = ref([])

async function generate () {    
    const res = await generateQuestions({
        topic: topic.value,
        level: level.value
    })
    questions.value = res.data.data.questions
}
</script>