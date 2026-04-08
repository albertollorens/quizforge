<template>
  <section id="success" class="py-5 reveal success-section">
    <div class="container text-center">

      <h2 class="fw-bold mb-3 animate-item">{{ t('success.title') }}</h2>
      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub1') }}</p>

      <!-- 👨‍🏫 PROFESSORS -->
      <div class="horizontal-scroll animate-item delay-2" 
           ref="profScroll"
           @mouseenter="pauseScroll(profScroll)"
           @mouseleave="resumeScroll(profScroll, 0.5)">
        <div class="scroll-item" v-for="(caseItem, i) in successCasesDup" :key="i">
          <div class="success-card">            
            <h5 class="fw-semibold">{{ caseItem.name }}</h5>
            <p class="text-muted small"><i>"{{ caseItem.description }}"</i></p>
          </div>
        </div>
      </div>

      <br/><br/>

      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub2') }}</p>

      <!-- 🏫 CENTRES -->
      <div class="horizontal-scroll animate-item delay-2" 
           ref="centresScroll"
           @mouseenter="pauseScroll(centresScroll)"
           @mouseleave="resumeScroll(centresScroll, 0.8)">
        <div class="scroll-item" v-for="(caseItem, i) in successIESDup" :key="i">
          <div class="success-card">
            <div class="success-media mb-3">
              <img :src="caseItem.img" class="img-fluid rounded" alt="">
            </div>
            <h5 class="fw-semibold">{{ caseItem.name }}</h5>
            <p class="text-muted small">{{ caseItem.description }}</p>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'
import { nextTick } from 'vue'

// Imatges centres
import centre1 from '@/assets/img/iessimarro.jpg'
import centre2 from '@/assets/img/aulaxativa.png'
import centre3 from '@/assets/img/iesjribera.jpg'
import centre4 from '@/assets/img/iesporçons.jpg'

useReveal()
const { t } = useI18n()

// Dades professors
const successCases = [
  { name: 'Joan Piera Climent', description: t('success.prof1') },
  { name: 'Maria Llopis Serra', description: t('success.prof2') },
  { name: 'Pere Mateu Saez', description: t('success.prof3') },
  { name: 'Laura Martí Gisbert', description: t('success.prof4') }
]

// Dades centres
const successIES = [
  { name: 'IES Lluís Simarro', description: t('success.case1'), img: centre1 },
  { name: 'Aula Xàtiva Formación', description: t('success.case2'), img: centre2 },
  { name: 'IES Josep de Ribera', description: t('success.case3'), img: centre3 },
  { name: 'IES Porçons (Aielo)', description: t('success.case4'), img: centre4 }
]

// Duplicació 2x per scroll infinit
const successCasesDup = computed(() => [...successCases, ...successCases])
const successIESDup = computed(() => [...successIES, ...successIES])

// refs dels scrolls
const profScroll = ref(null)
const centresScroll = ref(null)

// Funció scroll infinit
function startAutoScroll(refEl, speed) {
  if (!refEl.value) return

  // Evitar múltiples animacions
  if (refEl.value._animId) return

  const step = () => {
    if (!refEl.value) return

    refEl.value.scrollLeft += speed

    const resetPoint = refEl.value.scrollWidth / 2
    if (refEl.value.scrollLeft >= resetPoint) {
      refEl.value.scrollLeft -= resetPoint
    }

    refEl.value._animId = requestAnimationFrame(step)
  }

  refEl.value._animId = requestAnimationFrame(step)
}

function pauseScroll(refEl) {
  if (!refEl.value) return

  if (refEl.value._animId) {
    cancelAnimationFrame(refEl.value._animId)
    refEl.value._animId = null
  }
}

function resumeScroll(refEl, speed) {
  if (!refEl.value) return
  startAutoScroll(refEl, speed)
}

onMounted(async () => {
  await nextTick()

  startAutoScroll(profScroll, 0.6)
  startAutoScroll(centresScroll, 0.9)

  // 👉 PROFESSORS
  profScroll.value.addEventListener('mouseenter', () => pauseScroll(profScroll))
  profScroll.value.addEventListener('mouseleave', () => resumeScroll(profScroll, 0.6))

  // 👉 CENTRES
  centresScroll.value.addEventListener('mouseenter', () => pauseScroll(centresScroll))
  centresScroll.value.addEventListener('mouseleave', () => resumeScroll(centresScroll, 0.9))
})

onBeforeUnmount(() => {
  pauseScroll(profScroll)
  pauseScroll(centresScroll)

  if (profScroll.value) {
    profScroll.value.replaceWith(profScroll.value.cloneNode(true))
  }
  if (centresScroll.value) {
    centresScroll.value.replaceWith(centresScroll.value.cloneNode(true))
  }
})
</script>

<style scoped>
.horizontal-scroll {
  display: flex;
  gap: 20px;
  overflow-x: hidden;
  padding-bottom: 10px;
}

.scroll-item {
  flex: 0 0 auto;
  min-width: 300px; /* més ample per professors petits */
}

/* Cards */
.success-card {
  background: white;
  padding: 20px;
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.success-card.text-only {
  min-height: 120px;
  padding: 25px 20px;
}

.success-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top, rgba(99,102,241,0.15), transparent);
  opacity: 0;
  transition: 0.3s;
}

.success-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

.success-card:hover::before {
  opacity: 1;
}

.success-media {
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.success-media img {
  max-height: 100%;
  object-fit: contain;
}

/* 🌙 dark */
body.dark .feature-card {
  background: #0f172a;
  border-color: #1e293b;
}
</style>