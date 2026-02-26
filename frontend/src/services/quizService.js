import axios from 'axios'
import authService from './authService'

export function getUserQuizzes() {
  return axios.get('/api/quizzes', {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}

export function createQuiz(data) {
  return axios.post('/api/quizzes', data, {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}

export function deleteQuiz(id) {
  return axios.delete(`/api/quizzes/${id}`, {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}

export function getQuiz(id) {
  return axios.get(`/api/quizzes/${id}`, {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}

export function updateQuiz(id, data) {
  return axios.put(`/api/quizzes/${id}`, data, {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}