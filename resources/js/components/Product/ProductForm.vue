<template>
  <div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">
            {{ isEditing ? 'Editar Producto' : 'Nuevo Producto' }}
          </h1>
          <p class="mt-1 text-sm text-gray-500">
            {{ isEditing ? 'Modifica la información del producto' : 'Completa el formulario para agregar un nuevo producto al catálogo' }}
          </p>
        </div>
        <div v-if="product.id" class="text-right">
          <p class="text-sm text-gray-500">ID: #{{ product.id }}</p>
          <p class="text-xs text-gray-400">{{ formatDate(product.created_at) }}</p>
          <span 
            :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1"
          >
            {{ product.is_active ? 'Activo' : 'Inactivo' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Basic Information -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          📦 Información Básica
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700">
              Nombre del Producto *
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              maxlength="255"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Ej: Camiseta Nike Dri-FIT"
            />
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">
              Descripción *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Describe las características del producto..."
            ></textarea>
          </div>

          <div>
            <label for="sku" class="block text-sm font-medium text-gray-700">
              SKU (Código) *
            </label>
            <input
              id="sku"
              v-model="form.sku"
              type="text"
              required
              maxlength="100"
              :disabled="isEditing"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm disabled:bg-gray-100"
              placeholder="Ej: NK-SHIRT-001"
              @input="form.sku = form.sku.toUpperCase()"
            />
            <p class="mt-1 text-xs text-gray-500">
              {{ isEditing ? 'El SKU no se puede modificar' : 'Código único para identificar el producto' }}
            </p>
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">
              Estado
            </label>
            <select
              id="status"
              v-model="form.is_active"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
              <option :value="true">✅ Activo</option>
              <option :value="false">❌ Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Categories and Brand -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          🏷️ Categorización
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">
              Categoría *
            </label>
            <select
              id="category"
              v-model="form.category_id"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
              <option value="">Selecciona una categoría</option>
              <option 
                v-for="category in categories" 
                :key="category.id" 
                :value="category.id"
              >
                {{ category.parent_id ? '↳ ' : '' }}{{ category.name }}
              </option>
            </select>
          </div>

          <div>
            <label for="brand" class="block text-sm font-medium text-gray-700">
              Marca *
            </label>
            <select
              id="brand"
              v-model="form.brand_id"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
              <option value="">Selecciona una marca</option>
              <option 
                v-for="brand in brands" 
                :key="brand.id" 
                :value="brand.id"
              >
                {{ brand.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Category and Brand Info -->
        <div v-if="selectedCategory || selectedBrand" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-if="selectedCategory" class="p-3 bg-blue-50 rounded-md">
            <h4 class="text-sm font-medium text-blue-900">Categoría Seleccionada</h4>
            <p class="text-sm text-blue-700">{{ selectedCategory.name }}</p>
            <p class="text-xs text-blue-600">{{ selectedCategory.description }}</p>
          </div>
          
          <div v-if="selectedBrand" class="p-3 bg-green-50 rounded-md">
            <h4 class="text-sm font-medium text-green-900">Marca Seleccionada</h4>
            <p class="text-sm text-green-700">{{ selectedBrand.name }}</p>
            <p class="text-xs text-green-600">{{ selectedBrand.description }}</p>
          </div>
        </div>
      </div>

      <!-- Pricing and Stock -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          💰 Precio e Inventario
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label for="price" class="block text-sm font-medium text-gray-700">
              Precio Regular *
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">$</span>
              </div>
              <input
                id="price"
                v-model.number="form.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="block w-full pl-7 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="0.00"
                @input="calculateFinalPrice"
              />
            </div>
          </div>

          <div>
            <label for="stock" class="block text-sm font-medium text-gray-700">
              Stock Actual *
            </label>
            <input
              id="stock"
              v-model.number="form.stock"
              type="number"
              min="0"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="0"
            />
          </div>

          <div>
            <label for="min_stock" class="block text-sm font-medium text-gray-700">
              Stock Mínimo
            </label>
            <input
              id="min_stock"
              v-model.number="form.min_stock"
              type="number"
              min="0"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="0"
            />
            <p class="mt-1 text-xs text-gray-500">
              Alerta cuando el stock esté por debajo de este número
            </p>
          </div>
        </div>

        <!-- Stock Status -->
        <div v-if="form.stock !== null" class="mt-4">
          <div class="flex items-center space-x-4">
            <div 
              :class="stockStatus.class"
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ stockStatus.label }}
            </div>
            <span v-if="form.min_stock && form.stock <= form.min_stock" class="text-orange-600 text-sm">
              ⚠️ Stock bajo - Considerar reabastecimiento
            </span>
          </div>
        </div>
      </div>

      <!-- Discount Settings -->
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            🏷️ Configuración de Descuentos
          </h3>
          <label class="flex items-center">
            <input
              v-model="hasDiscount"
              type="checkbox"
              class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            <span class="ml-2 text-sm text-gray-700">Aplicar descuento</span>
          </label>
        </div>

        <div v-if="hasDiscount" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">
              Porcentaje de Descuento
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="discount_percentage"
                v-model.number="form.discount_percentage"
                type="number"
                step="0.01"
                min="0"
                max="100"
                class="block w-full pr-12 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="0.00"
                @input="calculateFinalPrice"
              />
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">%</span>
              </div>
            </div>
          </div>

          <div>
            <label for="discount_valid_until" class="block text-sm font-medium text-gray-700">
              Válido Hasta
            </label>
            <input
              id="discount_valid_until"
              v-model="form.discount_valid_until"
              type="datetime-local"
              :min="minDiscountDate"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            />
            <p class="mt-1 text-xs text-gray-500">
              Dejar vacío para descuento permanente
            </p>
          </div>
        </div>

        <!-- Price Preview -->
        <div v-if="form.price > 0" class="mt-4 p-4 bg-gray-50 rounded-md">
          <h4 class="text-sm font-medium text-gray-900 mb-2">Vista Previa de Precios</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
              <span class="text-gray-600">Precio Regular:</span>
              <span class="font-medium ml-2">${{ form.price.toFixed(2) }}</span>
            </div>
            <div v-if="hasDiscount && form.discount_percentage">
              <span class="text-gray-600">Descuento:</span>
              <span class="font-medium text-red-600 ml-2">-{{ form.discount_percentage }}%</span>
            </div>
            <div>
              <span class="text-gray-600">Precio Final:</span>
              <span class="font-bold text-green-600 ml-2">${{ finalPrice.toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Images -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          🖼️ Imágenes del Producto
        </h3>

        <!-- Image Upload Area -->
        <div class="mb-4">
          <div class="flex items-center justify-center w-full">
            <label for="image-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500">
                  <span class="font-semibold">Click para subir</span> o arrastra imágenes
                </p>
                <p class="text-xs text-gray-500">PNG, JPG, WEBP (MAX. 2MB)</p>
              </div>
              <input
                id="image-upload"
                type="file"
                multiple
                accept="image/*"
                class="hidden"
                @change="handleImageUpload"
              />
            </label>
          </div>
        </div>

        <!-- Current Images -->
        <div v-if="form.images && form.images.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="(image, index) in form.images"
            :key="index"
            class="relative group"
          >
            <img
              :src="image"
              :alt="`Imagen ${index + 1}`"
              class="w-full h-24 object-cover rounded-lg border border-gray-200"
            />
            <button
              type="button"
              @click="removeImage(index)"
              class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
            >
              ✕
            </button>
          </div>
        </div>

        <!-- Add Image URL -->
        <div class="mt-4">
          <label for="image_url" class="block text-sm font-medium text-gray-700">
            O agregar URL de imagen
          </label>
          <div class="mt-1 flex rounded-md shadow-sm">
            <input
              id="image_url"
              v-model="imageUrl"
              type="url"
              class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="https://ejemplo.com/imagen.jpg"
            />
            <button
              type="button"
              @click="addImageUrl"
              class="relative -ml-px inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100"
            >
              Agregar
            </button>
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
                {{ isEditing ? '✅ Actualizar Producto' : '✅ Crear Producto' }}
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

// Props
const props = defineProps({
  productId: {
    type: [Number, String],
    default: null
  }
})

// Emits
const emit = defineEmits(['saved', 'cancel'])

// Reactive data
const loading = ref(false)
const categories = ref([])
const brands = ref([])
const errors = ref([])
const imageUrl = ref('')
const hasDiscount = ref(false)

const product = ref({
  id: null,
  name: '',
  description: '',
  sku: '',
  price: 0,
  stock: 0,
  category_id: null,
  brand_id: null,
  images: [],
  is_active: true,
  created_at: null
})

const form = ref({
  name: '',
  description: '',
  sku: '',
  price: null,
  stock: null,
  min_stock: 0,
  category_id: '',
  brand_id: '',
  images: [],
  discount_percentage: null,
  discount_valid_until: '',
  is_active: true
})

// Computed properties
const isEditing = computed(() => !!props.productId)

const selectedCategory = computed(() => {
  if (!form.value.category_id) return null
  return categories.value.find(c => c.id == form.value.category_id)
})

const selectedBrand = computed(() => {
  if (!form.value.brand_id) return null
  return brands.value.find(b => b.id == form.value.brand_id)
})

const stockStatus = computed(() => {
  const stock = form.value.stock
  const minStock = form.value.min_stock || 0
  
  if (stock === null || stock === undefined) {
    return { label: 'Sin definir', class: 'bg-gray-100 text-gray-800' }
  }
  
  if (stock === 0) {
    return { label: 'Agotado', class: 'bg-red-100 text-red-800' }
  }
  
  if (stock <= minStock) {
    return { label: 'Stock Bajo', class: 'bg-orange-100 text-orange-800' }
  }
  
  return { label: 'En Stock', class: 'bg-green-100 text-green-800' }
})

const finalPrice = computed(() => {
  if (!form.value.price) return 0
  
  if (hasDiscount.value && form.value.discount_percentage) {
    return form.value.price * (1 - form.value.discount_percentage / 100)
  }
  
  return form.value.price
})

const minDiscountDate = computed(() => {
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  return tomorrow.toISOString().slice(0, 16)
})

const isFormValid = computed(() => {
  return form.value.name && 
         form.value.description && 
         form.value.sku && 
         form.value.price > 0 && 
         form.value.stock >= 0 && 
         form.value.category_id && 
         form.value.brand_id
})

// Methods
const loadCategories = async () => {
  try {
    const response = await window.axios.get('/api/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading categories:', error)
  }
}

const loadBrands = async () => {
  try {
    const response = await window.axios.get('/api/brands')
    if (response.data.success) {
      brands.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading brands:', error)
  }
}

const loadProduct = async () => {
  if (!props.productId) return
  
  try {
    loading.value = true
    const response = await window.axios.get(`/api/products/${props.productId}`)
    if (response.data.success) {
      product.value = response.data.data
      populateForm()
    }
  } catch (error) {
    console.error('Error loading product:', error)
    errors.value.push('Error cargando el producto')
  } finally {
    loading.value = false
  }
}

const populateForm = () => {
  form.value = {
    name: product.value.name,
    description: product.value.description,
    sku: product.value.sku,
    price: product.value.price,
    stock: product.value.stock,
    min_stock: product.value.min_stock || 0,
    category_id: product.value.category_id,
    brand_id: product.value.brand_id,
    images: product.value.images || [],
    discount_percentage: product.value.discount_percentage,
    discount_valid_until: product.value.discount_valid_until 
      ? new Date(product.value.discount_valid_until).toISOString().slice(0, 16)
      : '',
    is_active: product.value.is_active
  }
  
  hasDiscount.value = !!product.value.discount_percentage
}

const calculateFinalPrice = () => {
  // This will be reactive through computed property
}

const handleImageUpload = (event) => {
  const files = Array.from(event.target.files)
  
  files.forEach(file => {
    if (file.size > 2 * 1024 * 1024) { // 2MB
      errors.value.push(`Imagen ${file.name} es muy grande (máximo 2MB)`)
      return
    }
    
    const reader = new FileReader()
    reader.onload = (e) => {
      form.value.images.push(e.target.result)
    }
    reader.readAsDataURL(file)
  })
  
  // Clear input
  event.target.value = ''
}

const addImageUrl = () => {
  if (imageUrl.value && isValidUrl(imageUrl.value)) {
    form.value.images.push(imageUrl.value)
    imageUrl.value = ''
  } else {
    errors.value.push('URL de imagen no válida')
  }
}

const removeImage = (index) => {
  form.value.images.splice(index, 1)
}

const isValidUrl = (string) => {
  try {
    new URL(string)
    return true
  } catch (_) {
    return false
  }
}

const validateForm = () => {
  errors.value = []
  
  if (!form.value.name.trim()) {
    errors.value.push('El nombre es obligatorio')
  }
  
  if (!form.value.description.trim()) {
    errors.value.push('La descripción es obligatoria')
  }
  
  if (!form.value.sku.trim()) {
    errors.value.push('El SKU es obligatorio')
  }
  
  if (!form.value.price || form.value.price <= 0) {
    errors.value.push('El precio debe ser mayor a 0')
  }
  
  if (form.value.stock < 0) {
    errors.value.push('El stock no puede ser negativo')
  }
  
  if (!form.value.category_id) {
    errors.value.push('Debe seleccionar una categoría')
  }
  
  if (!form.value.brand_id) {
    errors.value.push('Debe seleccionar una marca')
  }
  
  if (hasDiscount.value) {
    if (!form.value.discount_percentage || form.value.discount_percentage < 0 || form.value.discount_percentage > 100) {
      errors.value.push('El porcentaje de descuento debe estar entre 0 y 100')
    }
  }
  
  return errors.value.length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) return
  
  loading.value = true
  
  try {
    const productData = {
      ...form.value,
      discount_percentage: hasDiscount.value ? form.value.discount_percentage : null,
      discount_valid_until: hasDiscount.value && form.value.discount_valid_until 
        ? form.value.discount_valid_until 
        : null
    }
    
    let response
    if (isEditing.value) {
      response = await window.axios.put(`/api/products/${props.productId}`, productData)
    } else {
      response = await window.axios.post('/api/products', productData)
    }
    
    if (response.data.success) {
      emit('saved', response.data.data)
    }
    
  } catch (error) {
    console.error('Error saving product:', error)
    if (error.response?.data?.errors) {
      const serverErrors = error.response.data.errors
      Object.values(serverErrors).flat().forEach(err => {
        errors.value.push(err)
      })
    } else {
      errors.value.push('Error al guardar el producto')
    }
  } finally {
    loading.value = false
  }
}

const saveDraft = async () => {
  loading.value = true
  
  try {
    const draftData = {
      ...form.value,
      is_active: false // Drafts are inactive by default
    }
    
    const response = await window.axios.post('/api/products', draftData)
    
    if (response.data.success) {
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
    loadCategories(),
    loadBrands()
  ])
  
  if (isEditing.value) {
    await loadProduct()
  }
})

// Watchers
watch(hasDiscount, (newValue) => {
  if (!newValue) {
    form.value.discount_percentage = null
    form.value.discount_valid_until = ''
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

/* Image upload hover effects - SIN @apply group */
.image-item {
  position: relative;
}

.image-item:hover .image-remove {
  opacity: 1;
}

/* Custom file input styling */
input[type="file"] {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* Price preview styling */
.price-preview {
  @apply bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200;
}

/* Form sections */
.form-section {
  @apply bg-white shadow rounded-lg p-6;
}

.form-section h3 {
  @apply text-lg font-medium text-gray-900 mb-4;
}

/* Status indicators */
.status-indicator {
  @apply inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full;
}

.status-active {
  @apply bg-green-100 text-green-800;
}

.status-inactive {
  @apply bg-red-100 text-red-800;
}

.status-draft {
  @apply bg-yellow-100 text-yellow-800;
}

/* Stock status styling */
.stock-high {
  @apply bg-green-100 text-green-800;
}

.stock-medium {
  @apply bg-yellow-100 text-yellow-800;
}

.stock-low {
  @apply bg-orange-100 text-orange-800;
}

.stock-empty {
  @apply bg-red-100 text-red-800;
}

/* Image grid - SIN usar @apply con group */
.image-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

@media (min-width: 768px) {
  .image-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.image-item {
  position: relative;
}

.image-item img {
  width: 100%;
  height: 6rem;
  object-fit: cover;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  transition: transform 0.2s ease-in-out;
}

.image-item:hover img {
  transform: scale(1.05);
}

.image-remove {
  position: absolute;
  top: -0.5rem;
  right: -0.5rem;
  background-color: #ef4444;
  color: white;
  border-radius: 50%;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  opacity: 0;
  transition: opacity 0.2s ease-in-out;
  cursor: pointer;
}

.image-remove:hover {
  background-color: #dc2626;
}

/* Form validation */
.field-error {
  @apply border-red-300 focus:border-red-500 focus:ring-red-500;
}

.error-message {
  @apply text-red-600 text-sm mt-1;
}

/* Loading states */
.loading-spinner {
  @apply animate-spin rounded-full h-4 w-4 border-b-2 border-white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .form-section {
    padding: 1rem;
  }
  
  .image-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Form inputs focus states */
input:focus,
select:focus,
textarea:focus {
  @apply outline-none ring-2 ring-blue-500 ring-opacity-50 border-blue-500;
}

/* Discount toggle animation */
.discount-toggle {
  transition: all 0.3s ease-in-out;
}

/* Image upload area */
.upload-area {
  @apply flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100;
}

.upload-area svg {
  @apply w-8 h-8 mb-4 text-gray-500;
}

/* Product preview card */
.product-preview {
  @apply bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-lg p-4;
}

/* Category and brand info cards */
.info-card {
  @apply p-3 rounded-md;
}

.info-card.category {
  @apply bg-blue-50;
}

.info-card.brand {
  @apply bg-green-50;
}

.info-card h4 {
  @apply text-sm font-medium mb-1;
}

.info-card.category h4 {
  @apply text-blue-900;
}

.info-card.brand h4 {
  @apply text-green-900;
}

.info-card p {
  @apply text-sm;
}

.info-card.category p {
  @apply text-blue-700;
}

.info-card.brand p {
  @apply text-green-700;
}

.info-card .description {
  @apply text-xs;
}

.info-card.category .description {
  @apply text-blue-600;
}

.info-card.brand .description {
  @apply text-green-600;
}

/* Stock indicator animations */
.stock-indicator {
  @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-all duration-200;
}

.stock-indicator.in-stock {
  @apply bg-green-100 text-green-800;
}

.stock-indicator.low-stock {
  @apply bg-orange-100 text-orange-800;
}

.stock-indicator.out-of-stock {
  @apply bg-red-100 text-red-800;
}

/* Price display */
.price-display {
  @apply bg-gray-50 rounded-md p-4;
}

.price-row {
  @apply flex justify-between items-center text-sm;
}

.price-row.regular {
  @apply text-gray-600;
}

.price-row.discount {
  @apply text-red-600;
}

.price-row.final {
  @apply text-green-600 font-bold text-lg border-t border-gray-200 pt-2 mt-2;
}

/* Form section animations */
.form-section {
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Checkbox styling */
.checkbox-container {
  @apply flex items-center;
}

.checkbox-container input[type="checkbox"] {
  @apply rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500;
}

.checkbox-container label {
  @apply ml-2 text-sm text-gray-700;
}

/* Error container */
.error-container {
  @apply bg-red-50 border border-red-200 rounded-lg p-4;
}

.error-container h4 {
  @apply text-sm font-medium text-red-800 mb-2;
}

.error-container ul {
  @apply text-sm text-red-700 space-y-1;
}

/* Success states */
.success-indicator {
  @apply bg-green-50 border border-green-200 rounded-lg p-4;
}

.success-indicator h4 {
  @apply text-sm font-medium text-green-800 mb-2;
}

.success-indicator p {
  @apply text-sm text-green-700;
}
</style>
