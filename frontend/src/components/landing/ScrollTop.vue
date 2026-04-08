<template>
  <transition name="fade">
    <button
      v-if="visible"
      class="scroll-top-btn"
      @click="scrollToTop"
      aria-label="Torna a l'inici"
    >
      <!-- icona fletxa simple -->
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
      </svg>
    </button>
  </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const visible = ref(false)

function onScroll() {
  visible.value = window.scrollY > 200
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  window.addEventListener('scroll', onScroll)
  onScroll() // comprova l'estat inicial
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
})
</script>

<style scoped>
.scroll-top-btn {
  position: fixed;      /* fix a la finestra, no al contenidor */
  bottom: 30px;         /* a 30px del bottom */
  right: 30px;          /* a 30px de la dreta */
  background: linear-gradient(135deg, #6366f1, #f97316);
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  cursor: pointer;
  z-index: 9999;        /* sempre al davant */
  display: flex;
  align-items: right;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  transition: transform 0.3s, box-shadow 0.3s;
}

body.dark .scroll-top-btn {
  background: linear-gradient(135deg, #6366f1, #f97316);
}

.scroll-top-btn:hover {
  transform: translateY(-4px) scale(1.1);
  box-shadow: 0 12px 30px rgba(0,0,0,0.3);
} 

/* transició suau */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>