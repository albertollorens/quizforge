import axios from 'axios'

const API_URL = '/api'
const OAUTH_URL = '/auth'

// Instància axios centralitzada
const api = axios.create({
  baseURL: API_URL
})

/* ================================
   REQUEST INTERCEPTOR
================================ */
api.interceptors.request.use(config => {
  const token = localStorage.getItem('jwt_token')

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

/* ================================
   RESPONSE INTERCEPTOR
================================ */
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {

      // Token expirat o invàlid
      localStorage.removeItem('jwt_token')
      localStorage.removeItem('user')

      // Event global per mostrar missatge amigable
      window.dispatchEvent(new Event('session-expired'))

      // Redirigir a login
      window.location.href = '/login'
    }

    return Promise.reject(error)
  }
)

/* ================================
   AUTH SERVICE
================================ */
export default {

  async login(email, password) {
    return api.post('/login', { email, password })
  },

  async register(username, email, password) {
    return api.post('/register', { username, email, password })
  },

  async updateProfile(data) {
    const user = this.getUser()
    if (!user) throw new Error('Usuari no loguejat')

    const response = await api.put(`/users/${user.id}`, data)

    localStorage.setItem('user', JSON.stringify(response.data))
    return response.data
  },

  saveToken(token) {
    localStorage.setItem('jwt_token', token)

    const payload = JSON.parse(atob(token.split('.')[1]))

    localStorage.setItem('user', JSON.stringify({
      id: payload.sub,
      username: payload.username,
      email: payload.email
    }))
  },

  getUser() {
    const user = localStorage.getItem('user')
    return user ? JSON.parse(user) : null
  },

  getToken() {
    return localStorage.getItem('jwt_token')
  },

  logout() {
    localStorage.removeItem('jwt_token')
    localStorage.removeItem('user')
    window.location.href = '/login'
  },

  isAuthenticated() {
    const token = localStorage.getItem('jwt_token')
    if (!token) return false

    try {
      const payload = JSON.parse(atob(token.split('.')[1]))
      const now = Date.now() / 1000

      // Comprovem expiració
      if (payload.exp && payload.exp < now) {
        this.logout()
        return false
      }

      return true

    } catch (e) {
      this.logout()
      return false
    }
  },

  /* ================================
   SOCIAL AUTH (Google via API)
  ================================ */

  async loginWithGoogle(googleToken) {
    const response = await api.post('/auth/google', {
      token: googleToken
    })

    if (response.data?.token) {
      this.saveToken(response.data.token)
    }

    return response.data
  },

  async registerWithGoogle(googleToken) {
    const response = await api.post('/auth/google/register', {
      token: googleToken
    })

    // guardar JWT directament (auto login)
    if (response.data?.token) {
      this.saveToken(response.data.token)
    }

    return response.data
  },

  api // Exportem api per usar-lo en altres serveis
}