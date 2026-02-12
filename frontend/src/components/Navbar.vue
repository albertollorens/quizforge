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
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <!--<a class="navbar-brand">QuizForge</a>-->

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="menu" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link">👤 {{user.username}}</a>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/">Inici</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click="logout">Sortir</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>