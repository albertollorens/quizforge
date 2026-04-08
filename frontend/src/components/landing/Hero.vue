<template>
  <section id="hero" class="reveal hero py-5">

    <!-- 🌈 BACKGROUND -->
    <div class="hero-bg"></div>
    <div class="hero-glow" :style="glowStyle"></div>

    <div class="container">
      <div class="row align-items-center">

        <!-- 🧠 TEXT -->
        <div class="col-lg-6 text-center text-lg-start hero-content">

          <span class="badge bg-primary mb-3 animate-item">
            {{ t('hero.badge') }}
          </span>

          <h1 class="display-5 fw-bold mb-3 animate-item delay-1">
            {{ t('hero.title') }}
            <br>
            <span class="typed-text">{{ displayedText }}</span>
            <span class="cursor" aria-hidden="true">|</span>
          </h1>

          <p class="lead text-muted mb-4 animate-item delay-2">
            {{ t('hero.subtitle') }}
          </p>

          <div class="d-flex justify-content-center justify-content gap-3 animate-item delay-3">
            <button class="btn btn-dark btn-lg cta-btn" @click="goToRegister">
              {{ t('hero.cta1') }}
            </button>

            <button class="btn btn-outline-dark btn-lg cta-secondary" @click="scrollToDemo">
              {{ t('hero.cta2') }}
            </button>
          </div>

        </div>

        <!-- 💻 MOCKUP VIDEO -->
        <div class="col-lg-6 mt-5 mt-lg-0 animate-item delay-2">

          <div 
            class="mockup"
            @mousemove="handleMouseMove"
            @mouseleave="resetTilt"
            :style="mockupStyle"
          >
            <div class="mockup-screen">

              <!-- 🎥 VIDEO REAL -->
              <video 
                class="mockup-video"
                autoplay 
                muted 
                loop 
                playsinline
              >
                <source :src="demo" type="video/mp4" />
              </video>

            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed, watch } from 'vue'
import demo from '@/assets/video/demofinal.mp4'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { useReveal } from '@/composables/useReveal'

const router = useRouter()
const { t, locale } = useI18n()

function goToRegister() {
  router.push('/register')
}

function scrollToDemo() {
  const el = document.getElementById('demo')
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}

useReveal()

/* ✍️ TYPING */
const texts = computed(() => {  
  const val = t('hero.typing')

  if (!val) return []

  // Convertir string a array
  return val.split(',')
    .map(s => s.trim())     // eliminar espais
    .filter(Boolean)        // eliminar buits
})

const displayedText = ref('') // string buit inicial
let textIndex = 0
let charIndex = 0
let timeout = null

function type() {
  if (!texts.value.length) return

  const current = texts.value[textIndex] || ''

  if (charIndex < current.length) {
    displayedText.value += current[charIndex] // afegim només un caràcter
    charIndex++
    timeout = setTimeout(type, 50)
  } else {
    timeout = setTimeout(erase, 1400)
  }
}

function erase() {
  if (charIndex > 0) {
    displayedText.value = displayedText.value.slice(0, -1) // esborrem un caràcter
    charIndex--
    timeout = setTimeout(erase, 25)
  } else {
    textIndex = (textIndex + 1) % texts.value.length // passem al següent text
    timeout = setTimeout(type, 400)
  }
}

// reiniciar typing si canviem idioma
watch(locale, () => {
  displayedText.value = ''
  textIndex = 0
  charIndex = 0
  clearTimeout(timeout)
  type()
})

onMounted(() => {
  if (texts.value.length) type()
})

onBeforeUnmount(() => clearTimeout(timeout))

/* 💡 GLOW */
const glowStyle = ref({})

function handleGlobalMouse(e) {
  glowStyle.value = {
    background: `radial-gradient(circle at ${e.clientX}px ${e.clientY}px, rgba(99,102,241,0.15), transparent 40%)`
  }
}

/* 🧲 MOCKUP TILT */
const mockupStyle = ref({})
let frame = null

function handleMouseMove(e) {
  const el = e.currentTarget // 🔥 guardar referència

  if (!el) return

  if (frame) cancelAnimationFrame(frame)

  frame = requestAnimationFrame(() => {
    const rect = e.currentTarget.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    const rotateY = ((x - rect.width / 2) / rect.width) * 8
    const rotateX = -((y - rect.height / 2) / rect.height) * 8

    mockupStyle.value = {
      transform: `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`
    }
  })
}

function resetTilt() {
  mockupStyle.value = {
    transform: 'rotateX(0deg) rotateY(0deg)'
  }
}

/* 🌊 PARALLAX */
function handleScroll() {
  const y = window.scrollY
  const hero = document.getElementById('hero')
  if (hero) hero.style.backgroundPositionY = `${y * 0.3}px`
}

onMounted(() => {
  window.addEventListener('mousemove', handleGlobalMouse)
  window.addEventListener('scroll', handleScroll)
})

onBeforeUnmount(() => {
  window.removeEventListener('mousemove', handleGlobalMouse)
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
/* 🌈 HERO BASE */
.hero {
  position: relative;
  overflow: hidden;
  min-height: 70vh;
  display: flex;
  align-items: center;
}

/* 🌈 BACKGROUND */
.hero-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
  background: linear-gradient(120deg, #f8fafc, #eef2ff, #f0f9ff);
  background-size: 200% 200%;
  animation: gradientMove 12s ease infinite;
}

@keyframes gradientMove {
  0% { background-position: 0% 50% }
  50% { background-position: 100% 50% }
  100% { background-position: 0% 50% }
}

.hero-glow {
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
}

/* assegura contingut damunt */
.container {
  position: relative;
  z-index: 2;
}

/* ✨ ANIMACIÓ ENTRADA */
.animate-item {
  opacity: 0;
  transform: translateY(30px) scale(0.98);
  filter: blur(10px);
  animation: fadeSlideBlur 0.8s ease forwards;
}

.delay-1 { animation-delay: 0.2s }
.delay-2 { animation-delay: 0.4s }
.delay-3 { animation-delay: 0.6s }

@keyframes fadeSlideBlur {
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: blur(0);
  }
}

/* ✍️ typing */
.typed-text {
  color: #6366f1;
  font-weight: 600;
}

.cursor {
  animation: blink 1s infinite;
}

@keyframes blink {
  50% { opacity: 0 }
}

/* 💻 MOCKUP */
.mockup {
  perspective: 1200px;
}

.mockup-screen {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 40px 80px rgba(0,0,0,0.25);
}

/* 🎥 VIDEO */
.mockup-video {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 20px;
}

/* 🚀 CTA */
.cta-btn:hover {
  transform: translateY(-3px) scale(1.04);
  box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}

/* 🌙 DARK */
body.dark .hero-bg {
  background: linear-gradient(120deg, #020617, #0f172a);
}
</style>