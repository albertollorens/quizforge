<script setup>
import { computed } from 'vue'
import authService from '../services/authService'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = computed(() => authService.getUser())
const logout = () => {
  authService.logout()
  router.push('/login')
}

// Funció per a navegar al perfil
const goProfile = () => {
  router.push('/profile') // Canvia a la ruta real del teu perfil
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="menu" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- Dropdown usuari -->
          <li class="nav-item dropdown">
            <a 
              class="nav-link dropdown-toggle" 
              href="#" 
              role="button" 
              data-bs-toggle="dropdown" 
              aria-expanded="false"
            >
              👤 {{ user.username }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" @click="goProfile">Canviar perfil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" @click="logout">Tancar sessió</a></li>
            </ul>
          </li>
          <!-- Altres enllaços -->
          <li class="nav-item">
            <router-link class="nav-link" to="/">Inici</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>