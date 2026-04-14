import { ref, onMounted } from 'vue'

export function useScrollSpy(sectionIds) {
  const activeSection = ref('')

  onMounted(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            activeSection.value = entry.target.id
          }
        })
      },
      {
        threshold: 0.6
      }
    )

    sectionIds.forEach(id => {
      const el = document.getElementById(id)
      if (el) observer.observe(el)
    })
  })

  return { activeSection }
}