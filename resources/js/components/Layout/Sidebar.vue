<template>
  <div class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 overflow-y-auto">
    <div class="flex items-center justify-center h-16 bg-gray-800">
      <h1 class="text-white text-xl font-bold">CRM Outlet</h1>
    </div>
    
    <nav class="mt-8">
      <div class="px-4 space-y-2">
        <router-link
          to="/"
          class="bg-gray-800 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
          active-class="bg-gray-700"
        >
          <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
          </svg>
          Dashboard
        </router-link>

        <router-link
          to="/customers"
          class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
          active-class="bg-gray-700 text-white"
        >
          <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
          </svg>
          Clientes
        </router-link>

        <router-link
          to="/products"
          class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
          active-class="bg-gray-700 text-white"
        >
          <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          Productos
        </router-link>

        <router-link
          to="/sales"
          class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"
          active-class="bg-gray-700 text-white"
        >
          <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          Ventas
        </router-link>
      </div>
    </nav>
  </div>
</template>