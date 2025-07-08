<template>
  <div class="space-y-6">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Clientes</h1>
        <p class="mt-2 text-sm text-gray-700">
          Lista de todos los clientes registrados en el sistema.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          to="/customers/create"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
        >
          Nuevo Cliente
        </router-link>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Buscar</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Nombre, email..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @input="debouncedSearch"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Tipo</label>
          <select
            v-model="filters.type"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @change="fetchCustomers"
          >
            <option value="">Todos</option>
            <option value="regular">Regular</option>
            <option value="vip">VIP</option>
            <option value="wholesale">Mayorista</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Estado</label>
          <select
            v-model="filters.active"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @change="fetchCustomers"
          >
            <option value="">Todos</option>
            <option value="true">Activos</option>
            <option value="false">Inactivos</option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
          >
            Limpiar
          </button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Cliente
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Contacto
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Tipo
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Estado
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Fecha Registro
            </th>
            <th class="relative px-6 py-3">
              <span class="sr-only">Acciones</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="customer in customerStore.customers" :key="customer.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">
                      {{ customer.name.charAt(0) }}{{ customer.lastName.charAt(0) }}
                    </span>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ customer.name }} {{ customer.lastName }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ customer.contactInfo.email }}</div>
              <div class="text-sm text-gray-500">{{ customer.contactInfo.phone }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getTypeClass(customer.type.value)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                {{ getTypeLabel(customer.type.value) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="customer.isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                {{ customer.isActive ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(customer.createdAt) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <router-link
                :to="`/customers/${customer.id}/edit`"
                class="text-indigo-600 hover:text-indigo-900 mr-3"
              >
                Editar
              </router-link>
              <button
                @click="deleteCustomer(customer.id)"
                class="text-red-600 hover:text-red-900"
              >
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Loading State -->
      <div v-if="customerStore.loading" class="text-center py-4">
        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="customerStore.customers.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay clientes</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo cliente.</p>
        <div class="mt-6">
          <router-link
            to="/customers/create"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Nuevo Cliente
          </router-link>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="customerStore.pagination.total_pages > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="changePage(customerStore.pagination.page - 1)"
          :disabled="customerStore.pagination.page <= 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          Anterior
        </button>
        <button
          @click="changePage(customerStore.pagination.page + 1)"
          :disabled="customerStore.pagination.page >= customerStore.pagination.total_pages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          Siguiente
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Mostrando página {{ customerStore.pagination.page }} de {{ customerStore.pagination.total_pages }}
            ({{ customerStore.pagination.total }} resultados)
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button
              v-for="page in getVisiblePages()"
              :key="page"
              @click="changePage(page)"
              :class="page === customerStore.pagination.page ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useCustomerStore } from '../../stores/customers'

const customerStore = useCustomerStore()

const filters = ref({
  search: '',
  type: '',
  active: '',
  page: 1
})

let searchTimeout = null

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.value.page = 1
    fetchCustomers()
  }, 500)
}

const fetchCustomers = async () => {
  await customerStore.fetchCustomers(filters.value)
}

const clearFilters = () => {
  filters.value = {
    search: '',
    type: '',
    active: '',
    page: 1
  }
  fetchCustomers()
}

const changePage = (page) => {
  if (page >= 1 && page <= customerStore.pagination.total_pages) {
    filters.value.page = page
    fetchCustomers()
  }
}

const deleteCustomer = async (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
    try {
      await customerStore.deleteCustomer(id)
      fetchCustomers()
    } catch (error) {
      alert('Error al eliminar el cliente')
    }
  }
}

const getTypeClass = (type) => {
  const classes = {
    regular: 'bg-gray-100 text-gray-800',
    vip: 'bg-yellow-100 text-yellow-800',
    wholesale: 'bg-blue-100 text-blue-800'
  }
  return classes[type] || 'bg-gray-100 text-gray-800'
}

const getTypeLabel = (type) => {
  const labels = {
    regular: 'Regular',
    vip: 'VIP',
    wholesale: 'Mayorista'
  }
  return labels[type] || type
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES')
}

const getVisiblePages = () => {
  const currentPage = customerStore.pagination.page
  const totalPages = customerStore.pagination.total_pages
  const pages = []
  
  for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
    pages.push(i)
  }
  
  return pages
}

onMounted(() => {
  fetchCustomers()
})
</script>