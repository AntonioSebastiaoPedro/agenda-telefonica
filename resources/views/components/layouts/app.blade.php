<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Agenda Telefónica' }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <h1 class="text-xl font-bold text-blue-600">Agenda App</h1>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                Dashboard
            </a>
            <!-- Link temporário até a rota ser criada na Fase 3/5 -->
            <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                Contatos
            </a>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header / Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center md:hidden">
                <h1 class="text-xl font-bold text-blue-600">Agenda App</h1>
            </div>
            <div class="ml-auto flex items-center">
                <!-- User dropdown (placeholder) -->
                <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    <span>Admin</span>
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                        A
                    </div>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
