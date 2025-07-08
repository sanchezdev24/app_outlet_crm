<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-2xl font-bold text-gray-900">Ventas</h1>
        <p class="mt-2 text-sm text-gray-700">
          Gestiona y monitorea todas las ventas del sistema
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button
          @click="$emit('create-sale')"
          class="btn-primary"
        >
          ➕ Nueva Venta
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="text-2xl">📊</div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Total Ventas</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.totalSales }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="text-2xl">💰</div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Ingresos Totales</dt>
                <dd class="text-lg font-medium text-gray-900">${{ stats.totalRevenue.toFixed(2) }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="text-2xl">📈</div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Promedio por Venta</dt>
                <dd class="text-lg font-medium text-gray-900">${{ stats.averageSale.toFixed(2) }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="text-2xl">🕐</div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Ventas Hoy</dt>
                <dd class="text-lg font-medium text-gray-900">{{ stats.todaySales }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-6">
        <div class="sm:col-span-2">
          <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
          <input
            id="search"
            v-model="filters.search"
            type="text"
            placeholder="Cliente, ID de venta..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            @input="debouncedSearch"
          />
        </div>

        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
          <select
            id="status"
            v-model="filters.status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            @change="fetchSales"
          >
            <option value="">Todos</option>
            <option value="pending">Pendiente</option>
            <option value="completed">Completada</option>
            <option value="cancelled">Cancelada</option>
            <option value="draft">Borrador</option>
          </select>
        </div>

        <div>
          <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago</label>
          <select
            id="payment_method"
            v-model="filters.payment_method"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            @change="fetchSales"
          >
            <option value="">Todos</option>
            <option value="cash">💵 Efectivo</option>
            <option value="card">💳 Tarjeta</option>
            <option value="transfer">🏦 Transferencia</option>
          </select>
        </div>

        <div>
          <label for="date_from" class="block text-sm font-medium text-gray-700">Desde</label>
          <input
            id="date_from"
            v-model="filters.date_from"
            type="date"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            @change="fetchSales"
          />
        </div>

        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full btn-secondary"
          >
            🗑️ Limpiar
          </button>
        </div>
      </div>

      <!-- Quick Date Filters -->
      <div class="mt-4 flex flex-wrap gap-2">
        <button
          @click="setDateFilter('today')"
          :class="isDateFilterActive('today') ? 'btn-primary' : 'btn-secondary'"
          class="text-sm"
        >
          Hoy
        </button>
        <button
          @click="setDateFilter('week')"
          :class="isDateFilterActive('week') ? 'btn-primary' : 'btn-secondary'"
          class="text-sm"
        >
          Esta Semana
        </button>
        <button
          @click="setDateFilter('month')"
          :class="isDateFilterActive('month') ? 'btn-primary' : 'btn-secondary'"
          class="text-sm"
        >
          Este Mes
        </button>
        <button
          @click="setDateFilter('year')"
          :class="isDateFilterActive('year') ? 'btn-primary' : 'btn-secondary'"
          class="text-sm"
        >
          Este Año
        </button>
      </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Table Header -->
      <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-medium text-gray-900">
            Lista de Ventas
            <span v-if="pagination.total" class="text-sm text-gray-500 font-normal">
              ({{ pagination.total }} resultados)
            </span>
          </h3>
          
          <!-- View Toggle -->
          <div class="flex items-center space-x-2">
            <button
              @click="viewMode = 'table'"
              :class="viewMode === 'table' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'"
              class="p-2 rounded-md"
            >
              📋 Tabla
            </button>
            <button
              @click="viewMode = 'cards'"
              :class="viewMode === 'cards' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'"
              class="p-2 rounded-md"
            >
              🃏 Tarjetas
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        <p class="mt-2 text-gray-600">Cargando ventas...</p>
      </div>

      <!-- Table View -->
      <div v-else-if="viewMode === 'table'" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th 
                @click="sortBy('id')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              >
                ID
                <span v-if="sortField === 'id'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Cliente
              </th>
              <th 
                @click="sortBy('total')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              >
                Total
                <span v-if="sortField === 'total'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Pago
              </th>
              <th 
                @click="sortBy('created_at')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              >
                Fecha
                <span v-if="sortField === 'created_at'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="relative px-6 py-3">
                <span class="sr-only">Acciones</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="sale in sales" :key="sale.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ sale.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-xs font-medium text-gray-700">
                        {{ getCustomerInitials(sale.customer) }}
                      </span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ sale.customer?.name }} {{ sale.customer?.last_name }}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ sale.customer?.email }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  <span class="font-medium">${{ sale.final_total.toFixed(2) }}</span>
                  <span v-if="sale.discount > 0" class="text-xs text-gray-500 block">
                    (Desc: ${{ sale.discount.toFixed(2) }})
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(sale.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusLabel(sale.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ getPaymentMethodLabel(sale.payment_method) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div>{{ formatDate(sale.created_at) }}</div>
                <div class="text-xs">{{ formatTime(sale.created_at) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center space-x-2">
                  <button
                    @click="viewSale(sale)"
                    class="text-blue-600 hover:text-blue-900"
                    title="Ver detalles"
                  >
                    👁️
                  </button>
                  <button
                    v-if="sale.status !== 'completed'"
                    @click="$emit('edit-sale', sale.id)"
                    class="text-green-600 hover:text-green-900"
                    title="Editar"
                  >
                    ✏️
                  </button>
                  <button
                    @click="duplicateSale(sale)"
                    class="text-purple-600 hover:text-purple-900"
                    title="Duplicar"
                  >
                    📋
                  </button>
                  <button
                    v-if="sale.status === 'pending'"
                    @click="updateSaleStatus(sale.id, 'completed')"
                    class="text-green-600 hover:text-green-900"
                    title="Completar"
                  >
                    ✅
                  </button>
                  <button
                    v-if="sale.status !== 'cancelled'"
                    @click="updateSaleStatus(sale.id, 'cancelled')"
                    class="text-red-600 hover:text-red-900"
                    title="Cancelar"
                  >
                    ❌
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cards View -->
      <div v-else-if="viewMode === 'cards'" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="sale in sales"
            :key="sale.id"
            class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"
          >
            <div class="p-6">
              <!-- Header -->
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Venta #{{ sale.id }}</h3>
                <span :class="getStatusClass(sale.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusLabel(sale.status) }}
                </span>
              </div>

              <!-- Customer Info -->
              <div class="mb-4">
                <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-700">
                        {{ getCustomerInitials(sale.customer) }}
                      </span>
                    </div>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">
                      {{ sale.customer?.name }} {{ sale.customer?.last_name }}
                    </p>
                    <p class="text-sm text-gray-500">{{ sale.customer?.email }}</p>
                  </div>
                </div>
              </div>

              <!-- Sale Details -->
              <div class="mb-4 space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Total:</span>
                  <span class="font-medium">${{ sale.final_total.toFixed(2) }}</span>
                </div>
                <div v-if="sale.discount > 0" class="flex justify-between text-sm">
                  <span class="text-gray-600">Descuento:</span>
                  <span class="text-red-600">-${{ sale.discount.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Pago:</span>
                  <span>{{ getPaymentMethodLabel(sale.payment_method) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Fecha:</span>
                  <span>{{ formatDate(sale.created_at) }}</span>
                </div>
              </div>

              <!-- Items Preview -->
              <div v-if="sale.items && sale.items.length > 0" class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Productos ({{ sale.items.length }}):</p>
                <div class="space-y-1">
                  <div
                    v-for="item in sale.items.slice(0, 2)"
                    :key="item.id"
                    class="text-xs text-gray-500 flex justify-between"
                  >
                    <span>{{ item.product?.name }}</span>
                    <span>{{ item.quantity }}x</span>
                  </div>
                  <div v-if="sale.items.length > 2" class="text-xs text-gray-400">
                    +{{ sale.items.length - 2 }} más...
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <button
                  @click="viewSale(sale)"
                  class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                >
                  Ver Detalles
                </button>
                <div class="flex space-x-2">
                  <button
                    v-if="sale.status !== 'completed'"
                    @click="$emit('edit-sale', sale.id)"
                    class="text-green-600 hover:text-green-900"
                    title="Editar"
                  >
                    ✏️
                  </button>
                  <button
                    @click="duplicateSale(sale)"
                    class="text-purple-600 hover:text-purple-900"
                    title="Duplicar"
                  >
                    📋
                  </button>
                  <button
                    v-if="sale.status === 'pending'"
                    @click="updateSaleStatus(sale.id, 'completed')"
                    class="text-green-600 hover:text-green-900"
                    title="Completar"
                  >
                    ✅
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="sales.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay ventas</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera venta.</p>
        <div class="mt-6">
          <button
            @click="$emit('create-sale')"
            class="btn-primary"
          >
            ➕ Nueva Venta
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.total_pages > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="changePage(pagination.page - 1)"
          :disabled="pagination.page <= 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          Anterior
        </button>
        <button
          @click="changePage(pagination.page + 1)"
          :disabled="pagination.page >= pagination.total_pages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Mostrando página {{ pagination.page }} de {{ pagination.total_pages }}
            ({{ pagination.total }} resultados)
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button
              v-for="page in getVisiblePages()"
              :key="page"
              @click="changePage(page)"
              :class="page === pagination.page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Sale Detail Modal -->
    <div v-if="selectedSale" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="selectedSale = null"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Detalles de Venta #{{ selectedSale.id }}
              </h3>
              <button
                @click="selectedSale = null"
                class="text-gray-400 hover:text-gray-600"
              >
                ✕
              </button>
            </div>
            
            <!-- Sale Detail Content -->
            <SaleDetail v-if="selectedSale" :sale="selectedSale" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import SaleDetail from './SaleDetail.vue'

// Emits
const emit = defineEmits(['create-sale', 'edit-sale', 'sale-updated'])

// Reactive data
const loading = ref(false)
const sales = ref([])
const selectedSale = ref(null)
const viewMode = ref('table') // 'table' or 'cards'
const sortField = ref('created_at')
const sortDirection = ref('desc')

const filters = ref({
  search: '',
  status: '',
  payment_method: '',
  date_from: '',
  date_to: '',
  page: 1
})

const pagination = ref({
  page: 1,
  per_page: 15,
  total: 0,
  total_pages: 0
})

const stats = ref({
  totalSales: 0,
  totalRevenue: 0,
  averageSale: 0,
  todaySales: 0
})

let searchTimeout = null

// Computed properties
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.value.page = 1
    fetchSales()
  }, 500)
}

// Methods
const fetchSales = async () => {
  loading.value = true
  try {
    const params = {
      ...filters.value,
      sort_field: sortField.value,
      sort_direction: sortDirection.value
    }
    
    const response = await window.axios.get('/api/sales', { params })
    if (response.data.success) {
      sales.value = response.data.data.data
      pagination.value = {
        page: response.data.data.page,
        per_page: response.data.data.per_page,
        total: response.data.data.total,
        total_pages: response.data.data.total_pages
      }
    }
  } catch (error) {
    console.error('Error loading sales:', error)
  } finally {
    loading.value = false
  }
}

const fetchStats = async () => {
  try {
    const response = await window.axios.get('/api/sales/stats')
    if (response.data.success) {
      stats.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const clearFilters = () => {
  filters.value = {
    search: '',
    status: '',
    payment_method: '',
    date_from: '',
    date_to: '',
    page: 1
  }
  fetchSales()
}

const setDateFilter = (period) => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  
  switch (period) {
    case 'today':
      filters.value.date_from = `${year}-${month}-${day}`
      filters.value.date_to = `${year}-${month}-${day}`
      break
    case 'week':
      const weekStart = new Date(today.setDate(today.getDate() - today.getDay()))
      filters.value.date_from = weekStart.toISOString().split('T')[0]
      filters.value.date_to = new Date().toISOString().split('T')[0]
      break
    case 'month':
      filters.value.date_from = `${year}-${month}-01`
      filters.value.date_to = `${year}-${month}-${day}`
      break
    case 'year':
      filters.value.date_from = `${year}-01-01`
      filters.value.date_to = `${year}-${month}-${day}`
      break
  }
  fetchSales()
}

const isDateFilterActive = (period) => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  
  switch (period) {
    case 'today':
      return filters.value.date_from === `${year}-${month}-${day}`
    case 'month':
      return filters.value.date_from === `${year}-${month}-01`
    // Add more cases as needed
    default:
      return false
  }
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'desc'
  }
  fetchSales()
}

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.total_pages) {
    filters.value.page = page
    fetchSales()
  }
}

const getVisiblePages = () => {
  const currentPage = pagination.value.page
  const totalPages = pagination.value.total_pages
  const pages = []
  
  for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
    pages.push(i)
  }
  
  return pages
}

const viewSale = async (sale) => {
  try {
    loading.value = true
    const response = await window.axios.get(`/api/sales/${sale.id}`)
    if (response.data.success) {
      selectedSale.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading sale details:', error)
  } finally {
    loading.value = false
  }
}

const updateSaleStatus = async (saleId, newStatus) => {
  const confirmMessage = newStatus === 'completed' 
    ? '¿Confirmar que la venta ha sido completada?'
    : '¿Estás seguro de cancelar esta venta?'
    
  if (!confirm(confirmMessage)) return
  
  try {
    const response = await window.axios.patch(`/api/sales/${saleId}/status`, {
      status: newStatus
    })
    
    if (response.data.success) {
      // Update local data
      const saleIndex = sales.value.findIndex(s => s.id === saleId)
      if (saleIndex !== -1) {
        sales.value[saleIndex].status = newStatus
      }
      
      // Refresh stats
      await fetchStats()
      
      emit('sale-updated', response.data.data)
    }
  } catch (error) {
    console.error('Error updating sale status:', error)
    alert('Error al actualizar el estado de la venta')
  }
}

const duplicateSale = async (sale) => {
  if (!confirm('¿Crear una nueva venta basada en esta?')) return
  
  try {
    const response = await window.axios.post(`/api/sales/${sale.id}/duplicate`)
    if (response.data.success) {
      emit('edit-sale', response.data.data.id)
    }
  } catch (error) {
    console.error('Error duplicating sale:', error)
    alert('Error al duplicar la venta')
  }
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
    draft: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Pendiente',
    completed: 'Completada',
    cancelled: 'Cancelada',
    draft: 'Borrador'
  }
  return labels[status] || status
}

const getPaymentMethodLabel = (method) => {
  const labels = {
    cash: '💵 Efectivo',
    card: '💳 Tarjeta',
    transfer: '🏦 Transferencia'
  }
  return labels[method] || method
}

const getCustomerInitials = (customer) => {
  if (!customer) return '?'
  return `${customer.name?.charAt(0) || ''}${customer.last_name?.charAt(0) || ''}`.toUpperCase()
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('es-ES')
}

const formatTime = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleTimeString('es-ES', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

// Lifecycle
onMounted(async () => {
  await Promise.all([
    fetchSales(),
    fetchStats()
  ])
})
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

.btn-secondary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

/* Table hover effects */
tbody tr:hover {
  @apply bg-gray-50;
}

/* Sort indicators */
th.cursor-pointer:hover {
  @apply bg-gray-100;
}

/* Card hover effects */
.card-hover {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Status badges */
.status-badge {
  @apply inline-flex px-2 py-1 text-xs font-semibold rounded-full;
}

.status-pending {
  @apply bg-yellow-100 text-yellow-800;
}

.status-completed {
  @apply bg-green-100 text-green-800;
}

.status-cancelled {
  @apply bg-red-100 text-red-800;
}

.status-draft {
  @apply bg-gray-100 text-gray-800;
}

/* Action buttons */
.action-btn {
  @apply text-sm font-medium transition-colors duration-150;
}

.action-btn:hover {
  @apply opacity-75;
}

/* Loading spinner */
.loading-spinner {
  @apply animate-spin rounded-full border-b-2 border-blue-500;
}

/* Modal overlay */
.modal-overlay {
  @apply fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity;
}

.modal-content {
  @apply inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .stats-grid {
    @apply grid-cols-1;
  }
  
  .filter-grid {
    @apply grid-cols-1;
  }
  
  .cards-grid {
    @apply grid-cols-1;
  }
}

@media (max-width: 640px) {
  .table-container {
    @apply overflow-x-auto;
  }
  
  .pagination-mobile {
    @apply flex;
  }
  
  .pagination-desktop {
    @apply hidden;
  }
}

/* Quick date filter buttons */
.date-filter-btn {
  @apply px-3 py-1 text-sm rounded-md transition-colors duration-150;
}

.date-filter-active {
  @apply bg-blue-100 text-blue-700;
}

.date-filter-inactive {
  @apply bg-gray-100 text-gray-600 hover:bg-gray-200;
}

/* View mode toggle */
.view-toggle {
  @apply p-2 rounded-md transition-colors duration-150;
}

.view-toggle-active {
  @apply bg-blue-100 text-blue-700;
}

.view-toggle-inactive {
  @apply text-gray-500 hover:text-gray-700 hover:bg-gray-100;
}

/* Custom scrollbar for table */
.table-container::-webkit-scrollbar {
  height: 6px;
}

.table-container::-webkit-scrollbar-track {
  @apply bg-gray-100;
}

.table-container::-webkit-scrollbar-thumb {
  @apply bg-gray-300 rounded;
}

.table-container::-webkit-scrollbar-thumb:hover {
  @apply bg-gray-400;
}

/* Animation for cards */
.card-enter-active,
.card-leave-active {
  transition: all 0.3s ease;
}

.card-enter-from,
.card-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

/* Empty state styling */
.empty-state {
  @apply text-center py-12;
}

.empty-state svg {
  @apply mx-auto h-12 w-12 text-gray-400;
}

.empty-state h3 {
  @apply mt-2 text-sm font-medium text-gray-900;
}

.empty-state p {
  @apply mt-1 text-sm text-gray-500;
}

/* Sale detail modal improvements */
.sale-detail-modal {
  @apply fixed inset-0 z-50 overflow-y-auto;
}

.sale-detail-backdrop {
  @apply flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0;
}

.sale-detail-content {
  @apply inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full;
}
</style>