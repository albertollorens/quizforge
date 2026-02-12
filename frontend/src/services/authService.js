import axios from 'axios';

const API_URL = '/api'; // amb Vite, es redirigeix a Slim vía proxy

export default {
  async login(email, password) {
    return axios.post(`${API_URL}/login`, { email, password });
  },

  async register(username, email, password) {
    return axios.post(`${API_URL}/register`, { username, email, password });
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
  },

  isAuthenticated() {
    return !!localStorage.getItem('jwt_token');
  }
};