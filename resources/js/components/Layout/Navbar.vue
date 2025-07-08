<template>
  <div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <h1 class="text-xl font-semibold text-gray-900">
            {{ $route.name }}
          </h1>
        </div>
        
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-700">{{ authStore.user?.name }}</span>
            <button
              @click="handleLogout"
              class="bg-gray-800 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700"
            >
              Cerrar Sesión
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>