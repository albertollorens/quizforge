<template>
  <section id="pricing" class="py-5 reveal" style="background: #f8f9fa;">
    <div class="container">

      <div class="text-center">
        <h2 class="fw-bold mb-3">{{ t('price.title') }}</h2>
        <p class="text-muted mb-5">{{ t('price.sub') }}</p>
      </div>

      <div class="row g-4 justify-content-center">
        <div class="col-md-4" v-for="plan in plans" :key="plan.name">
          
          <div class="card p-4 h-100 d-flex flex-column pricing-card"
               :class="plan.highlight ? 'highlight' : plan.variant">

            <!-- Badge popular -->
            <div v-if="plan.highlight" class="badge-popular">
              {{ t('price.p2_popular') }}
            </div>

            <h4 class="fw-semibold">{{ t(plan.name) }}</h4>
            <h2 class="my-3 fw-bold">{{ plan.price }}<span class="text-sm text-gray-500" data-i18n="p2_period">{{plan.month}}</span></h2>

            <ul class="space-y-4 mb-8 text-sm text-gray-600">
              <li class="flex items-center gap-3" v-for="(f,i) in plan.features" :key="i">
                <iconify-icon icon="solar:check-circle-linear" class="text-gray-900"></iconify-icon> {{ t(f) }}
              </li>
            </ul>

            <button class="btn w-100 mt-auto"
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
    price: t('price.p1'),
    btn: 'price.p1_btn',
    features: ['price.p1_f1', 'price.p1_f2', 'price.p1_f3', 'price.p1_f4'],
    variant: 'plan-free'
  },
  {
    name: 'price.p2_name',
    price: '9€',
    month: "/" + t('price.p2_month'),
    btn: 'price.p2_btn',
    features: ['price.p2_f1', 'price.p2_f2', 'price.p2_f3', 'price.p2_f4'],
    highlight: true
  },
  {
    name: 'price.p3_name',
    price: '49€',
    month: "/" + t('price.p2_month'),
    btn: 'price.p3_btn',
    features: ['price.p3_f1', 'price.p3_f2', 'price.p3_f3', 'price.p3_f4'],
    variant: 'plan-business'
  }
]
</script>

<style scoped>
ul li {
  list-style-type: none;
}

/* Cards més amples i flex-column per botons */
.pricing-card {
  min-width: 340px; 
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
  background: white;
  display: flex;
  flex-direction: column;
}

/* Hover efecte */
.pricing-card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 25px 60px rgba(0,0,0,0.12);
}

/* Plans */
.plan-free { border-top: 4px solid #3b82f6; }
.plan-business { border-top: 4px solid #f97316; }

/* Destacat */
.highlight {
  border: none;
  background: linear-gradient(135deg, #2563eb, #f97316);
  color: white;
  transform: scale(1.05);
}

.highlight ul li {
  color: rgba(255,255,255,0.9);
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

/* Botons */
.btn-gradient {
  background: linear-gradient(90deg, #2563eb, #f97316);
  border: none;
  color: white;
  font-weight: 600;
}

.btn-gradient:hover { opacity: 0.9; }

.pricing-card ul { flex-grow: 1; }
.pricing-card button { margin-top: auto; }

/* 🌙 DARK MODE */
body.dark #pricing { background: #010419; color: #e2e8f0; }
body.dark .pricing-card { background: #0f172a; border: 1px solid #1e293b; color: #e2e8f0; }
body.dark .pricing-card:hover { box-shadow: 0 25px 60px rgba(0,0,0,0.5); }
body.dark .plan-free { border-top: 4px solid #3b82f6; }
body.dark .plan-business { border-top: 4px solid #f97316; }
body.dark .highlight { background: linear-gradient(135deg, #2563eb, #f97316); color: white; border: none; }
body.dark .highlight ul li { color: rgba(255,255,255,0.9); }
body.dark .badge-popular { background: #1e293b; color: #3b82f6; border: 1px solid #3b82f6; }
body.dark .btn-gradient { background: linear-gradient(90deg, #2563eb, #f97316); color: white; border: none; }
body.dark .btn-outline-dark { border-color: #1e293b; color: #e2e8f0; }
body.dark .btn-outline-dark:hover { background: #1e293b; color: #ffffff; }
</style>