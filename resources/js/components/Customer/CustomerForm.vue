<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-xl font-semibold text-gray-900">
        {{ isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}
      </h1>
      <p class="mt-2 text-sm text-gray-700">
        {{ isEditing ? 'Actualiza la información del cliente.' : 'Completa el formulario para crear un nuevo cliente.' }}
      </p>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label for="last_name" class="block text-sm font-medium text-gray-700">Apellido</label>
              <input
                id="last_name"
                v-model="form.last_name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div class="sm:col-span-2">
              <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
              <textarea
                id="address"
                v-model="form.address"
                rows="3"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              ></textarea>
            </div>

            <div>
              <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Cliente</label>
              <select
                id="type"
                v-model="form.type"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecciona un tipo</option>
                <option value="regular">Regular</option>
                <option value="vip">VIP</option>
                <option value="wholesale">Mayorista</option>
              </select>
            </div>

            <div>
              <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
              <input
                id="birth_date"
                v-model="form.birth_date"
                type="date"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end space-x-3">
        <router-link
          to="/customers"
          class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          Cancelar
        </router-link>
        <button
          type="submit"
          :disabled="customerStore.loading"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ customerStore.loading ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Crear') }}
        </button>
      </div>

      <div v-if="error" class="text-red-600 text-sm">
        {{ error }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCustomerStore } from '../../stores/customers'

const route = useRoute()
const router = useRouter()
const customerStore = useCustomerStore()

const isEditing = computed(() => !!route.params.id)
const error = ref('')

const form = reactive({
  name: '',
  last_name: '',
  email: '',
  phone: '',
  address: '',
  type: '',
  birth_date: ''
})

const handleSubmit = async () => {
  error.value = ''
  try {
    if (isEditing.value) {
      await customerStore.updateCustomer(route.params.id, form)
    } else {
      await customerStore.createCustomer(form)
    }
    router.push('/customers')
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al guardar el cliente'
  }
}

onMounted(async () => {
  if (isEditing.value) {
    try {
      await customerStore.fetchCustomer(route.params.id)
      if (customerStore.customer) {
        form.name = customerStore.customer.name
        form.last_name = customerStore.customer.lastName
        form.email = customerStore.customer.contactInfo.email
        form.phone = customerStore.customer.contactInfo.phone
        form.address = customerStore.customer.contactInfo.address
        form.type = customerStore.customer.type.value
        form.birth_date = customerStore.customer.birthDate.split('T')[0]
      }
    } catch (error) {
      router.push('/customers')
    }
  }
})
</script>