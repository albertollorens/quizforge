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

      // Redirigir a signin
      window.location.href = '/signin'
    }

    return Promise.reject(error)
  }
)

/* ================================
   AUTH SERVICE
================================ */
export default {

  async login(email: string, password: string) {
    return api.post('/login', { email, password })    
  },

  async register(username: string, email: string, password: string) {
    return api.post('/register', { username, email, password })
  },

  async updateProfile(data: object) {
    const user = this.getUser()
    if (!user) throw new Error('Usuari no loguejat')

    const response = await api.put(`/users/${user.id}`, data)

    localStorage.setItem('user', JSON.stringify(response.data))
    return response.data
  },

  saveToken(token: string) {
    localStorage.setItem('jwt_token', token)

    const payload = JSON.parse(atob(token.split('.')[1]))

    localStorage.setItem('user', JSON.stringify({
      id: payload.sub,
      username: payload.username,
      email: payload.email,
      rol: payload.rol,
      plan: payload.plan,
      planStr: getPlan(payload.plan),
      planicon: getPlanIcon(payload.plan),
      quizzes: payload.quizzes
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
    window.location.href = '/signin'
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

  async authWithGoogle(googleToken: string) {
    const response = await api.post('/auth/google', {
      token: googleToken
    })

    if (response.data?.token) {
      this.saveToken(response.data.token)
    }

    return response.data
  },

  async authWithMicrosoft(microsoftToken: string) {
    const response = await api.post('/auth/microsoft', {
      token: microsoftToken
    })
    if (response.data?.token) {
      this.saveToken(response.data.token)
    }
    return response.data
  },

  api // Exportem api per usar-lo en altres serveis
}

function getPlan(plan: string) {
  switch(plan) {
    case 'free': return 'Bàsic'
      break
    case 'pro': return 'Pro Docent'
      break
    case 'premium': return 'Premium Centre/Empresa'
      break
    default: return 'Bàsic'
  }
}

function getPlanIcon(plan: string) {
  switch(plan) {
    case 'free': return '/images/product/lightbulb.png'
      break
    case 'pro': return '/images/product/star.png'
      break
    case 'premium': return '/images/product/crown.png'
      break
    default: return '/images/product/lightbulb.png'
  }
}