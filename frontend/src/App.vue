<template>
  <div class="bg-light min-vh-100">
    <router-view />

    <!-- Snackbar notificació -->
    <transition name="fade">
      <div v-if="sessionExpired" class="session-toast">
        La sessió ha expirat. Torna a iniciar sessió.
        <button @click="redirectLogin">OK</button>
      </div>
    </transition>
  </div>
</template>

<script setup>
function redirectLogin() {
  sessionExpired.value = false
  window.location.href = '/login'
}
</script>

<style>
.session-toast {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  background: #ffc107;
  color: #000;
  padding: 1rem 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>