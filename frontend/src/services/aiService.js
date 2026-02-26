import axios from 'axios'
import authService from './authService'

export function generateQuestions(data) {    
    return  axios.post("/api/aiquiz/generate", data, {
        headers: {
            Authorization: `Bearer ${authService.getToken()}`
        }
    });
};
