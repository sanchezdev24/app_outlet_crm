import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import routes from './routes'
import './bootstrap'

// ✅ CONFIGURACIÓN AXIOS
axios.defaults.baseURL = 'http://app_outlet_crm.test/api/'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'


// ✅ Interceptor de request para token dinámico
axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('auth_token')
        //console.log(token);
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        
        // Debug completo
        console.log('🔄 Axios Request:', {
            method: config.method?.toUpperCase(),
            fullURL: config.baseURL + (config.url?.startsWith('/') ? config.url.slice(1) : config.url),
            baseURL: config.baseURL,
            url: config.url,
            headers: {
                Accept: config.headers.Accept,
                'Content-Type': config.headers['Content-Type'],
                'X-Requested-With': config.headers['X-Requested-With'],
                Authorization: config.headers.Authorization ? 'Bearer [REDACTED]' : 'None'
            }
        })
        
        return config
    },
    error => {
        console.error('❌ Request interceptor error:', error)
        return Promise.reject(error)
    }
)

// ✅ Interceptor de response mejorado
axios.interceptors.response.use(
    response => {
        console.log('✅ Axios Response:', {
            status: response.status,
            statusText: response.statusText,
            url: response.config.url,
            contentType: response.headers['content-type'],
            dataType: typeof response.data,
            dataPreview: typeof response.data === 'string' 
                ? response.data.substring(0, 100) + '...'
                : response.data
        })
        return response
    },
    error => {
        console.error('❌ Axios Error:', {
            status: error.response?.status,
            statusText: error.response?.statusText,
            url: error.response?.config?.url,
            data: error.response?.data,
            message: error.message
        })
        
        if (error.response?.status === 401) {
            console.warn('🔒 Unauthorized - clearing auth')
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

// ✅ Debug info
console.log('🚀 Vue app initialized')
console.log('📡 Axios baseURL:', axios.defaults.baseURL)
console.log('🔑 Token present:', !!localStorage.getItem('auth_token'))

// ✅ Hacer axios globalmente disponible
app.config.globalProperties.$axios = axios
window.axios = axios