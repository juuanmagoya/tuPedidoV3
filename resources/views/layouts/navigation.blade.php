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
            Tu Pedido
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
           <img src="{{ asset('storage/LogoTuPedido.png') }}" 
                alt="Logo TuPedido" 
                class="ml-4 h-16 w-auto md:h-20 lg:h-24">
        </div>

        <!-- MENU -->
        <div class="flex-1 px-4 py-6 space-y-6 text-sm overflow-y-auto custom-scrollbar">

            <!-- PRINCIPAL -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    PRINCIPAL
                </p>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded bg-yellow-500 text-black font-semibold">
                    <!-- Dashboard Icon -->
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3z"/>
                    </svg>
                    Dashboard
                </a>
            </div>

            <!-- PRODUCCIÓN -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    PRODUCCIÓN
                </p>
                
                <nav class="space-y-2">
                    <!-- Insumos -->
                    <a href="{{ route('inputs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-8 5-8-5m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/>
                        </svg>
                        Insumos
                    </a>

                    <!-- Producción -->
                    <a href="{{ route('productions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.983 4.5a1.5 1.5 0 012.034 0l.42.378a1.5 1.5 0 001.957.07l.47-.383a1.5 1.5 0 012.121.185l.354.42a1.5 1.5 0 00.987.524l.546.07a1.5 1.5 0 011.3 1.716l-.07.546a1.5 1.5 0 00.524.987l.42.354a1.5 1.5 0 01.185 2.121l-.383.47a1.5 1.5 0 00.07 1.957l.378.42a1.5 1.5 0 010 2.034l-.378.42a1.5 1.5 0 00-.07 1.957l.383.47a1.5 1.5 0 01-.185 2.121l-.42.354a1.5 1.5 0 00-.524.987l-.07.546a1.5 1.5 0 01-1.716 1.3l-.546-.07a1.5 1.5 0 00-.987.524l-.354.42a1.5 1.5 0 01-2.121.185l-.47-.383a1.5 1.5 0 00-1.957.07l-.42.378a1.5 1.5 0 01-2.034 0l-.42-.378a1.5 1.5 0 00-1.957-.07l-.47.383a1.5 1.5 0 01-2.121-.185l-.354-.42a1.5 1.5 0 00-.987-.524l-.546-.07a1.5 1.5 0 01-1.3-1.716l.07-.546a1.5 1.5 0 00-.524-.987l-.42-.354a1.5 1.5 0 01-.185-2.121l.383-.47a1.5 1.5 0 00-.07-1.957l-.378-.42a1.5 1.5 0 010-2.034l.378-.42a1.5 1.5 0 00.07-1.957l-.383-.47a1.5 1.5 0 01.185-2.121l.42-.354a1.5 1.5 0 00.524-.987l.07-.546a1.5 1.5 0 011.716-1.3l.546.07a1.5 1.5 0 00.987-.524l.354-.42a1.5 1.5 0 012.121-.185l.47.383a1.5 1.5 0 001.957-.07l.42-.378z"/>
                        </svg>
                        Producción
                    </a>
                </nav>
            </div>

            <!-- GESTIÓN -->
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">
                    GESTIÓN
                </p>

                <nav class="space-y-2">
                    <!-- Categorías -->
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                        Categorías
                    </a>

                    <!-- Productos -->
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4v10l8 4 8-4V7z"/>
                        </svg>
                        Productos
                    </a>

                    <!-- Usuarios -->
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Usuarios
                    </a>

                    <!-- Proveedores -->
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
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
                    <!-- Compras -->
                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-6h6v6m4 4H5a2 2 0 01-2-2V5a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                        </svg>
                        Compras
                    </a>

                    <!-- Pedidos -->
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
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
