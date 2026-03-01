<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow w-100" style="max-width: 400px">
      <div class="card-body">
        <h3 class="text-center mb-4">
          Registre de nou usuari
        </h3>
        <form @submit.prevent="register">
          <div class="mb-3">
            <label class="form-label">Usuari</label>
            <input v-model="username" type="text" placeholder="Usuario" required  class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input v-model="email" type="email" placeholder="Email" required  class="form-control" />
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input v-model="password" type="password" placeholder="Contraseña" required  class="form-control" />
          </div>
          
          <p class="mt-3">            
            <router-link to="/login">Iniciar sessió</router-link>
          </p>

          <button class="btn btn-primary w-100">
            Enviar
          </button>
        </form>
      </div>
      <p v-if="message">{{ message }}</p>
      <p v-if="error">{{ error }}</p>      
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import authService from '../services/authService'
import { useRouter } from 'vue-router';

const username = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const message = ref('')

const router = useRouter()

const register = async () => {
   try {
    const res = await authService.register(username.value, email.value, password.value)
    // Guardar token
    authService.saveToken(res.data.token)
    message.value = res.message;
    error.value = "";
    // Redirigir a dashboard
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  }
};
</script>
