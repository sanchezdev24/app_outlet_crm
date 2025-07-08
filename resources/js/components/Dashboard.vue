<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="mt-1 text-sm text-gray-500">
        Resumen general del sistema CRM Outlet
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Total Clientes
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  {{ stats.totalCustomers }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Total Productos
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  {{ stats.totalProducts }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Ventas del Mes
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  {{ stats.monthlySales }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Ingresos del Mes
                </dt>
                <dd class="text-lg font-medium text-gray-900">
                  ${{ stats.monthlyRevenue }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Acciones Rápidas
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
          <router-link
            to="/customers/create"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Nuevo Cliente
          </router-link>
          <router-link
            to="/products/create"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
          >
            Nuevo Producto
          </router-link>
          <router-link
            to="/sales/create"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
          >
            Nueva Venta
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Valores por defecto para prevenir undefined
const stats = ref({
  totalCustomers: 0,
  totalProducts: 0,
  monthlySales: 0,
  monthlyRevenue: '0.00'
})

// Estado de carga (opcional)
const loading = ref(false)

onMounted(async () => {
  await loadStats()
})

const loadStats = async () => {
  loading.value = true
  try {
    console.log(axios.defaults.headers.common)
    // Vamos a verificar qué está pasando
    console.log("Debugging steps:")
    console.log("1. Token presente:", !!localStorage.getItem('auth_token'))
    console.log("2. Token value:", localStorage.getItem('auth_token'))
    console.log("3. Current URL:", window.location.href)
    const response = await axios.get('/dashboard/stats')
    
    // Debug: ver qué datos llegan
    console.log('API Response:', response.data)
    
    // Verificar que response.data.data existe
    if (response.data && response.data.data) {
      const apiData = response.data.data
      
      // Actualizar solo las propiedades que existen, manteniendo valores por defecto
      stats.value = {
        totalCustomers: apiData.totalCustomers ?? 0,
        totalProducts: apiData.totalProducts ?? 0,
        monthlySales: apiData.monthlySales ?? 0,
        monthlyRevenue: apiData.monthlyRevenue ?? '0.00'
      }
    } else {
      // Si no viene la estructura esperada, usar valores por defecto
      console.warn('API response does not contain expected data structure')
    }
  } catch (error) {
    console.error('Error loading dashboard stats:', error)
    
    // En caso de error, mantener valores por defecto
    // Opcional: mostrar mensaje de error al usuario
    if (error.response) {
      console.error('API Error:', error.response.status, error.response.data)
    }
  } finally {
    loading.value = false
  }
}
</script>