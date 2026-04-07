<template>
  <section id="features" class="py-5 reveal">
    <div class="container text-center">

      <h2 class="mb-4">{{ t('feat.title') }}</h2>
      <p class="text-muted mb-5">{{ t('feat.sub') }}</p>

      <div class="row">
        <div class="col-md-3 mb-4" v-for="f in features" :key="f.title">
          <div class="p-3 border rounded h-100 hover-card">
            <h5>{{ t(f.title) }}</h5>
            <p class="text-muted">{{ t(f.desc) }}</p>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'

useReveal()

const { t } = useI18n()

const features = [
  { title: 'feat.f1_title', desc: 'feat.f1_desc' },
  { title: 'feat.f2_title', desc: 'feat.f2_desc' },
  { title: 'feat.f3_title', desc: 'feat.f3_desc' },
  { title: 'feat.f4_title', desc: 'feat.f4_desc' }
]
</script>

<style scoped>
.hover-card:hover {
  transform: translateY(-5px);
  transition: 0.3s;
}
</style>