<template>
  <section id="success" class="py-5 reveal success-section">
    <div class="container text-center">

      <h2 class="fw-bold mb-3 animate-item">{{ t('success.title') }}</h2>
      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub1') }}</p>

      <div class="row g-4">
        <div 
          class="col-md-4 col-sm-6"
          v-for="caseItem in successCases" 
          :key="caseItem.name"
        >
          <div class="success-card h-100 animate-item delay-2">          

            <h5 class="fw-semibold">{{ caseItem.name }}</h5>
            <p class="text-muted small"><i>"{{ caseItem.description }}"</i></p>

          </div>
        </div>
      </div>

      <br/>

      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub2') }}</p>

      <div class="row g-4">
        <div 
          class="col-md-4 col-sm-6"
          v-for="caseItem in successIES" 
          :key="caseItem.name"
        >
          <div class="success-card h-100 animate-item delay-2">

            <!-- 🖼️ IMATGE DEL CENTRE -->
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
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'

// Aquí importaràs les imatges dels centres
import centre1 from '@/assets/img/iessimarro.jpg'
import centre2 from '@/assets/img/iesjribera.jpg'
import centre3 from '@/assets/img/iesporçons.jpg'

import prof1 from '@/assets/img/prof1.png'
import prof2 from '@/assets/img/prof2.png'
import prof3 from '@/assets/img/prof3.avif'

useReveal()

const { t } = useI18n()

const successIES = [
  {
    name: 'IES Lluís Simarro (Xàtiva)',
    description: t('success.case1'),
    img: centre1
  },
  {
    name: 'IES Josep de Ribera (Xàtiva)',
    description: t('success.case2'),
    img: centre2
  },
  {
    name: 'IES Porçons (Aielo)',
    description: t('success.case3'),
    img: centre3
  }
]

const successCases = [
  {
    name: 'Josep Piera Langa',
    description: t('success.prof1'),
    img: prof1
  },
  {
    name: 'Joan Mateu Saez',
    description: t('success.prof2'),
    img: prof2
  },
  {
    name: 'Maria Llopis Serra',
    description: t('success.prof3'),
    img: prof3
  }
]
</script>

<style scoped>
.success-section {
  background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
}

/* 🌙 dark mode */
body.dark .success-section {
  background: linear-gradient(180deg, #020617 0%, #0f172a 100%);
}

.success-card {
  background: white;
  padding: 20px;
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
  overflow: hidden;
  position: relative;
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
  transform: translateY(-8px) scale(1.02);
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

.success-card h5 {
  margin-bottom: 8px;
}

.success-card p {
  font-size: 0.9rem;
}

/* ✨ animació d’entrada */
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

/* 🌙 dark mode card */
body.dark .success-card {
  background: #0f172a;
  border-color: #1e293b;
}
</style>