<template>
  <div class="max-w-4xl mx-auto space-y-6">
    <!-- Sale Header -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            Venta #{{ sale.id }}
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            {{ formatDate(sale.created_at) }} • {{ formatTime(sale.created_at) }}
          </p>
        </div>
        <div class="flex items-center space-x-3">
          <span :class="getStatusClass(sale.status)" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
            {{ getStatusIcon(sale.status) }} {{ getStatusLabel(sale.status) }}
          </span>
          <div class="text-right">
            <p class="text-2xl font-bold text-gray-900">${{ sale.final_total.toFixed(2) }}</p>
            <p v-if="sale.discount > 0" class="text-sm text-gray-500">
              Descuento: ${{ sale.discount.toFixed(2) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Customer Information -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          👤 Información del Cliente
        </h3>
        
        <div v-if="sale.customer" class="space-y-3">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 h-12 w-12">
              <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                <span class="text-lg font-medium text-gray-700">
                  {{ getCustomerInitials(sale.customer) }}
                </span>
              </div>
            </div>
            <div>
              <p class="text-lg font-medium text-gray-900">
                {{ sale.customer.name }} {{ sale.customer.last_name }}
              </p>
              <span 
                :class="getCustomerTypeClass(sale.customer.type)"
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ getCustomerTypeLabel(sale.customer.type) }}
              </span>
            </div>
          </div>
          
          <div class="border-t border-gray-200 pt-3">
            <div class="space-y-2">
              <div class="flex items-center text-sm">
                <span class="text-gray-500 w-20">Email:</span>
                <a :href="`mailto:${sale.customer.email}`" class="text-blue-600 hover:text-blue-800">
                  {{ sale.customer.email }}
                </a>
              </div>
              <div class="flex items-center text-sm">
                <span class="text-gray-500 w-20">Teléfono:</span>
                <a :href="`tel:${sale.customer.phone}`" class="text-blue-600 hover:text-blue-800">
                  {{ sale.customer.phone }}
                </a>
              </div>
              <div class="flex items-start text-sm">
                <span class="text-gray-500 w-20">Dirección:</span>
                <span class="text-gray-900">{{ sale.customer.address }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="text-center text-gray-500 py-4">
          <p>⚠️ Cliente no encontrado</p>
        </div>
      </div>

      <!-- Sale Information -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          💰 Información de la Venta
        </h3>
        
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Estado:</span>
            <span :class="getStatusClass(sale.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
              {{ getStatusLabel(sale.status) }}
            </span>
          </div>
          
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Método de Pago:</span>
            <span class="text-gray-900">{{ getPaymentMethodLabel(sale.payment_method) }}</span>
          </div>
          
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Vendedor:</span>
            <span class="text-gray-900">{{ sale.user?.name || 'N/A' }}</span>
          </div>
          
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Fecha de Venta:</span>
            <span class="text-gray-900">{{ formatDate(sale.sale_date || sale.created_at) }}</span>
          </div>
          
          <div v-if="sale.notes" class="border-t border-gray-200 pt-3">
            <span class="text-gray-500 text-sm block mb-1">Notas:</span>
            <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ sale.notes }}</p>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          ⚡ Acciones Rápidas
        </h3>
        
        <div class="space-y-3">
          <button
            v-if="sale.status !== 'completed'"
            @click="$emit('edit-sale', sale.id)"
            class="w-full btn-primary"
          >
            ✏️ Editar Venta
          </button>
          
          <button
            @click="$emit('duplicate-sale', sale)"
            class="w-full btn-secondary"
          >
            📋 Duplicar Venta
          </button>
          
          <button
            @click="printSale"
            class="w-full btn-secondary"
          >
            🖨️ Imprimir Recibo
          </button>
          
          <div v-if="sale.status === 'pending'" class="space-y-2">
            <button
              @click="updateStatus('completed')"
              class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition"
            >
              ✅ Marcar como Completada
            </button>
            <button
              @click="updateStatus('cancelled')"
              class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition"
            >
              ❌ Cancelar Venta
            </button>
          </div>
          
          <div v-if="sale.status === 'cancelled'" class="p-3 bg-red-50 rounded-md">
            <p class="text-sm text-red-700 text-center">
              Esta venta ha sido cancelada
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Products Section -->
    <div class="bg-white shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">
        🛒 Productos Vendidos
      </h3>
      
      <div v-if="sale.items && sale.items.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Producto
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                SKU
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Precio Unit.
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Cantidad
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in sale.items" :key="item.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div v-if="item.product?.images && item.product.images.length > 0" class="flex-shrink-0 h-10 w-10">
                    <img 
                      :src="item.product.images[0]" 
                      :alt="item.product?.name"
                      class="h-10 w-10 rounded object-cover"
                    />
                  </div>
                  <div :class="item.product?.images && item.product.images.length > 0 ? 'ml-4' : ''">
                    <div class="text-sm font-medium text-gray-900">
                      {{ item.product?.name || 'Producto eliminado' }}
                    </div>
                    <div v-if="item.product?.category" class="text-sm text-gray-500">
                      {{ item.product.category.name }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.product?.sku || 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ item.unit_price.toFixed(2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ item.quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${{ item.total_price.toFixed(2) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-else class="text-center py-8 text-gray-500">
        <p>No hay productos en esta venta</p>
      </div>
    </div>

    <!-- Sale Summary -->
    <div class="bg-white shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">
        📊 Resumen de la Venta
      </h3>
      
      <div class="max-w-md ml-auto">
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
          </div>
          
          <div v-if="sale.discount > 0" class="flex justify-between text-sm">
            <span class="text-gray-600">Descuento:</span>
            <span class="font-medium text-red-600">-${{ sale.discount.toFixed(2) }}</span>
          </div>
          
          <div class="border-t border-gray-200 pt-3">
            <div class="flex justify-between text-lg font-bold">
              <span class="text-gray-900">Total:</span>
              <span class="text-blue-600">${{ sale.final_total.toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="text-xs text-gray-500 text-center">
            Pagado con {{ getPaymentMethodLabel(sale.payment_method) }}
          </div>
        </div>
      </div>
    </div>

    <!-- Sale History/Timeline -->
    <div v-if="saleHistory && saleHistory.length > 0" class="bg-white shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">
        📝 Historial de la Venta
      </h3>
      
      <div class="flow-root">
        <ul class="-mb-8">
          <li v-for="(event, eventIdx) in saleHistory" :key="eventIdx">
            <div class="relative pb-8">
              <span 
                v-if="eventIdx !== saleHistory.length - 1" 
                class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
              ></span>
              <div class="relative flex space-x-3">
                <div>
                  <span :class="getEventIconClass(event.type)" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                    {{ getEventIcon(event.type) }}
                  </span>
                </div>
                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                  <div>
                    <p class="text-sm text-gray-500">
                      {{ event.description }}
                      <span class="font-medium text-gray-900">{{ event.user }}</span>
                    </p>
                  </div>
                  <div class="text-right text-sm whitespace-nowrap text-gray-500">
                    {{ formatDate(event.created_at) }}
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Print Receipt Modal -->
    <div v-if="showPrintModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showPrintModal = false"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              🖨️ Imprimir Recibo
            </h3>
            
            <div class="space-y-3">
              <button
                @click="printReceipt('simple')"
                class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
              >
                <div class="font-medium">Recibo Simple</div>
                <div class="text-sm text-gray-500">Información básica de la venta</div>
              </button>
              
              <button
                @click="printReceipt('detailed')"
                class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
              >
                <div class="font-medium">Recibo Detallado</div>
                <div class="text-sm text-gray-500">Incluye detalles del cliente y productos</div>
              </button>
              
              <button
                @click="printReceipt('invoice')"
                class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
              >
                <div class="font-medium">Factura</div>
                <div class="text-sm text-gray-500">Formato de factura completa</div>
              </button>
            </div>
          </div>
          
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="showPrintModal = false"
              class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Props
const props = defineProps({
  sale: {
    type: Object,
    required: true
  }
})

// Emits
const emit = defineEmits(['edit-sale', 'duplicate-sale', 'status-updated', 'close'])

// Reactive data
const showPrintModal = ref(false)
const saleHistory = ref([])

// Computed properties
const subtotal = computed(() => {
  if (!props.sale.items) return 0
  return props.sale.items.reduce((sum, item) => sum + item.total_price, 0)
})

// Methods
const updateStatus = async (newStatus) => {
  const confirmMessage = newStatus === 'completed' 
    ? '¿Confirmar que la venta ha sido completada?'
    : '¿Estás seguro de cancelar esta venta?'
    
  if (!confirm(confirmMessage)) return
  
  try {
    const response = await window.axios.patch(`/api/sales/${props.sale.id}/status`, {
      status: newStatus
    })
    
    if (response.data.success) {
      emit('status-updated', response.data.data)
    }
  } catch (error) {
    console.error('Error updating sale status:', error)
    alert('Error al actualizar el estado de la venta')
  }
}

const printSale = () => {
  showPrintModal.value = true
}

const printReceipt = (type) => {
  showPrintModal.value = false
  
  // Create print content
  const printContent = generateReceiptContent(type)
  
  // Open print window
  const printWindow = window.open('', '_blank')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const generateReceiptContent = (type) => {
  const sale = props.sale
  const date = new Date(sale.created_at).toLocaleDateString('es-ES')
  
  let content = `
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>Recibo - Venta #${sale.id}</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .sale-info { margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f5f5f5; }
        .total-section { text-align: right; margin-top: 20px; }
        .total-line { margin: 5px 0; }
        .final-total { font-weight: bold; font-size: 1.2em; border-top: 2px solid #000; padding-top: 10px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>CRM OUTLET</h1>
        <p>Recibo de Venta #${sale.id}</p>
        <p>Fecha: ${date}</p>
      </div>
  `
  
  if (type === 'detailed' || type === 'invoice') {
    content += `
      <div class="sale-info">
        <h3>Información del Cliente</h3>
        <p><strong>Nombre:</strong> ${sale.customer?.name} ${sale.customer?.last_name}</p>
        <p><strong>Email:</strong> ${sale.customer?.email}</p>
        <p><strong>Teléfono:</strong> ${sale.customer?.phone}</p>
      </div>
    `
  }
  
  content += `
    <table class="items-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
  `
  
  sale.items?.forEach(item => {
    content += `
      <tr>
        <td>${item.product?.name || 'Producto'}</td>
        <td>${item.quantity}</td>
        <td>$${item.unit_price.toFixed(2)}</td>
        <td>$${item.total_price.toFixed(2)}</td>
      </tr>
    `
  })
  
  content += `
      </tbody>
    </table>
    
    <div class="total-section">
      <div class="total-line">Subtotal: $${subtotal.value.toFixed(2)}</div>
  `
  
  if (sale.discount > 0) {
    content += `<div class="total-line">Descuento: -$${sale.discount.toFixed(2)}</div>`
  }
  
  content += `
      <div class="total-line final-total">Total: $${sale.final_total.toFixed(2)}</div>
      <div class="total-line">Método de Pago: ${getPaymentMethodLabel(sale.payment_method)}</div>
    </div>
    
    <div style="text-align: center; margin-top: 30px; font-size: 0.9em; color: #666;">
      <p>¡Gracias por su compra!</p>
      <p>CRM Outlet - Sistema de Gestión</p>
    </div>
    
    </body>
    </html>
  `
  
  return content
}

const loadSaleHistory = async () => {
  try {
    const response = await window.axios.get(`/api/sales/${props.sale.id}/history`)
    if (response.data.success) {
      saleHistory.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading sale history:', error)
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

const getStatusIcon = (status) => {
  const icons = {
    pending: '⏳',
    completed: '✅',
    cancelled: '❌',
    draft: '📝'
  }
  return icons[status] || '❓'
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

const getEventIconClass = (type) => {
  const classes = {
    created: 'bg-blue-500',
    updated: 'bg-yellow-500',
    completed: 'bg-green-500',
    cancelled: 'bg-red-500'
  }
  return classes[type] || 'bg-gray-500'
}

const getEventIcon = (type) => {
  const icons = {
    created: '📝',
    updated: '✏️',
    completed: '✅',
    cancelled: '❌'
  }
  return icons[type] || '📄'
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
onMounted(() => {
  loadSaleHistory()
})
</script>

<style scoped>
.btn-primary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200;
}

.btn-secondary {
  @apply inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200;
}

/* Custom styles for receipt modal */
.receipt-option {
  @apply w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-150;
}

.receipt-option:hover {
  @apply border-blue-300 bg-blue-50;
}

/* Timeline styles */
.timeline-event {
  @apply relative pb-8;
}

.timeline-connector {
  @apply absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200;
}

.timeline-icon {
  @apply h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white text-white text-sm;
}

/* Product image styles */
.product-image {
  @apply h-10 w-10 rounded object-cover border border-gray-200;
}

/* Status badge animations */
.status-badge {
  @apply inline-flex px-3 py-1 text-sm font-semibold rounded-full transition-all duration-200;
}

/* Hover effects for action buttons */
.action-button {
  @apply transition-all duration-200 hover:scale-105;
}

/* Print modal styles */
.print-modal-backdrop {
  @apply fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity;
}

.print-modal-content {
  @apply inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all;
}

/* Summary section styles */
.summary-section {
  @apply max-w-md ml-auto;
}

.summary-line {
  @apply flex justify-between text-sm;
}

.summary-total {
  @apply flex justify-between text-lg font-bold border-t border-gray-200 pt-3;
}

/* Customer info card styles */
.customer-card {
  @apply space-y-3;
}

.customer-avatar {
  @apply flex-shrink-0 h-12 w-12;
}

.customer-details {
  @apply border-t border-gray-200 pt-3 space-y-2;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .detail-grid {
    @apply grid-cols-1;
  }
  
  .summary-section {
    @apply max-w-none ml-0;
  }
  
  .action-button {
    @apply w-full;
  }
}

/* Animation for content loading */
.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>