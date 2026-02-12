<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow w-100" style="max-width: 400px">
      <div class="card-body">
        <h3 class="text-center mb-4">
          QuizForge 🔐
        </h3>

        <form @submit.prevent="login">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input v-model="email" type="email" class="form-control" />
          </div>

          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input v-model="password" type="password" class="form-control" />
          </div>

          <p class="mt-3">            
            <router-link to="/register">Registrar-se</router-link>
          </p>

          <button class="btn btn-primary w-100">
            Entrar
          </button>
        </form>
        <br/>
        <p v-if="error">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import authService from '../services/authService'
import { useRouter } from 'vue-router';

const email = ref('')
const password = ref('')
const error = ref('')

const router = useRouter()

const login = async () => {
  try {
    const res = await authService.login(email.value, password.value)
    // Guardar token
    authService.saveToken(res.data.token)
    // Redirigir a dashboard
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  }
}
</script>
