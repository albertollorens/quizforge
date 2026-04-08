<template>
  <section id="success" class="py-5 reveal success-section">
    <div class="container text-center">

      <h2 class="fw-bold mb-3 animate-item">{{ t('success.title') }}</h2>
      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub1') }}</p>

      <!-- 👨‍🏫 PROFESSORS -->
      <div class="horizontal-scroll animate-item delay-2">
        <div 
          class="scroll-item"
          v-for="caseItem in successCases" 
          :key="caseItem.name"
        >
          <div class="success-card">

            <h5 class="fw-semibold">{{ caseItem.name }}</h5>
            <p class="text-muted small"><i>"{{ caseItem.description }}"</i></p>

          </div>
        </div>
      </div>

      <br/><br/>

      <p class="text-muted mb-5 animate-item delay-1">{{ t('success.sub2') }}</p>

      <!-- 🏫 CENTRES -->
      <div class="horizontal-scroll animate-item delay-2">
        <div 
          class="scroll-item"
          v-for="caseItem in successIES" 
          :key="caseItem.name"
        >
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
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'

// Aquí importaràs les imatges dels centres
import centre1 from '@/assets/img/iessimarro.jpg'
import centre2 from '@/assets/img/aulaxativa.png'
import centre3 from '@/assets/img/iesjribera.jpg'
import centre4 from '@/assets/img/iesporçons.jpg'

useReveal()

const { t } = useI18n()

const successIES = [
  {
    name: 'IES Lluís Simarro',
    description: t('success.case1'),
    img: centre1
  },
  {
    name: 'Aula Xàtiva Formación',
    description: t('success.case2'),
    img: centre2
  },
  {
    name: 'IES Josep de Ribera',
    description: t('success.case3'),
    img: centre3
  },
  {
    name: 'IES Porçons (Aielo)',
    description: t('success.case4'),
    img: centre4
  }
]

const successCases = [
  {
    name: 'Joan Piera Climent',
    description: t('success.prof1'),
  },
  {
    name: 'Maria Llopis Serra',
    description: t('success.prof2'),
  },
  {
    name: 'Pere Mateu Saez',
    description: t('success.prof3'),
  },
  {
    name: 'Laura Martí Gisbert',
    description: t('success.prof4'),
  }
]
</script>

<style scoped>
/* 🧭 scroll horitzontal */
.horizontal-scroll {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  padding-bottom: 10px;

  scroll-snap-type: x mandatory;
  scrollbar-width: none; /* Firefox */
}

.horizontal-scroll::-webkit-scrollbar {
  display: none; /* Chrome */
}

/* 📦 item */
.scroll-item {
  min-width: 260px;
  flex: 0 0 auto;
  scroll-snap-align: start;
}

/* 🎴 card */
.success-card {
  background: white;
  padding: 20px;
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
  height: 100%;
  position: relative;
  overflow: hidden;
}

/* glow */
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

/* 🖼️ imatge */
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

/* ✨ animació entrada */
.animate-item {
  opacity: 0;
  transform: translateY(30px) scale(0.98);
  filter: blur(10px);
  animation: fadeSlideBlur 0.8s ease forwards;
}

.delay-1 { animation-delay: 0.2s }
.delay-2 { animation-delay: 0.4s }

@keyframes fadeSlideBlur {
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: blur(0);
  }
}

/* 🌙 dark */
body.dark .success-section {
  background: linear-gradient(180deg, #020617 0%, #0f172a 100%);
}

body.dark .success-card {
  background: #0f172a;
  border-color: #1e293b;
}
</style>