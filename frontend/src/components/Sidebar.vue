<template>
  <nav class="bg-dark text-white p-3" style="width: 250px;">
    <img :src="logo" width="150px"/>
    <!--
    <h4 class="text-center mb-4">QuizForge</h4>
    -->
    <br/><br/>
    <ul class="nav flex-column">Menú
      <li class="nav-item">
        <router-link class="nav-link text-white" to="/dashboard">
          <i class="bi bi-speedometer2"></i>  Dashboard
        </router-link>
      </li>
      <li>
        <router-link class="nav-link text-white" to="/nouquiz">
          <i class="bi bi-plus-circle"></i>  Nou quiz
        </router-link>
      </li>
      <li>
        <router-link class="nav-link text-white" to="/aiquiz">
          <i class="bi bi-plus-circle"></i>  AI quiz
        </router-link>
      </li>
      <li class="nav-item mt-3">
        <button class="btn btn-outline-light w-100" @click="logout">
          <i class="bi bi-box-arrow-left"></i>  Sortir
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import router from '../router'
import logo from '@/assets/logo.png'

const logout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>
