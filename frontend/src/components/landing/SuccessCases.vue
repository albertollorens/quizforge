<template>
  <section id="success" class="py-5 reveal success-section">
    <div class="container">
      <div class="text-center">
        <h2 class="fw-bold mb-3 animate-item">{{ t('success.title') }}</h2>
        <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub1') }}</p>
      </div>

      <!-- 👨‍🏫 PROFESSORS -->
      <div class="horizontal-scroll animate-item delay-2" 
          ref="profScroll"
          @mouseenter="pauseScroll(profScroll)"
          @mouseleave="resumeScroll(profScroll, 0.6)">

        <div class="scroll-item" v-for="(caseItem, i) in successCasesDup" :key="i">

          <div class="testimonial-card flex gap-1 mb-4 text-gray-900">

            <!-- ⭐ STARS -->
            <div class="flex gap-1 mb-4 text-gray-900">
                <iconify-icon
                  v-for="n in caseItem.stars"
                  :key="n"
                  icon="solar:star-bold"
                  width="16"
                ></iconify-icon>
            </div>

            <!-- TEXT -->
            <p class="testimonial-text text-sm text-gray-700 mb-6 leading-relaxed">
              "{{ caseItem.description }}"
            </p>

            <!-- USER -->
            <div class="flex gap-3 mt-4">
              <div class="avatar" :class="caseItem.color">
                {{ caseItem.initials }}
              </div>
              <div>
                <p class="name">{{ caseItem.name }}</p>
                <p class="role">{{ caseItem.role }}</p>
              </div>
            </div>
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
          <div class="success-card text-center">
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
  { name: 'Marta Ribera', role: "Professora d'Història, IES Lluís Vives", initials: 'MR', color: 'bg-blue-100 text-blue-700', description: t('success.prof1'), stars: getRandomStars() },
  { name: 'Joan Pastor', role: "Departament d'Informàtica, FP", initials: 'JP', color: 'bg-emerald-100 text-emerald-700', description: t('success.prof2'), stars: getRandomStars() },
  { name: 'Carme Garcia', role: "Mestra de Primària", initials: 'CG', color: 'bg-orange-100 text-orange-700', description: t('success.prof3'), stars: getRandomStars() },
  { name: 'Pere Llorca', role: "Responsable Acadèmia Avança", initials: 'PL', color: 'bg-orange-100 text-yellow-700', description: t('success.prof4'), stars: getRandomStars() }
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

function getRandomStars() {
  return Math.floor(Math.random() * 5) + 2
}
</script>

<style scoped>
.success-section {
  background: linear-gradient(
    180deg,
    rgba(37, 99, 235, 0.05),
    rgba(249, 115, 22, 0.05)
  );
  position: relative;
}

/* ✨ Glow subtil corporatiu */
.success-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top left, rgba(37, 99, 235, 0.15), transparent 60%),
              radial-gradient(circle at bottom right, rgba(249, 115, 22, 0.15), transparent 60%);
  pointer-events: none;
}

.horizontal-scroll {
  display: flex;
  gap: 20px;
  overflow-x: hidden;
  padding-bottom: 10px;
}

.scroll-item {
  flex: 0 0 380px;
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

/* TESTIMONIAL CARD */
.testimonial-card {
  background: white;
  padding: 20px;
  border-radius: 16px;
  border: 1px solid #e5e7eb;  
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

/* STARS */
.stars {
  font-size: 14px;
  color: #111827;
}

/* TEXT */
.testimonial-text {
  font-size: 14px;
  color: #374151;
  line-height: 1.6;
}

/* USER */
.avatar {
  width: 32px;
  height: 32px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 600;
}

.name {
  font-size: 14px;
  font-weight: 600;
  color: #111827;
  margin-bottom: 0px;
}

.role {
  font-size: 12px;
  color: #6b7280;
}

/* 🌙 DARK MODE SuccesCases */
body.dark .success-section {
  background: #010414; /* fosc profund i net */
  position: relative;
}

/* Glow corporatiu subtil, diferent del Features */
body.dark .success-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top left, rgba(37,99,235,0.1), transparent 60%),
              radial-gradient(circle at bottom right, rgba(249,115,22,0.1), transparent 60%);
  pointer-events: none;
}

/* Cards dark mode */
body.dark .success-card {
  background: #0f172a;
  border: 1px solid #1e293b;
  box-shadow: 0 10px 30px rgba(0,0,0,0.4);
  transition: all 0.3s ease;
}

/* Hover cards */
body.dark .success-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 25px 60px rgba(0,0,0,0.6);
}

/* Glow hover diferenciat */
body.dark .success-card::before {
  background: radial-gradient(circle at top, rgba(99,102,241,0.25), transparent);
}

/* Text */
body.dark .success-card h5 {
  color: #e2e8f0;
}

body.dark .success-card p {
  color: #94a3b8;
}

/* Professors / centres media */
body.dark .success-media img {
  filter: brightness(0.9) contrast(1.1);
  transition: all 0.3s ease;
}

body.dark .success-card:hover .success-media img {
  transform: scale(1.05);
  filter: brightness(1.1);
}

body.dark .testimonial-card {
  background: #0f172a;
  border: 1px solid #1e293b;
}

body.dark .testimonial-text {
  color: #cbd5f5;
}

body.dark .name {
  color: #e2e8f0;
}

body.dark .role {
  color: #94a3b8;
}
</style>