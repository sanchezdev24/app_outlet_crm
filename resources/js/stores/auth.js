import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin'
  },

  actions: {
    async login(credentials) {
      this.loading = true
      try {
        const response = await axios.post('/auth/login', credentials)
        const { user, token } = response.data.data
        
        this.user = user
        this.token = token
        
        localStorage.setItem('auth_token', token)
        localStorage.setItem('user', JSON.stringify(user))
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await axios.post('/auth/logout')
      } catch (error) {
        // Handle logout error
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user')
        delete axios.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get('/auth/me')
        this.user = response.data.data
      } catch (error) {
        this.logout()
      }
    },

    setToken(token) {
      this.token = token
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }
  }
})