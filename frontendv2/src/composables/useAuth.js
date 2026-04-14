import { ref } from 'vue'

const user = ref(null)

export function useAuth() {
  const loadUser = () => {
    const stored = localStorage.getItem('user')
    user.value = stored ? JSON.parse(stored) : null
  }

  return {
    user,
    loadUser
  }
}