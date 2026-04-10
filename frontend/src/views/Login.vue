<template>
  <section class="login-page d-flex align-items-center justify-content-center">

    <!-- 🌈 BACKGROUND -->
    <div class="login-bg"></div>
    <div class="login-glow"></div>

    <div class="back-link" @click="$router.push('/')">
      <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Tornar
    </div>

    <!-- CARD -->
    <div class="login-card">

      <!-- LOGO -->
      <div class="text-center mb-4">
        <img :src="logo" alt="QuizForge" class="logo mb-3" />
        <h4 class="fw-bold">QuizForge</h4>
        <p class="text-muted small">Accedeix al teu espai docent</p>
      </div>

      <!-- FORM -->
      <form @submit.prevent="login">

        <!-- EMAIL -->
        <div class="form-floating mb-3">
          <input
            v-model="email"
            type="email"
            class="form-control"
            id="email"
            placeholder="Email"
          />
          <label for="email">Email</label>
        </div>

        <!-- PASSWORD -->
        <div class="form-floating mb-3">
          <input
            v-model="password"
            type="password"
            class="form-control"
            id="password"
            placeholder="Contraseña"
          />
          <label for="password">Contraseña</label>
        </div>

        <!-- ERROR -->
        <div v-if="error" class="alert alert-danger py-2 small">
          {{ error }}
        </div>

        <!-- BUTTON -->
        <button class="btn btn-dark w-100 login-btn">
          Entrar
        </button>

        <!-- LINKS -->
        <p class="text-center mt-3 small">
          No tens compte?
          <router-link to="/register" class="link-primary fw-semibold">
            Registrar-se
          </router-link>
        </p>

      </form>

    </div>

  </section>
</template>

<script setup>
import { ref } from 'vue'
import authService from '../services/authService'
import { useRouter } from 'vue-router'
import logo from '@/assets/img/logo.png'

const email = ref('')
const password = ref('')
const error = ref('')

const router = useRouter()

const login = async () => {
  try {
    const res = await authService.login(email.value, password.value)
    authService.saveToken(res.data.token)
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  }
}
</script>

<style scoped>
.back-link {
  position: absolute;
  top: 30px;
  left: 30px;
  z-index: 3;

  font-size: 0.9rem;
  color: #64748b;
  cursor: pointer;

  transition: all 0.2s ease;
}

.back-link:hover {
  color: #0f172a;
  transform: translateX(-3px);
}

/* 🌌 PAGE */
.login-page {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 100px;
}

@media (max-width: 768px) {
  .login-page {
    padding-top: 60px;
  }
}

/* 🌈 BACKGROUND */
.login-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(120deg, #f8fafc, #eef2ff, #f0f9ff);
  background-size: 200% 200%;
  animation: gradientMove 12s ease infinite;
  z-index: 0;
}

@keyframes gradientMove {
  0% { background-position: 0% 50% }
  50% { background-position: 100% 50% }
  100% { background-position: 0% 50% }
}

/* 💡 GLOW */
.login-glow {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 30% 30%, rgba(99,102,241,0.15), transparent 40%);
  z-index: 1;
}

/* 🧾 CARD */
.login-card {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 420px;
  padding: 2rem;
  border-radius: 20px;

  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(12px);

  box-shadow: 0 20px 50px rgba(0,0,0,0.1);
  border: 1px solid rgba(255,255,255,0.4);

  animation: fadeUp 0.6s ease;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* 🧠 LOGO */
.logo {
  width: 120px;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

/* ✨ INPUTS */
.form-control {
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  transition: all 0.2s ease;
}

.form-control:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
}

/* 🚀 BUTTON */
.login-btn {
  border-radius: 12px;
  padding: 0.7rem;
  transition: all 0.2s ease;
}

.login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

/* 🔗 LINKS */
a {
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

</style>