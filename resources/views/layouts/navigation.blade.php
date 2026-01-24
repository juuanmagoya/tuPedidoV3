<nav x-data="{ open: false }">

    <!-- TOPBAR MOBILE -->
    <header
        class="h-16 bg-gray-900 border-b border-gray-800 flex items-center px-4 sm:hidden fixed top-0 left-0 right-0 z-20">
        <button @click="open = true" class="text-yellow-400 focus:outline-none">
            <!-- Hamburger -->
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <span class="ml-4 font-semibold text-yellow-400">
            TuPedido
        </span>
    </header>

    <!-- OVERLAY MOBILE -->
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 sm:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full sm:translate-x-0'"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-gray-900 to-black text-yellow-400
               transform transition-transform duration-200 ease-in-out flex flex-col"
    >

        <!-- LOGO -->
        <div class="h-20 flex items-center justify-center border-b border-gray-800">
            <span class="text-xl font-bold tracking-wide">
                TuPedido
            </span>
        </div>

        <!-- MENU -->
        <div class="flex-1 px-4 py-6 space-y-6 text-sm">

            <!-- PRINCIPAL -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    PRINCIPAL
                </p>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded bg-yellow-500 text-black font-semibold">
                    <!-- Icon -->
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3z"/>
                    </svg>
                    Dashboard
                </a>
            </div>

            <!-- GESTIÓN -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    GESTIÓN
                </p>

                <nav class="space-y-2">
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                        Categorías
                    </a>

                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6"/>
                        </svg>
                        Productos
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1"/>
                        </svg>
                        Usuarios
                    </a>

                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6"/>
                        </svg>
                        Proveedores
                    </a>
                </nav>
            </div>

            <!-- TRANSACCIONES -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    TRANSACCIONES
                </p>

                <nav class="space-y-2">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h18l-2 13H5L3 3z"/>
                        </svg>
                        Compras
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v8m4-4H8"/>
                        </svg>
                        Pedidos
                    </a>
                </nav>
            </div>

        </div>

        <!-- USER -->
        <div class="border-t border-gray-800 p-4 text-sm">
            <p class="font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-xs text-yellow-500">Administrador</p>

            <a href="{{ route('profile.edit') }}" class="block mt-2 hover:underline">
                Perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="mt-2 text-red-500 hover:underline">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </aside>

</nav>
