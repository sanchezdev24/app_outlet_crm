<template>
  <div id="app">
    <div v-if="!isLoginPage" class="min-h-screen bg-gray-100">
      <Sidebar />
      <div class="lg:pl-72">
        <Navbar />
        <main class="py-10">
          <div class="px-4 sm:px-6 lg:px-8">
            <router-view />
          </div>
        </main>
      </div>
    </div>
    <div v-else class="min-h-screen bg-gray-50">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'
import Sidebar from './components/Layout/Sidebar.vue'
import Navbar from './components/Layout/Navbar.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const isLoginPage = computed(() => route.name === 'login')

onMounted(() => {
  // Check if user is authenticated
  const token = localStorage.getItem('auth_token')
  if (token) {
    authStore.setToken(token)
    authStore.fetchUser()
  }

  // Route guards
  router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
      next('/login')
    } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
      next('/')
    } else {
      next()
    }
  })
})
</script>