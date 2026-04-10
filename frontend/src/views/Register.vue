<template>
  <section class="register-page d-flex align-items-center justify-content-center">

    <!-- 🌈 BACKGROUND -->
    <div class="register-bg"></div>
    <div class="register-glow"></div>

    <div class="back-link" @click="$router.push('/')">
      <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Tornar
    </div>

    <!-- CARD -->
    <div class="register-card">

      <!-- LOGO -->
      <div class="text-center mb-4">
        <img :src="logo" alt="QuizForge" class="logo mb-3" />
        <h4 class="fw-bold">QuizForge</h4>
        <p class="text-muted small">Crea el teu compte en segons</p>
      </div>

      <!-- FORM -->
      <form @submit.prevent="register">

        <!-- USERNAME -->
        <div class="form-floating mb-3">
          <input
            v-model="username"
            type="text"
            class="form-control"
            id="username"
            placeholder="Usuari"
            required
          />
          <label for="username">Usuari</label>
        </div>

        <!-- EMAIL -->
        <div class="form-floating mb-3">
          <input
            v-model="email"
            type="email"
            class="form-control"
            id="email"
            placeholder="Email"
            required
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
            required
          />
          <label for="password">Contraseña</label>
        </div>

        <!-- ERROR -->
        <div v-if="error" class="alert alert-danger py-2 small">
          {{ error }}
        </div>

        <!-- SUCCESS -->
        <div v-if="message" class="alert alert-success py-2 small">
          {{ message }}
        </div>

        <!-- SOCIAL LOGIN -->
        <div class="social-login">

          <button class="btn social-btn google w-100 mb-2" @click="loginSocial('google')">
            <iconify-icon icon="logos:google-icon" width="18"></iconify-icon>
            Continuar amb Google
          </button>

          <button class="btn social-btn microsoft w-100 mb-2" @click="loginSocial('microsoft')">
            <iconify-icon icon="logos:microsoft-icon" width="18"></iconify-icon>
            Continuar amb Microsoft
          </button>

          <button class="btn social-btn linkedin w-100" @click="loginSocial('linkedin')">
            <iconify-icon icon="logos:linkedin-icon" width="18"></iconify-icon>
            Continuar amb LinkedIn
          </button>

        </div>

        <!-- SEPARATOR -->
        <div class="separator my-3">
          <span>o</span>
        </div>

        <!-- BUTTON -->
        <button class="btn btn-dark w-100 register-btn">
          Crear compte
        </button>

        <!-- LINKS -->
        <p class="text-center mt-3 small">
          Ja tens compte?
          <router-link to="/login" class="link-primary fw-semibold">
            Iniciar sessió
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

const username = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const message = ref('')

const router = useRouter()

const register = async () => {
  try {
    const res = await authService.register(
      username.value,
      email.value,
      password.value
    )

    authService.saveToken(res.data.token)
    message.value = res.message
    error.value = ''

    // Redirect amb lleuger delay (UX PRO)
    setTimeout(() => {
      router.push('/dashboard')
    }, 800)

  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  }
}

function loginSocial(provider) {
  authService.loginSocial(provider)
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
.register-page {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 100px;
}

@media (max-width: 768px) {
  .register-page {
    padding-top: 60px;
  }
}

/* 🌈 BACKGROUND */
.register-bg {
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
.register-glow {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 70% 40%, rgba(249,115,22,0.15), transparent 40%);
  z-index: 1;
}

/* 🧾 CARD */
.register-card {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 420px;
  padding: 2rem;
  border-radius: 20px;

  background: rgba(255,255,255,0.85);
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

/* 🔗 SOCIAL LOGIN */
.social-login {
  display: flex;
  flex-direction: column;
}

/* BOTONS BASE */
.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;

  border-radius: 12px;
  padding: 0.6rem;
  font-size: 0.9rem;
  font-weight: 500;

  background: white;
  border: 1px solid #e5e7eb;

  transition: all 0.2s ease;
}

/* HOVER */
.social-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

/* COLORS PER MARCA */
.social-btn.google:hover {
  border-color: #ea4335;
}

.social-btn.microsoft:hover {
  border-color: #2563eb;
}

.social-btn.linkedin:hover {
  border-color: #0a66c2;
}

/* SEPARADOR */
.separator {
  display: flex;
  align-items: center;
  text-align: center;
  color: #9ca3af;
  font-size: 0.8rem;
}

.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #e5e7eb;
}

.separator span {
  padding: 0 10px;
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
  border-color: #f97316;
  box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
}

/* 🚀 BUTTON */
.register-btn {
  border-radius: 12px;
  padding: 0.7rem;
  transition: all 0.2s ease;
}

.register-btn:hover {
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