<template>
    <div class="d-flex flex-column align-items-center mt-5">
        <div class="spinner-border text-primary mb-3"></div>
        <p>Connectant amb el teu compte...</p>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import authService from '@/services/authService'

const router = useRouter()

onMounted(() => {

  const ok = authService.processOAuthFromUrl()

  if (ok) {
    router.push('/dashboard')
  } else {
    router.push('/login')
  }

})
</script>