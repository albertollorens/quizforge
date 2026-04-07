<template>
  <section id="finalcta" class="py-5 text-center bg-dark text-white">
    <div class="container">

      <h2>{{ t('cta.title') }}</h2>
      <p class="mb-4">{{ t('cta.sub') }}</p>

      <button class="btn btn-light">
        {{ t('cta.btn') }}
      </button>

    </div>
  </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>