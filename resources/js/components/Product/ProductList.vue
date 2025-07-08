<template>
  <div class="space-y-6">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Productos</h1>
        <p class="mt-2 text-sm text-gray-700">
          Catálogo de productos disponibles en el outlet.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          to="/products/create"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
        >
          Nuevo Producto
        </router-link>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
        <div>
          <label class="block text-sm font-medium text-gray-700">Buscar</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Nombre, SKU..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @input="debouncedSearch"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Categoría</label>
          <select
            v-model="filters.category_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @change="fetchProducts"
          >
            <option value="">Todas</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Marca</label>
          <select
            v-model="filters.brand_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @change="fetchProducts"
          >
            <option value="">Todas</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.id">
              {{ brand.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Stock</label>
          <select
            v-model="filters.in_stock"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @change="fetchProducts"
          >
            <option value="">Todos</option>
            <option value="true">Con Stock</option>
            <option value="false">Sin Stock</option>
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

    <!-- Products Grid -->
    <div v-if="productStore.loading" class="text-center py-4">
      <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-500"></div>
    </div>
    
    <div v-else-if="productStore.products.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No hay productos</h3>
      <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo producto.</p>
    </div>

    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div
        v-for="product in productStore.products"
        :key="product.id"
        class="bg-white overflow-hidden shadow rounded-lg"
      >
        <div class="aspect-w-1 aspect-h-1 bg-gray-200">
          <img
            v-if="product.images && product.images.length > 0"
            :src="product.images[0]"
            :alt="product.name"
            class="w-full h-48 object-cover"
          />
          <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center">
            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        <div class="p-4">
          <h3 class="text-lg font-medium text-gray-900 truncate">
            {{ product.name }}
          </h3>
          <p class="text-sm text-gray-500 mt-1">
            SKU: {{ product.sku }}
          </p>
          <div class="mt-2 flex items-center justify-between">
            <div>
              <span class="text-lg font-bold text-gray-900">
                ${{ product.finalPrice }}
              </span>
              <span v-if="product.discount" class="text-sm text-gray-500 line-through ml-2">
                ${{ product.price.value }}
              </span>
            </div>
            <span :class="product.isInStock ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
              Stock: {{ product.stock.quantity }}
            </span>
          </div>
          <div class="mt-4 flex space-x-2">
            <router-link
              :to="`/products/${product.id}/edit`"
              class="flex-1 bg-indigo-600 text-white text-center py-2 px-3 rounded-md text-sm font-medium hover:bg-indigo-700"
            >
              Editar
            </router-link>
            <button
              @click="deleteProduct(product.id)"
              class="flex-1 bg-red-600 text-white py-2 px-3 rounded-md text-sm font-medium hover:bg-red-700"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductStore } from '../../stores/products'
import axios from 'axios'

const productStore = useProductStore()

const filters = ref({
  search: '',
  category_id: '',
  brand_id: '',
  in_stock: '',
  page: 1
})

const categories = ref([])
const brands = ref([])

let searchTimeout = null

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.value.page = 1
    fetchProducts()
  }, 500)
}

const fetchProducts = async () => {
  await productStore.fetchProducts(filters.value)
}

const clearFilters = () => {
  filters.value = {
    search: '',
    category_id: '',
    brand_id: '',
    in_stock: '',
    page: 1
  }
  fetchProducts()
}

const deleteProduct = async (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
    try {
      await productStore.deleteProduct(id)
      fetchProducts()
    } catch (error) {
      alert('Error al eliminar el producto')
    }
  }
}

const fetchCategoriesAndBrands = async () => {
  try {
    const [categoriesResponse, brandsResponse] = await Promise.all([
      axios.get('/categories'),
      axios.get('/brands')
    ])
    categories.value = categoriesResponse.data.data
    brands.value = brandsResponse.data.data
  } catch (error) {
    console.error('Error loading categories and brands:', error)
  }
}

onMounted(() => {
  fetchProducts()
  fetchCategoriesAndBrands()
})
</script>