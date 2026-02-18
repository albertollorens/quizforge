<template>
  <nav class="bg-dark text-white p-3" style="width: 250px;">
    <img src="logo.png" width="150px"/>
    <!--
    <h4 class="text-center mb-4">QuizForge</h4>
    -->
    <br/>
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link class="nav-link text-white" to="/dashboard">
          📊 Dashboard
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link text-white" to="/quizzes">
          📝 Quizzes
        </router-link>
      </li>
      <li class="nav-item mt-3">
        <button class="btn btn-outline-light w-100" @click="logout">
          🚪 Sortir
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import router from '../router'

const logout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>
