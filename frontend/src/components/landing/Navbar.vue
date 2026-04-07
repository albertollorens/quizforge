<template>
  <nav class="navbar bg-white shadow-sm px-3 reveal">

    <strong>QuizForge</strong>

    <div class="d-flex gap-3 align-items-center">

      <a href="#features">{{ t('navbar.features') }}</a>
      <a href="#pricing">{{ t('navbar.pricing') }}</a>

      <select v-model="locale" @change="saveLang" class="form-select form-select-sm w-auto">
        <option value="va">VAL</option>
        <option value="es">CAS</option>
        <option value="en">ENG</option>
      </select>

      <button class="btn btn-dark btn-sm">
        {{ t('navbar.cta') }}
      </button>

      <button class="btn btn-sm btn-outline-dark" @click="toggle">
        <i class="bi" :class="isDark ? 'bi-sun' : 'bi-moon'"></i>
      </button>

      <a @click.prevent="scrollTo('features')">Features</a>
    </div>
  </nav>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { useDarkMode } from '@/composables/useDarkMode'
import { useScrollSpy } from '@/composables/useScrollSpy'

const { activeSection } = useScrollSpy([
  'hero',
  'demo',
  'features',
  'pricing'
])
const { t, locale } = useI18n()
const { isDark, toggle } = useDarkMode()

function saveLang() {
  localStorage.setItem('lang', locale.value)
}

function scrollTo(id) {
  const el = document.getElementById(id)
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}
</script>

<style scope>
.nav-link.active {
  color: #6366f1 !important;
  font-weight: bold;
}
</style>