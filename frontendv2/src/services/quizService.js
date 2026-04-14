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

/**
 * Retorna un quiz amb totes les preguntes i respostes/pairs
 */
export async function getQuiz(id) {
  // 1️⃣ Obtenir el quiz
  return axios.get(`/api/quizzes/${id}`, { headers: {
      Authorization: `Bearer ${authService.getToken()}`
    } })
}

export function updateQuiz(id, data) {
  return axios.put(`/api/quizzes/${id}`, data, {
    headers: {
      Authorization: `Bearer ${authService.getToken()}`
    }
  })
}