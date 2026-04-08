<template>
  <section id="pricing" class="py-5 bg-light reveal">
    <div class="container text-center">

      <h2 class="fw-bold mb-3">{{ t('price.title') }}</h2>
      <p class="text-muted mb-5">{{ t('price.sub') }}</p>

      <div class="row g-4">
        <div class="col-md-4" v-for="plan in plans" :key="plan.name">
          
          <div class="card p-4 h-100 pricing-card"
               :class="plan.highlight ? 'highlight' : plan.variant">

            <!-- Badge popular -->
            <div v-if="plan.highlight" class="badge-popular">
              {{ t('price.p2_popular') }}
            </div>

            <h4 class="fw-semibold">{{ t(plan.name) }}</h4>
            <h2 class="my-3 fw-bold">{{ plan.price }}</h2>

            <ul class="text-start mb-4">
              <li v-for="(f,i) in plan.features" :key="i">
                ✔ {{ t(f) }}
              </li>
            </ul>

            <button class="btn w-100"
                    :class="plan.highlight ? 'btn-gradient' : 'btn-outline-dark'">
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
    features: ['price.p1_f1', 'price.p1_f2'],
    variant: 'plan-free'
  },
  {
    name: 'price.p2_name',
    price: '9€/mes',
    btn: 'price.p2_btn',
    features: ['price.p2_f1', 'price.p2_f2'],
    highlight: true
  },
  {
    name: 'price.p3_name',
    price: '49€/mes',
    btn: 'price.p3_btn',
    features: ['price.p3_f1', 'price.p3_f2'],
    variant: 'plan-business'
  }
]
</script>

<style scoped>
ul li {
  list-style-type: none;
}
.pricing-card {
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
  background: white;
}

/* Hover effect */
.pricing-card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 25px 60px rgba(0,0,0,0.12);
}

/* 🔵 FREE */
.plan-free {
  border-top: 4px solid #3b82f6;
}

/* 🟣 PRO (destacat) */
.highlight {
  border: none;
  background: linear-gradient(135deg, #2563eb, #f97316);
  color: white;
  transform: scale(1.05);
}

.highlight ul li {
  color: rgba(255,255,255,0.9);
}

/* 🟠 BUSINESS */
.plan-business {
  border-top: 4px solid #f97316;
}

/* Badge */
.badge-popular {
  position: absolute;
  top: 15px;
  right: 15px;
  background: white;
  color: #2563eb;
  font-size: 0.75rem;
  padding: 5px 10px;
  border-radius: 20px;
  font-weight: 600;
}

/* Botó gradient */
.btn-gradient {
  background: linear-gradient(90deg, #2563eb, #f97316);
  border: none;
  color: white;
  font-weight: 600;
}

.btn-gradient:hover {
  opacity: 0.9;
}

/* Dark mode */
body.dark .pricing-card {
  background: #0f172a;
  border-color: #1e293b;
}
</style>