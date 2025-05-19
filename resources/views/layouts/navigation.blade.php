<div x-data="{ sidebarOpen: false, darkMode: document.documentElement.classList.contains('dark') }" class="relative">

    <!-- Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between shadow z-30 h-20">
        <!-- Izquierda: botón hamburguesa -->
        <div class="flex items-center">
            <button @click="sidebarOpen = true"
                class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Centro: título -->
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <span class="text-xl font-semibold text-gray-700 dark:text-white">Aplicación de Clientes</span>
        </div>

        <!-- Derecha: iconos de control -->
        <div class="flex items-center space-x-6 pr-12">

            <!-- Modo claro/oscuro -->
            <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark')"
                class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none">
                <template x-if="darkMode">
                    <i class="fas fa-moon"></i>
                </template>
                <template x-if="!darkMode">
                    <i class="fas fa-sun"></i>
                </template>
            </button>

            <!-- Perfil -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none">
                    <i class="fas fa-user"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 py-2">

                    <!-- Nombre -->
                    <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 border-b dark:border-gray-700">
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                    </div>

                    <!-- Opciones -->
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Configuración -->
            <div class="relative" x-data="{ configOpen: false }">
                <button @click="configOpen = !configOpen"
                    class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none">
                    <i class="fas fa-wrench"></i>
                </button>

                <div x-show="configOpen" @click.away="configOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 py-2">

                    <button @click="$dispatch('open-modal', { id: 'modalClientes' }); configOpen = false"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Tabla Clientes
                    </button>


                    <button @click="$dispatch('open-modal', { id: 'modalAplicaciones' }); configOpen = false"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Tabla Aplicación
                    </button>

                    <button @click="$dispatch('open-modal', { id: 'modalCooperativas' }); configOpen = false"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Tabla Cooperativa
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 shadow-md z-50 transform" x-cloak
        @click.away="sidebarOpen = false">

        <div class="p-6 space-y-4">
            <a href="{{ route('dashboard') }}"
                class="block text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold">Dashboard</a>
            <a href="{{ route('clientes.index') }}"
                class="block text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold">Clientes</a>
        </div>
    </div>

    <!-- Overlay oscuro -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-30 z-40"
        @click="sidebarOpen = false" x-cloak></div>
</div>