import { ref } from 'vue'
import { MICROSOFT_CLIENT_ID, MICROSOFT_TENANT_ID, MICROSOFT_REDIRECT_URI } from '../config/msConfig'

const isLoading = ref(false)

export function useMicrosoftAuth() {

  const loginWithMicrosoft = () => {
    try {
      isLoading.value = true

      const clientId = MICROSOFT_CLIENT_ID
      const tenantId = MICROSOFT_TENANT_ID
      const redirectUri = encodeURIComponent(
        MICROSOFT_REDIRECT_URI
      )

      const scope = encodeURIComponent('openid profile email')
      const responseType = 'code'

      const authUrl =
        `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=${clientId}` +
        `?client_id=${clientId}` +
        `&response_type=${responseType}` +
        `&redirect_uri=${redirectUri}` +
        `&response_mode=query` +
        `&scope=${scope}`

      window.location.href = authUrl

    } catch (error) {
      console.error('Microsoft login error:', error)
    } finally {
      isLoading.value = false
    }
  }

  return {
    loginWithMicrosoft,
    isLoading
  }
}