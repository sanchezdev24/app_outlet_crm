import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('products', {
  state: () => ({
    products: [],
    product: null,
    loading: false,
    pagination: {}
  }),

  actions: {
    async fetchProducts(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/products', { params })
        this.products = response.data.data.data
        this.pagination = {
          total: response.data.data.total,
          page: response.data.data.page,
          per_page: response.data.data.per_page,
          total_pages: response.data.data.total_pages
        }
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchProduct(id) {
      this.loading = true
      try {
        const response = await axios.get(`/products/${id}`)
        this.product = response.data.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async createProduct(data) {
      this.loading = true
      try {
        const response = await axios.post('/products', data)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateProduct(id, data) {
      this.loading = true
      try {
        const response = await axios.put(`/products/${id}`, data)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteProduct(id) {
      this.loading = true
      try {
        const response = await axios.delete(`/products/${id}`)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})