<template>
  <section id="pricing" class="py-5 bg-light">
    <div class="container text-center">

      <h2 class="mb-4">{{ t('price.title') }}</h2>
      <p class="text-muted mb-5">{{ t('price.sub') }}</p>

      <div class="row">
        <div class="col-md-4" v-for="plan in plans" :key="plan.name">
          <div class="card p-4 h-100 shadow-sm pricing-card">

            <h4>{{ t(plan.name) }}</h4>
            <h2 class="my-3">{{ plan.price }}</h2>

            <ul class="text-start mb-3">
              <li v-for="(f,i) in plan.features" :key="i">
                {{ t(f) }}
              </li>
            </ul>

            <button class="btn btn-dark">
              {{ t(plan.btn) }}
            </button>

          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const plans = [
  {
    name: 'price.p1_name',
    price: '0€',
    btn: 'price.p1_btn',
    features: ['price.p1_f1', 'price.p1_f2']
  },
  {
    name: 'price.p2_name',
    price: '9€/mes',
    btn: 'price.p2_btn',
    features: ['price.p2_f1', 'price.p2_f2']
  },
  {
    name: 'price.p3_name',
    price: '49€/mes',
    btn: 'price.p3_btn',
    features: ['price.p3_f1', 'price.p3_f2']
  }
]
</script>

<style scoped>
.pricing-card:hover {
  transform: scale(1.05);
  transition: 0.3s;
}
</style>