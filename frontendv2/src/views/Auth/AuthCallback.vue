<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import authService from '../../services/authService'

const router = useRouter()

onMounted(async () => {
  const params = new URLSearchParams(window.location.search)
  const code = params.get('code')

  if (!code) {
    return router.push('/signin')
  }

  try {
    const res = await authService.authWithMicrosoft(code)

    authService.saveToken(res.token)

    router.push('/dashboard')

  } catch (err) {
    console.error(err)
    router.push('/signin')
  }
})
</script>