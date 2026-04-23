import { ref } from 'vue'
import authService from '@/services/authService'

const isGoogleLoaded = ref(false)
const isLoading = ref(false)

declare global {
  interface Window {
    google: any
  }
}

export function useGoogleAuth() {

  const loadGoogleScript = (): Promise<void> => {
    return new Promise((resolve, reject) => {
      if (window.google?.accounts) {
        isGoogleLoaded.value = true
        resolve()
        return
      }

      const script = document.createElement('script')
      script.src = 'https://accounts.google.com/gsi/client'
      script.async = true
      script.defer = true

      script.onload = () => {
        isGoogleLoaded.value = true
        resolve()
      }

      script.onerror = reject
      document.head.appendChild(script)
    })
  }

  const handleGoogleResponse = async (response: { credential: string }) => {
    try {
      isLoading.value = true

      const result = await authService.authWithGoogle(response.credential)

      if (!result?.token) {
        throw new Error('Token no rebut del backend')
      }

      authService.saveToken(result.token)

      window.location.href = '/dashboard'

    } catch (error) {
      console.error('❌ Google login error:', error)
      alert('Error iniciant sessió amb Google')
    } finally {
      isLoading.value = false
    }
  }

  const initializeGoogle = (clientId: string) => {
    if (!window.google?.accounts) {
      throw new Error('Google SDK no carregat')
    }

    window.google.accounts.id.initialize({
      client_id: clientId,
      callback: handleGoogleResponse,
      auto_select: false,
      cancel_on_tap_outside: true
    })
  }

  /**
   * 🔥 NUEVO: renderButton (RECOMENDADO para Firefox)
   */
  const renderGoogleButton = (elementId: string, clientId: string) => {
    if (!window.google?.accounts) {
      throw new Error('Google SDK no carregat')
    }

    initializeGoogle(clientId)

    const container = document.getElementById(elementId)

    if (!container) {
      throw new Error(`Element #${elementId} no trobat`)
    }

    window.google.accounts.id.renderButton(container, {
      theme: 'outline',
      size: 'large',
      type: 'standard',
      shape: 'pill',
    })
  }

  /**
   * 🔥 Login manual (fallback + compatibilidad)
   */
  const signInWithGoogle = async (clientId: string) => {
    try {
      if (!isGoogleLoaded.value) {
        await loadGoogleScript()
      }

      initializeGoogle(clientId)

      /**
       * ❌ NO dependemos de prompt (Firefox lo bloquea a veces)
       * ✔ Solo lo usamos como "best effort" en Chrome
       */
      const isChrome = /Chrome/.test(navigator.userAgent)

      if (isChrome) {
        window.google.accounts.id.prompt()
      }

    } catch (error) {
      console.error('Error Google Sign-In:', error)
      alert('Error carregant Google Login')
    }
  }

  return {
    isGoogleLoaded,
    isLoading,
    signInWithGoogle,
    renderGoogleButton
  }
}