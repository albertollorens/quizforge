<template>
  <section id="contact" class="py-5 reveal contact-section">
    <div class="container">

      <!-- Títol -->
      <div class="text-center mb-5">
        <h2 class="fw-bold animate-item">{{ t('contact.title') }}</h2>
        <p class="text-muted animate-item delay-1">{{ t('contact.sub') }}</p>
      </div>

      <div class="row justify-content-center">
        
        <!-- Formuari -->
        <div class="col-lg-6 animate-item delay-2">
          <form @submit.prevent="sendMessage" class="contact-form p-4 rounded">

            <div class="mb-3">
              <label for="name" class="form-label">{{ t('contact.name') }}</label>
              <input type="text" id="name" v-model="form.name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">{{ t('contact.email') }}</label>
              <input type="email" id="email" v-model="form.email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="message" class="form-label">{{ t('contact.message') }}</label>
              <textarea id="message" v-model="form.message" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-dark w-100 cta-btn">
              {{ t('contact.send') }}
            </button>

            <p v-if="status" class="mt-3 text-center" :class="statusClass">{{ status }}</p>

          </form>
        </div>

        <!-- Info de contacte o imatge -->
        <div class="col-lg-4 animate-item delay-3 d-none d-lg-flex align-items-center justify-content-center">
          <!-- Placeholder per icona, mapa o imatge -->
          <div class="contact-placeholder">
            📍
          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useReveal } from '@/composables/useReveal'

useReveal()

const { t } = useI18n()

const form = ref({
  name: '',
  email: '',
  message: ''
})

const status = ref('')
const statusClass = ref('text-success')

function sendMessage() {
  if (!form.value.name || !form.value.email || !form.value.message) return

  status.value = t('contact.sending')
  statusClass.value = 'text-primary'

  setTimeout(() => {
    status.value = t('contact.sent')
    statusClass.value = 'text-success'
    form.value.name = ''
    form.value.email = ''
    form.value.message = ''
  }, 1500)
}
</script>

<style scoped>
/* 🌈 Fons diferenciat */
.contact-section {
  background: linear-gradient(180deg, #eef2ff 0%, #f1f5f9 100%);
}

/* 🌙 Dark mode */
body.dark .contact-section {
  background: linear-gradient(180deg, #020617 0%, #0f172a 100%);
}

/* Form */
.contact-form {
  background: white;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.contact-form:hover {
  transform: translateY(-4px) scale(1.01);
  box-shadow: 0 25px 60px rgba(0,0,0,0.1);
}

/* Placeholder info */
.contact-placeholder {
  font-size: 3rem;
  color: #3b82f6;
  padding: 20px;
  border-radius: 16px;
  background: rgba(59,130,246,0.1);
}

/* Animació d'entrada */
.animate-item {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeSlide 0.8s ease forwards;
}

.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }

@keyframes fadeSlide {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Botó */
.cta-btn {
  transition: all 0.3s ease;
}
.cta-btn:hover {
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Dark mode form */
body.dark .contact-form {
  background: #0f172a;
  border: 1px solid #1e293b;
}
body.dark .contact-placeholder {
  background: rgba(99,102,241,0.15);
  color: #818cf8;
}
</style>