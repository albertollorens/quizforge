<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Dashboard</h1>
      <div class="">
        <button class="btn btn-primary" @click="newquiz">Nou quiz</button>
        <!--<button class="btn btn-danger" @click="logout">Sortir</button>-->
      </div>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else>
      <div v-if="quizzes.length === 0" class="alert alert-info">
        No hay quizzes disponibles.
      </div>

      <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col" v-for="quiz in quizzes" :key="quiz.id">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">{{ quiz.title }}</h5>
              <p class="card-text">{{ quiz.description }}</p>
            </div>
            <div class="card-footer text-end">
              <button class="btn btn-primary btn-sm">Vore Quiz</button>
              <button class="btn btn-danger btn-sm">Esborrar Quiz</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="error" class="alert alert-danger mt-4">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import authService from '../services/authService'
import axios from 'axios'

const router = useRouter()
const quizzes = ref([])
const loading = ref(true)
const error = ref('')

const fetchQuizzes = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await axios.get('/api/quizzes', {
      headers: {
        Authorization: `Bearer ${authService.getToken()}`
      }
    })
    quizzes.value = res.data.data // depende de cómo devuelva tu backend
  } catch (err) {
    if (err.response?.status === 401) {
      authService.logout()
      router.push('/login')
    } else {
      error.value = err.response?.data?.error || 'Error al cargar quizzes'
    }
  } finally {
    loading.value = false
  }
}

const logout = () => {
  authService.logout()
  router.push('/login')
}

onMounted(fetchQuizzes)
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
}
</style>
