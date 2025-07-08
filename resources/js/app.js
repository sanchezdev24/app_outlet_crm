import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import routes from './routes'
import './bootstrap'

// Configure axios
axios.defaults.baseURL = '/api'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

// Add token to requests
const token = localStorage.getItem('auth_token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

// Response interceptor for handling auth errors
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

const router = createRouter({
    history: createWebHistory(),
    routes
})

const pinia = createPinia()
const app = createApp(App)

app.use(router)
app.use(pinia)
app.mount('#app')