<template>
  <section id="features" class="py-5 reveal features-section">
    <div class="container text-center">

      <h2 class="fw-bold mb-3">{{ t('feat.title') }}</h2>
      <p class="text-muted mb-5">{{ t('feat.sub') }}</p>

      <!-- 🔥 5 columnes reals -->
      <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">

        <div 
          v-for="f in features" 
          :key="f.title"
          class="col d-flex"
        >
          <div class="feature-card w-100">

            <!-- 🖼️ ICON / IMAGE -->
            <div class="feature-media mb-3">
              <img 
                :src="f.img" 
                class="feature-img"
                :alt="t(f.title)"
              >
            </div>

            <h5 class="fw-semibold">{{ t(f.title) }}</h5>
            <p class="text-muted small">{{ t(f.desc) }}</p>

          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'
import openai from '@/assets/img/openai.png'
import moodleaules from '@/assets/img/moodleaules.png'
import xml from '@/assets/img/xml.png'
import questions from '@/assets/img/questions.png'
import timesave from '@/assets/img/timesave.png'

useReveal()

const { t } = useI18n()

const features = [
  { 
    title: 'feat.f1_title', 
    desc: 'feat.f1_desc',
    img: openai // 👉 afegir ruta després
  },
  { 
    title: 'feat.f2_title', 
    desc: 'feat.f2_desc',
    img: moodleaules
  },
  { 
    title: 'feat.f3_title', 
    desc: 'feat.f3_desc',
    img: xml
  },
  { 
    title: 'feat.f4_title', 
    desc: 'feat.f4_desc',
    img: questions
  },
  { 
    title: 'feat.f5_title', 
    desc: 'feat.f5_desc',
    img: timesave
  }
]
</script>

<style scoped>

/* 🌈 fons diferenciat */
.features-section {
  background: linear-gradient(180deg, #f1f5f9 0%, #ffffff 100%);
}

/* 🌙 dark mode */
body.dark .features-section {
  background: #020617;
}

/* 🎴 card */
.feature-card {
  background: white;
  padding: 22px;
  border-radius: 18px;
  height: 100%;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

/* glow subtil */
.feature-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top, rgba(59,130,246,0.15), transparent);
  opacity: 0;
  transition: 0.3s;
}

/* hover PRO */
.feature-card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.feature-card:hover::before {
  opacity: 1;
}

/* 🖼️ zona imatge */
.feature-media {
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* 🖼️ imatge correcta */
.feature-img {
  max-height: 45px;
  object-fit: contain;
  transition: all 0.3s ease;
  filter: grayscale(100%);
  opacity: 0.8;
}

/* ✨ microinteracció */
.feature-card:hover .feature-img {
  filter: grayscale(0%);
  opacity: 1;
  transform: scale(1.1);
}

/* text */
.feature-card h5 {
  margin-bottom: 8px;
  font-size: 1rem;
}

.feature-card p {
  font-size: 0.85rem;
}

/* 🌙 DARK MODE PREMIUM */
body.dark .features-section {
  background: linear-gradient(180deg, #020617 0%, #020617 100%);
  position: relative;
}

/* ✨ glow corporatiu subtil */
body.dark .features-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top left, rgba(37,99,235,0.15), transparent 60%),
              radial-gradient(circle at bottom right, rgba(249,115,22,0.15), transparent 60%);
  pointer-events: none;
}

/* 🎴 cards dark millorades */
body.dark .feature-card {
  background: #0f172a;
  border: 1px solid #1e293b;
  box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* ✨ hover més visible */
body.dark .feature-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 25px 60px rgba(0,0,0,0.6);
}

/* glow en hover */
body.dark .feature-card::before {
  background: radial-gradient(circle at top, rgba(59,130,246,0.25), transparent);
}

/* 🖼️ imatges en dark */
body.dark .feature-img {
  filter: grayscale(0%) brightness(0.9) contrast(1.1);
  opacity: 0.9;
}

/* hover imatge */
body.dark .feature-card:hover .feature-img {
  transform: scale(1.15);
  filter: brightness(1.1);
}

/* 📝 text contrast */
body.dark .feature-card h5 {
  color: #e2e8f0;
}

body.dark .feature-card p {
  color: #94a3b8;
}

</style>