<template>
  <section class="login-page d-flex align-items-center justify-content-center">

    <!-- 🌈 BACKGROUND -->
    <div class="login-bg"></div>
    <div class="login-glow"></div>

    <!-- 🔙 BACK -->
    <div class="back-link" @click="$router.push('/')">
      <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Tornar
    </div>

    <!-- 🧾 CARD -->
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
          <input v-model="email" type="email" class="form-control" id="email" placeholder="Email" />
          <label for="email">Email</label>
        </div>

        <!-- PASSWORD -->
        <div class="form-floating mb-3">
          <input v-model="password" type="password" class="form-control" id="password" placeholder="Contraseña" />
          <label for="password">Contraseña</label>
        </div>

        <!-- ERROR -->
        <div v-if="error" class="alert alert-danger py-2 small">
          {{ error }}
        </div>

        <!-- LOGIN BUTTON -->
        <button class="btn btn-dark w-100 login-btn">
          Entrar
        </button>

        <!-- SEPARATOR -->
        <div class="separator my-3">
          <span>o continua amb</span>
        </div>

       <div class="social-login">

        <!-- GOOGLE CUSTOM -->
        <button type="button" class="btn social-btn google w-100 mb-2" @click="loginWithGoogle">
          <iconify-icon icon="logos:google-icon" width="18"></iconify-icon>
          Continuar amb Google
        </button>

        <button type="button" class="btn social-btn microsoft w-100 mb-2">
          <iconify-icon icon="logos:microsoft-icon" width="18"></iconify-icon>
          Continuar amb Microsoft
        </button>

        <button type="button" class="btn social-btn linkedin w-100">
          <iconify-icon icon="logos:linkedin-icon" width="18"></iconify-icon>
          Continuar amb LinkedIn
        </button>

      </div>

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
import { ref, onMounted } from 'vue'
import authService from '../services/authService'
import { useRouter } from 'vue-router'
import logo from '@/assets/img/logo.png'

const email = ref('')
const password = ref('')
const error = ref('')

const router = useRouter()

let googleClient = null

/* LOGIN NORMAL */
const login = async () => {
  try {
    const res = await authService.login(email.value, password.value)
    authService.saveToken(res.data.token)
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  }
}

/* GOOGLE INIT */
/*onMounted(() => {
  window.google.accounts.id.initialize({
    client_id: '422845366189-pb88uq9qqhd6d7n5icv08vemhc03raql.apps.googleusercontent.com',
    callback: handleCredentialResponse
  })

  window.google.accounts.id.renderButton(
    document.getElementById('google-btn'),
    {
      theme: 'outline',
      size: 'large',
      width: 320
    }
  )

  window.google.accounts.id.prompt()
});*/

/* 👉 CLICK EN TU BOTÓN */
/*function loginWithGoogle() {
  googleClient.prompt() // lanza el selector de cuentas
}*/

/* CALLBACK */
/*async function handleCredentialResponse(response) {
  try {
    const res = await authService.loginWithGoogle(response.credential)
    authService.saveToken(res.data.token)
    router.push('/dashboard')
  } catch (err) {
    error.value = 'Error amb Google Login'
  }
}*/
</script>

<style scoped>
/* BACK */
.back-link {
  position: absolute;
  top: 30px;
  left: 30px;
  z-index: 3;
  font-size: 0.9rem;
  color: #64748b;
  cursor: pointer;
}
.back-link:hover { transform: translateX(-3px); }

/* PAGE */
.login-page {
  align-items: flex-start;
  padding-top: 100px;
}

/* BG */
.login-bg {
  position: absolute;
  inset: 0;
  background: linear-gradient(120deg, #f8fafc, #eef2ff, #f0f9ff);
  animation: gradientMove 12s ease infinite;
}
@keyframes gradientMove {
  0% { background-position: 0% }
  50% { background-position: 100% }
  100% { background-position: 0% }
}

/* CARD */
.login-card {
  max-width: 420px;
  padding: 2rem;
  border-radius: 20px;
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(12px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

/* SOCIAL */
.social-btn {
  display: flex;
  justify-content: center;
  gap: 10px;
  border-radius: 12px;
  padding: 0.6rem;
  border: 1px solid #e5e7eb;
}
.social-btn:hover {
  transform: translateY(-2px);
}

/* SEPARATOR */
.separator {
  display: flex;
  align-items: center;
  font-size: 0.8rem;
}
.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #e5e7eb;
}
.separator span { padding: 0 10px; }
</style>