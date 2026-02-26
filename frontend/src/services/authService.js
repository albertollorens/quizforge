import axios from 'axios';

const API_URL = '/api'; // amb Vite, es redirigeix a Slim vía proxy

export default {
  async login(email, password) {
    return axios.post(`${API_URL}/login`, { email, password });
  },

  async register(username, email, password) {
    return axios.post(`${API_URL}/register`, { username, email, password });
  },

  async updateProfile(data) {
    const user = this.getUser()
    if (!user) throw new Error('Usuari no loguejat')

    const response = await api.put(`/users/${user.id}`, data)
    // Actualitzem localStorage
    localStorage.setItem('user', JSON.stringify(response.data))
    return response.data
  },

  saveToken(token) {
    localStorage.setItem('jwt_token', token);
    
    // Decodificar JWT
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
    return localStorage.getItem('jwt_token');
  },

  logout() {
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('user')
  },

  isAuthenticated() {
    return !!localStorage.getItem('jwt_token');
  }
};