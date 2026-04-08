<template>
  <section id="finalcta" class="py-5 text-center final-cta reveal">
    <div class="container">

      <h2 class="fw-bold mb-3 animate-item">
        {{ t('cta.title') }}
      </h2>

      <p class="mb-4 text-muted animate-item delay-1">
        {{ t('cta.sub') }}
      </p>

      <button class="btn btn-cta animate-item delay-2">
        {{ t('cta.btn') }}
      </button>

    </div>
  </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'

const { t } = useI18n()
useReveal()
</script>

<style scoped>
/* 🎯 Fons net */
.final-cta {
  background: #ffffff;
  border-top: 1px solid #e2e8f0;
}

/* Botó principal amb identitat corporativa */
.btn-cta {
  background: linear-gradient(90deg, #2563eb, grey);
  border: none;
  color: white;
  font-weight: 600;
  padding: 12px 28px;
  border-radius: 999px;
  transition: all 0.3s ease;
}

/* Hover */
.btn-cta:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  opacity: 0.95;
}

/* Dark mode */
body.dark .final-cta {
  background: #0f172a;
  border-top: 1px solid #1e293b;
}
</style>