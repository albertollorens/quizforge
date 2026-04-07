import { ref, onMounted } from 'vue'

export function useDarkMode() {
  const isDark = ref(false)

  onMounted(() => {
    const saved = localStorage.getItem('dark')
    isDark.value = saved === 'true'
    apply()
  })

  function toggle() {
    isDark.value = !isDark.value
    localStorage.setItem('dark', isDark.value)
    apply()
  }

  function apply() {
    document.body.classList.toggle('dark', isDark.value)
  }

  return { isDark, toggle }
}