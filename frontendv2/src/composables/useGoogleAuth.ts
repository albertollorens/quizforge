import { ref } from 'vue'
import authService from '@/services/authService'

const isGoogleLoaded = ref(false)
const isLoading = ref(false)

export function useGoogleAuth() {
  const loadGoogleScript = (): Promise<void> => {
    return new Promise<void>((resolve, reject) => {
      if (window.google && window.google.accounts) {
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

  const initializeGoogleSignIn = (clientId: string): void => {
    if (!window.google || !window.google.accounts) {
      throw new Error('Google script not loaded')
    }
    
    window.google.accounts.id.initialize({
      client_id: clientId,
      callback: handleGoogleResponse,
      auto_select: false,
      cancel_on_tap_outside: true
    })
  }

  const handleGoogleResponse = async (response: { credential: string }): Promise<void> => {
    try {
      isLoading.value = true

      // Enviar el token de Google al backend
      const result = await authService.authWithGoogle(response.credential)

      // Redirigir al dashboard o página principal
      window.location.href = '/dashboard'

    } catch (error) {
      console.error('Error during Google authentication:', error)
      // Aquí podrías mostrar un mensaje de error al usuario
      alert('Error al iniciar sesión con Google. Por favor, inténtalo de nuevo.')
    } finally {
      isLoading.value = false
    }
  }

  const signInWithGoogle = async (clientId: string): Promise<void> => {
    try {
      if (!isGoogleLoaded.value) {
        await loadGoogleScript()
      }

      initializeGoogleSignIn(clientId)

      // Mostrar el popup de Google
      window.google.accounts.id.prompt()

    } catch (error) {
      console.error('Error initializing Google Sign-In:', error)
      alert('Error al cargar Google Sign-In. Por favor, recarga la página.')
    }
  }

  return {
    isGoogleLoaded,
    isLoading,
    loadGoogleScript,
    signInWithGoogle,
    authWithGoogle: signInWithGoogle,
  }
}