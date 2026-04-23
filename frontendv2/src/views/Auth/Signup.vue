<template>
  <FullScreenLayout>
    <div class="relative p-6 bg-slate-50 z-1 font-sans dark:bg-slate-950 sm:p-0">
      <div class="relative flex flex-col justify-center w-full h-screen lg:flex-row">

        <div class="flex flex-col flex-1 w-full lg:w-1/2">

          <!-- Back link -->
          <div class="w-full max-w-xl pt-10 mx-auto">
            <router-link
              to="/"
              class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
            >
              <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 20 20" fill="none">
                <path
                  d="M12.7083 5L7.5 10.2083L12.7083 15.4167"
                  stroke=""
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              Tornar
            </router-link>
          </div>

          <!-- Form -->
          <div class="flex flex-col justify-center flex-1 w-full max-w-xl mx-auto px-4 py-10 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl dark:border-slate-700 dark:bg-slate-950">

              <!-- Title -->
              <div class="mb-5 sm:mb-8">
                <h1 class="mb-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                  Registra't
                </h1>
              </div>

              <!-- OAuth buttons -->
              <div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5">

                  <!-- GOOGLE AUTH FIXED -->
                  <button
                    @click="handleGoogleRegister"
                    :disabled="googleLoading"
                    class="inline-flex items-center justify-center gap-3 rounded-3xl bg-slate-100 px-7 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-200 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-slate-900/70 dark:text-slate-100 dark:hover:bg-slate-800"
                  >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                      <path
                        d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z"
                        fill="#4285F4"
                      />
                      <path
                        d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z"
                        fill="#34A853"
                      />
                      <path
                        d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z"
                        fill="#FBBC05"
                      />
                      <path
                        d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z"
                        fill="#EB4335"
                      />
                    </svg>

                    Registra't amb Google
                  </button>

                  <!-- Microsoft (igual que tenías) -->
                  <button
                    class="inline-flex items-center justify-center gap-3 rounded-3xl bg-slate-100 px-7 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-200 dark:bg-slate-900/70 dark:text-slate-100 dark:hover:bg-slate-800"
                  >
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none">
                      <path d="M0 0h10.5v10.5H0V0z" fill="#F25022"/>
                      <path d="M10.5 0H21v10.5H10.5V0z" fill="#00A4EF"/>
                      <path d="M0 10.5h10.5V21H0v-10.5z" fill="#7FBA00"/>
                      <path d="M10.5 10.5H21V21H10.5v-10.5z" fill="#FFB900"/>
                    </svg>

                    Inicieu la sessió amb Microsoft
                  </button>

                </div>

                <!-- Divider -->
                <div class="relative py-5">
                  <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                  </div>
                  <div class="relative flex justify-center text-sm">
                    <span class="p-2 text-slate-500 bg-white dark:bg-slate-950">O</span>
                  </div>
                </div>
              </div>

              <p class="text-sm text-slate-500">
                Introdueix les següents dades per a crear el teu compte.
              </p>

              <br/>

              <!-- FORM -->
              <form @submit.prevent="handleSubmit">

                <div class="space-y-5">

                  <!-- Name -->
                  <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <input v-model="firstName" placeholder="Nom" class="input" />
                    <input v-model="lastName" placeholder="Cognoms" class="input" />

                  </div>

                  <!-- Email -->
                  <input v-model="email" type="email" placeholder="Email" class="input" />

                  <!-- Password -->
                  <input v-model="password" type="password" placeholder="Contrasenya" class="input" />

                  <!-- Confirm -->
                  <input v-model="confirmPassword" type="password" placeholder="Confirma contrasenya" class="input" />

                  <!-- Terms -->
                  <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" v-model="agreeToTerms" />
                    Accepto els termes i condicions
                  </label>

                  <!-- Submit -->
                  <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-3xl hover:bg-blue-700">
                    Registra't
                  </button>

                </div>

              </form>

              <!-- Login link -->
              <div class="mt-5 text-sm text-center">
                Ja tens un compte?
                <router-link to="/signin" class="text-blue-600">Inicia la sessió</router-link>
              </div>

            </div>
          </div>
        </div>

        <!-- Right panel -->
        <div
          class="relative items-center hidden w-full h-full lg:w-1/2 bg-brand-950 dark:bg-white/5 lg:grid"
        >
          <div class="flex items-center justify-center z-1">
            <common-grid-shape />
            <div class="flex flex-col items-center max-w-xs">
              <router-link to="/" class="block mb-4">
                <img width="100%" src="/images/logo/logo.png" alt="Logo" class="block dark:hidden"/>
              </router-link>
            </div>
          </div>
        </div>

      </div>
    </div>
  </FullScreenLayout>
</template>

<script setup lang="ts">
import FullScreenLayout from '../../components/layout/FullScreenLayout.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import authService from '../../services/authService'
import { useGoogleAuth } from '../../composables/useGoogleAuth'
import { GOOGLE_CLIENT_ID } from '../../config/googleConfig'

const router = useRouter()

const firstName = ref('')
const lastName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const agreeToTerms = ref(false)

const { signInWithGoogle, isLoading: googleLoading } = useGoogleAuth()

const handleGoogleRegister = async () => {
  await signInWithGoogle(GOOGLE_CLIENT_ID)
}

const handleSubmit = async () => {
  try {
    const response = await authService.register(firstName.value+' '+lastName.value, email.value, password.value)

    authService.saveToken(response.data.token)

    router.push('/dashboard')

    console.log('signup', {
      firstName: firstName.value,
      lastName: lastName.value,
      email: email.value,
    })
  } catch (error: any) {
    error.value = error?.response?.data?.error || 'Error login'
    console.error(error)
  }
}
</script>

<style scoped>
.input {
  width: 100%;
  padding: 10px;
  border-radius: 12px;
  border: 1px solid #ddd;
  background: #f8fafc;
}
</style>