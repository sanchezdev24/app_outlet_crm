<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'CRM Outlet') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Sistema CRM para gestión de tienda outlet. Administra clientes, productos y ventas de manera eficiente.">
    <meta name="keywords" content="CRM, outlet, gestión, ventas, clientes, productos">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        <!-- Loading inicial mientras Vue se monta -->
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">CRM Outlet</h1>
                <p class="text-gray-600">Cargando sistema...</p>
            </div>
        </div>
    </div>

    <!-- Scripts adicionales para debugging -->
    <script>
        // Debug info
        console.log('🚀 CRM Outlet iniciando...');
        console.log('📍 URL actual:', window.location.href);
        console.log('🔑 Token guardado:', !!localStorage.getItem('auth_token'));
        
        // Handle errors globalmente
        window.addEventListener('error', function(e) {
            console.error('❌ Error global:', e.error);
        });

        // Handle unhandled promise rejections
        window.addEventListener('unhandledrejection', function(e) {
            console.error('❌ Promise rejection:', e.reason);
        });

        // Verificar que Vue se está cargando
        setTimeout(() => {
            const app = document.getElementById('app');
            if (app && app.innerHTML.includes('Cargando sistema')) {
                console.warn('⚠️ Vue no se ha montado después de 3 segundos');
                console.log('📝 Verifica que npm run dev esté corriendo');
                console.log('📝 Verifica la consola del navegador para errores');
            }
        }, 3000);
    </script>
</body>
</html>