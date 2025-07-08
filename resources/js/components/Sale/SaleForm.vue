<!-- resources/js/components/Sale/SaleForm.vue -->
<template>
  <div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">
            {{ isEditing ? 'Editar Venta' : 'Nueva Venta' }}
          </h1>
          <p class="mt-1 text-sm text-gray-500">
            {{ isEditing ? 'Modifica los detalles de la venta' : 'Crea una nueva venta seleccionando cliente y productos' }}
          </p>
        </div>
        <div v-if="sale.id" class="text-right">
          <p class="text-sm text-gray-500">Venta #{{ sale.id }}</p>
          <p class="text-xs text-gray-400">{{ formatDate(sale.created_at) }}</p>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Customer Selection -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          👤 Información del Cliente
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="customer" class="block text-sm font-medium text-gray-700">
              Cliente *
            </label>
            <select
              id="customer"
              v-model="form.customer_id"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              @change="onCustomerChange"
            >
              <option value="">Selecciona un cliente</option>
              <option 
                v-for="customer in customers" 
                :key="customer.id" 
                :value="customer.id"
              >
                {{ customer.name }} {{ customer.last_name }} - {{ customer.email }}
              </option>
            </select>
          </div>

          <div v-if="selectedCustomer">
            <label class="block text-sm font-medium text-gray-700">
              Información del Cliente
            </label>
            <div class="mt-1 p-3 bg-gray-50 rounded-md">
              <p class="text-sm font-medium text-gray-900">
                {{ selectedCustomer.name }} {{ selectedCustomer.last_name }}
              </p>
              <p class="text-sm text-gray-600">{{ selectedCustomer.email }}</p>
              <p class="text-sm text-gray-600">{{ selectedCustomer.phone }}</p>
              <span 
                :class="getCustomerTypeClass(selectedCustomer.type)"
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1"
              >
                {{ getCustomerTypeLabel(selectedCustomer.type) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Products Selection -->
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            🛒 Productos
          </h3>
          <button
            type="button"
            @click="addProductLine"
            class="btn-primary"
          >
            ➕ Agregar Producto
          </button>
        </div>

        <!-- Product Lines -->
        <div class="space-y-4">
          <div
            v-for="(item, index) in form.items"
            :key="index"
            class="p-4 border border-gray-200 rounded-lg"
          >
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
              <!-- Product Selection -->
              <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700">
                  Producto *
                </label>
                <select
                  v-model="item.product_id"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  @change="onProductChange(index)"
                >
                  <option value="">Selecciona un producto</option>
                  <option 
                    v-for="product in availableProducts" 
                    :key="product.id" 
                    :value="product.id"
                    :disabled="product.stock <= 0"
                  >
                    {{ product.name }} - ${{ product.price }} 
                    (Stock: {{ product.stock }})
                  </option>
                </select>
              </div>

              <!-- Quantity -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                  Cantidad *
                </label>
                <input
                  v-model.number="item.quantity"
                  type="number"
                  min="1"
                  :max="getProductStock(item.product_id)"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  @input="calculateItemTotal(index)"
                />
              </div>

              <!-- Unit Price -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                  Precio Unitario
                </label>
                <input
                  v-model.number="item.unit_price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  @input="calculateItemTotal(index)"
                />
              </div>

              <!-- Total -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                  Total
                </label>
                <div class="mt-1 p-2 bg-gray-50 rounded-md">
                  <span class="text-sm font-medium text-gray-900">
                    ${{ item.total_price.toFixed(2) }}
                  </span>
                </div>
              </div>

              <!-- Actions -->
              <div class="md:col-span-2">
                <button
                  type="button"
                  @click="removeProductLine(index)"
                  class="w-full btn-danger text-sm"
                  :disabled="form.items.length === 1"
                >
                  🗑️ Eliminar
                </button>
              </div>
            </div>

            <!-- Product Info -->
            <div v-if="item.product_id" class="mt-3 p-3 bg-blue-50 rounded-md">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-blue-900">
                    {{ getProductInfo(item.product_id)?.name }}
                  </p>
                  <p class="text-sm text-blue-700">
                    SKU: {{ getProductInfo(item.product_id)?.sku }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-sm text-blue-700">
                    Stock disponible: {{ getProductStock(item.product_id) }}
                  </p>
                  <p v-if="getProductInfo(item.product_id)?.discount_percentage" 
                     class="text-xs text-green-600">
                    Descuento: {{ getProductInfo(item.product_id).discount_percentage }}%
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- No Products Message -->
        <div v-if="form.items.length === 0" class="text-center py-8">
          <p class="text-gray-500">No hay productos agregados</p>
          <button
            type="button"
            @click="addProductLine"
            class="mt-2 btn-primary"
          >
            Agregar Primer Producto
          </button>
        </div>
      </div>

      <!-- Sale Details -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          💰 Detalles de la Venta
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Payment Method -->
          <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700">
              Método de Pago *
            </label>
            <select
              id="payment_method"
              v-model="form.payment_method"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
              <option value="">Selecciona método de pago</option>
              <option value="cash">💵 Efectivo</option>
              <option value="card">💳 Tarjeta</option>
              <option value="transfer">🏦 Transferencia</option>
            </select>
          </div>

          <!-- Discount -->
          <div>
            <label for="discount" class="block text-sm font-medium text-gray-700">
              Descuento Adicional
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="discount"
                v-model.number="form.discount"
                type="number"
                step="0.01"
                min="0"
                :max="subtotal"
                class="block w-full rounded-md border-gray-300 pr-12 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="0.00"
                @input="calculateTotals"
              />
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">$</span>
              </div>
            </div>
          </div>

          <!-- Notes -->
          <div class="md:col-span-2">
            <label for="notes" class="block text-sm font-medium text-gray-700">
              Notas
            </label>
            <textarea
              id="notes"
              v-model="form.notes"
              rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Notas adicionales sobre la venta..."
            ></textarea>
          </div>
        </div>

        <!-- Totals -->
        <div class="mt-6 border-t border-gray-200 pt-6">
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Subtotal:</span>
              <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
            </div>
            <div v-if="form.discount > 0" class="flex justify-between text-sm">
              <span class="text-gray-600">Descuento:</span>
              <span class="font-medium text-red-600">-${{ form.discount.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
              <span>Total:</span>
              <span class="text-blue-600">${{ total.toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
          <button
            type="button"
            @click="$emit('cancel')"
            class="btn-secondary"
          >
            Cancelar
          </button>
          
          <div class="flex space-x-3">
            <button
              v-if="!isEditing"
              type="button"
              @click="saveDraft"
              :disabled="loading"
              class="btn-secondary"
            >
              💾 Guardar Borrador
            </button>
            
            <button
              type="submit"
              :disabled="loading || !isFormValid"
              class="btn-primary"
            >
              <span v-if="loading" class="flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                {{ isEditing ? 'Actualizando...' : 'Creando...' }}
              </span>
              <span v-else>
                {{ isEditing ? '✅ Actualizar Venta' : '✅ Crear Venta' }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </form>

    <!-- Error Messages -->
    <div v-if="errors.length > 0" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <h4 class="text-sm font-medium text-red-800 mb-2">Errores de validación:</h4>
      <ul class="text-sm text-red-700 space-y-1">
        <li v-for="error in errors" :key="error">• {{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

// Props
const props = defineProps({
  saleId: {
    type: [Number, String],
    default: null
  }
})

// Emits
const emit = defineEmits(['saved', 'cancel'])

// Router
const router = useRouter()
const route = useRoute()

// Reactive data
const loading = ref(false)
const customers = ref([])
const products = ref([])
const errors = ref([])

const sale = ref({
  id: null,
  customer_id: null,
  items: [],
  payment_method: '',
  discount: 0,
  notes: '',
  status: 'pending',
  created_at: null
})

const form = ref({
  customer_id: '',
  items: [
    {
      product_id: '',
      quantity: 1,
      unit_price: 0,
      total_price: 0
    }
  ],
  payment_method: '',
  discount: 0,
  notes: ''
})

// Computed properties
const isEditing = computed(() => !!props.saleId)

const selectedCustomer = computed(() => {
  if (!form.value.customer_id) return null
  return customers.value.find(c => c.id == form.value.customer_id)
})

const availableProducts = computed(() => {
  return products.value.filter(p => p.is_active && p.stock > 0)
})

const subtotal = computed(() => {
  return form.value.items.reduce((sum, item) => sum + item.total_price, 0)
})

const total = computed(() => {
  return Math.max(0, subtotal.value - (form.value.discount || 0))
})

const isFormValid = computed(() => {
  return form.value.customer_id && 
         form.value.payment_method && 
         form.value.items.length > 0 &&
         form.value.items.every(item => item.product_id && item.quantity > 0) &&
         total.value > 0
})

// Methods
const loadCustomers = async () => {
  try {
    const response = await window.axios.get('/api/customers')
    if (response.data.success) {
      customers.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading customers:', error)
  }
}

const loadProducts = async () => {
  try {
    const response = await window.axios.get('/api/products')
    if (response.data.success) {
      products.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading products:', error)
  }
}

const loadSale = async () => {
  if (!props.saleId) return
  
  try {
    loading.value = true
    const response = await window.axios.get(`/api/sales/${props.saleId}`)
    if (response.data.success) {
      sale.value = response.data.data
      populateForm()
    }
  } catch (error) {
    console.error('Error loading sale:', error)
    errors.value.push('Error cargando la venta')
  } finally {
    loading.value = false
  }
}

const populateForm = () => {
  form.value = {
    customer_id: sale.value.customer_id,
    items: sale.value.items.map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
      unit_price: item.unit_price,
      total_price: item.total_price
    })),
    payment_method: sale.value.payment_method,
    discount: sale.value.discount || 0,
    notes: sale.value.notes || ''
  }
}

const addProductLine = () => {
  form.value.items.push({
    product_id: '',
    quantity: 1,
    unit_price: 0,
    total_price: 0
  })
}

const removeProductLine = (index) => {
  if (form.value.items.length > 1) {
    form.value.items.splice(index, 1)
  }
}

const onCustomerChange = () => {
  // Apply customer-specific discounts if any
  if (selectedCustomer.value?.type === 'vip') {
    // VIP customers get 5% discount
    form.value.discount = subtotal.value * 0.05
  } else if (selectedCustomer.value?.type === 'wholesale') {
    // Wholesale customers get 10% discount
    form.value.discount = subtotal.value * 0.10
  }
  calculateTotals()
}

const onProductChange = (index) => {
  const item = form.value.items[index]
  const product = getProductInfo(item.product_id)
  
  if (product) {
    item.unit_price = product.final_price || product.price
    calculateItemTotal(index)
  }
}

const calculateItemTotal = (index) => {
  const item = form.value.items[index]
  item.total_price = (item.quantity || 0) * (item.unit_price || 0)
  calculateTotals()
}

const calculateTotals = () => {
  // Reactive computed properties will update automatically
}

const getProductInfo = (productId) => {
  return products.value.find(p => p.id == productId)
}

const getProductStock = (productId) => {
  const product = getProductInfo(productId)
  return product ? product.stock : 0
}

const getCustomerTypeClass = (type) => {
  const classes = {
    regular: 'bg-gray-100 text-gray-800',
    vip: 'bg-yellow-100 text-yellow-800',
    wholesale: 'bg-blue-100 text-blue-800'
  }
  return classes[type] || 'bg-gray-100 text-gray-800'
}

const getCustomerTypeLabel = (type) => {
  const labels = {
    regular: 'Regular',
    vip: 'VIP',
    wholesale: 'Mayorista'
  }
  return labels[type] || type
}

const validateForm = () => {
  errors.value = []
  
  if (!form.value.customer_id) {
    errors.value.push('Debe seleccionar un cliente')
  }
  
  if (!form.value.payment_method) {
    errors.value.push('Debe seleccionar un método de pago')
  }
  
  if (form.value.items.length === 0) {
    errors.value.push('Debe agregar al menos un producto')
  }
  
  form.value.items.forEach((item, index) => {
    if (!item.product_id) {
      errors.value.push(`Producto ${index + 1}: Debe seleccionar un producto`)
    }
    
    if (!item.quantity || item.quantity <= 0) {
      errors.value.push(`Producto ${index + 1}: La cantidad debe ser mayor a 0`)
    }
    
    const stock = getProductStock(item.product_id)
    if (item.quantity > stock) {
      errors.value.push(`Producto ${index + 1}: Cantidad excede el stock disponible (${stock})`)
    }
  })
  
  if (total.value <= 0) {
    errors.value.push('El total de la venta debe ser mayor a 0')
  }
  
  return errors.value.length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) return
  
  loading.value = true
  
  try {
    const saleData = {
      customer_id: form.value.customer_id,
      items: form.value.items,
      payment_method: form.value.payment_method,
      discount: form.value.discount || 0,
      notes: form.value.notes,
      status: 'completed'
    }
    
    let response
    if (isEditing.value) {
      response = await window.axios.put(`/api/sales/${props.saleId}`, saleData)
    } else {
      response = await window.axios.post('/api/sales', saleData)
    }
    
    if (response.data.success) {
      emit('saved', response.data.data)
      // Redirect or show success message
    }
    
  } catch (error) {
    console.error('Error saving sale:', error)
    if (error.response?.data?.errors) {
      const serverErrors = error.response.data.errors
      Object.values(serverErrors).flat().forEach(err => {
        errors.value.push(err)
      })
    } else {
      errors.value.push('Error al guardar la venta')
    }
  } finally {
    loading.value = false
  }
}

const saveDraft = async () => {
  if (!form.value.customer_id) {
    errors.value = ['Debe seleccionar un cliente para guardar borrador']
    return
  }
  
  loading.value = true
  
  try {
    const draftData = {
      ...form.value,
      status: 'draft'
    }
    
    const response = await window.axios.post('/api/sales', draftData)
    
    if (response.data.success) {
      // Show success message
      console.log('Borrador guardado')
    }
    
  } catch (error) {
    console.error('Error saving draft:', error)
    errors.value.push('Error al guardar borrador')
  } finally {
    loading.value = false
  }
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('es-ES')
}

// Lifecycle
onMounted(async () => {
  await Promise.all([
    loadCustomers(),
    loadProducts()
  ])
  
  if (isEditing.value) {
    await loadSale()
  }
})

// Watchers
watch(() => selectedCustomer.value?.type, (newType) => {
  if (newType && subtotal.value > 0) {
    onCustomerChange()
  }
})
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

.btn-secondary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

.btn-danger {
  @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}
</style>