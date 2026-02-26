<template>
  <div class="container my-4">
    <h2>Editar Perfil</h2>

    <div class="card shadow-sm mt-4">
      <div class="card-body">
        <form @submit.prevent="handleSave">

          <div class="mb-3">
            <label class="form-label">Nom d'usuari</label>
            <input v-model="form.username" type="text" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input v-model="form.email" type="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nova contrasenya (opcional)</label>
            <input v-model="form.password" type="password" class="form-control">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-floppy"></i> Guardar
            </button>
            <router-link class="btn btn-secondary" to="/dashboard">
                <i class="bi bi-x"></i> Cancel·lar
            </router-link>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import authService from '@/services/authService'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({
  username: '',
  email: '',
  password: ''
})

// Carreguem dades actuals de l'usuari
onMounted(() => {
  const user = authService.getUser()
  form.value.username = user.username
  form.value.email = user.email
})

// Guardar canvis
const handleSave = async () => {
  try {
    await authService.updateProfile(form.value)
    alert('Perfil actualitzat correctament!')
    router.push('/') // Torna a l’inici o dashboard
  } catch (err) {
    console.error(err)
    alert('Error actualitzant el perfil')
  }
}
</script>

<style scoped>
.container {
  max-width: 600px;
}
</style>