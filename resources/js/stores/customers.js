import { defineStore } from 'pinia'
import axios from 'axios'

export const useCustomerStore = defineStore('customers', {
  state: () => ({
    customers: [],
    customer: null,
    loading: false,
    pagination: {}
  }),

  actions: {
    async fetchCustomers(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/customers', { params })
        this.customers = response.data.data.data
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

    async fetchCustomer(id) {
      this.loading = true
      try {
        const response = await axios.get(`/customers/${id}`)
        this.customer = response.data.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async createCustomer(data) {
      this.loading = true
      try {
        const response = await axios.post('/customers', data)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateCustomer(id, data) {
      this.loading = true
      try {
        const response = await axios.put(`/customers/${id}`, data)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteCustomer(id) {
      this.loading = true
      try {
        const response = await axios.delete(`/customers/${id}`)
        return response.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})