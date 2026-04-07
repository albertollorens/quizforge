<template>
  <section id="hero" class="reveal hero py-5 bg-light fade-in">
    <div class="container">
      <div class="row align-items-center">

        <!-- 🧠 TEXT -->
        <div class="col-lg-6 text-center text-lg-start">

          <span class="badge bg-primary mb-3">
            {{ t('hero.badge') }}
          </span>

          <h1 class="display-5 fw-bold mb-3">
            {{ t('hero.title') }}
            <br>
            <span class="typed-text">{{ displayedText }}</span>
            <span class="cursor">|</span>
          </h1>

          <p class="lead text-muted mb-4">
            {{ t('hero.subtitle') }}
          </p>

          <!-- 🚀 CTA -->
          <div class="d-flex justify-content-center justify-content-lg-start gap-3">

            <button class="btn btn-dark btn-lg cta-btn" @click="goToRegister">
              {{ t('hero.cta1') }}
            </button>

            <button class="btn btn-outline-dark btn-lg cta-secondary" @click="scrollToDemo">
              {{ t('hero.cta2') }}
            </button>

          </div>

        </div>

        <!-- 💻 MOCKUP -->
        <div class="col-lg-6 mt-5 mt-lg-0">

          <div 
            class="mockup"
            @mousemove="handleMouseMove"
            @mouseleave="resetTilt"
            :style="mockupStyle"
          >
            <div class="mockup-screen">
              <div class="mockup-header"></div>
              <div class="mockup-content">
                <p class="fake-line w-75"></p>
                <p class="fake-line w-50"></p>
                <p class="fake-line w-100"></p>
                <p class="fake-line w-60"></p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'
import { useRouter } from 'vue-router'

const router = useRouter()

function goToRegister() {
  router.push('/register')
}

function scrollToDemo() {
  const el = document.getElementById('demo')
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
  }
}

useReveal()

const { t } = useI18n()

/* ✍️ TEXTOS DINÀMICS (també internacionalitzats si vols) */
const texts = t('hero.typing')

const displayedText = ref('')
let textIndex = 0
let charIndex = 0

function type() {
  if (charIndex < texts[textIndex].length) {
    displayedText.value += texts[textIndex][charIndex]
    charIndex++
    setTimeout(type, 50)
  } else {
    setTimeout(erase, 1500)
  }
}

function erase() {
  if (charIndex > 0) {
    displayedText.value = texts[textIndex].substring(0, charIndex - 1)
    charIndex--
    setTimeout(erase, 30)
  } else {
    textIndex = (textIndex + 1) % texts.length
    setTimeout(type, 500)
  }
}

onMounted(type)

/* 🧲 MOCKUP INTERACTIU */
const mockupStyle = ref({})

function handleMouseMove(e) {
  const rect = e.currentTarget.getBoundingClientRect()
  const x = e.clientX - rect.left
  const y = e.clientY - rect.top

  const rotateY = ((x - rect.width / 2) / rect.width) * 10
  const rotateX = -((y - rect.height / 2) / rect.height) * 10

  mockupStyle.value = {
    transform: `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`
  }
}

function resetTilt() {
  mockupStyle.value = {
    transform: 'rotateX(0deg) rotateY(0deg)'
  }
}
</script>

<style scoped>
.hero {
  min-height: 80vh;
  display: flex;
  align-items: center;
}

/* ✍️ Typing */
.typed-text {
  color: #6366f1;
}

.cursor {
  animation: blink 1s infinite;
}

@keyframes blink {
  50% { opacity: 0; }
}

/* 🚀 CTA PRO */
.cta-btn {
  transition: all 0.3s ease;
}

.cta-btn:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.cta-secondary:hover {
  transform: translateY(-2px);
}

/* 💻 MOCKUP */
.mockup {
  perspective: 1000px;
}

.mockup-screen {
  background: #111;
  border-radius: 20px;
  padding: 20px;
  transition: transform 0.2s ease;
}

.mockup-header {
  height: 10px;
  background: #333;
  border-radius: 10px;
  margin-bottom: 15px;
}

.fake-line {
  height: 10px;
  background: #444;
  border-radius: 5px;
  margin-bottom: 10px;
}

/* 🌙 DARK MODE */
body.dark .hero {
  background: #0f172a !important;
}

body.dark .typed-text {
  color: #818cf8;
}

body.dark .mockup-screen {
  background: #020617;
}

body.dark .fake-line {
  background: #1e293b;
}
</style>