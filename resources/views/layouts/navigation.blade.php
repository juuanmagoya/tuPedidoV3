@php
    use App\Models\User;
    $user = Auth::user();
@endphp

<nav x-data="{ open: false }">

    <!-- TOPBAR MOBILE -->
    <header
        class="h-16 bg-gray-900 border-b border-gray-800 flex items-center px-4 sm:hidden fixed top-0 left-0 right-0 z-20">
        <button @click="open = true" class="text-yellow-400 focus:outline-none">
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
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">PRINCIPAL</p>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded bg-yellow-500 text-black font-semibold">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H3z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('ai-assistant.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Asistente IA
                    
                </a>
            </div>

            {{-- ===================== PRODUCCIÓN ===================== --}}
            @if(in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PRODUCTION]))
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">PRODUCCIÓN</p>

                <nav class="space-y-2">
                    <a href="{{ route('inputs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Insumos</span>
                    </a>

                    <a href="{{ route('productions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Producción</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Categorías</span>
                    </a>

                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Productos</span>
                    </a>
                </nav>
            </div>
            @endif

            {{-- ===================== COMPRAS ===================== --}}
            @if(in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PURCHASE]))
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">COMPRAS</p>

                <nav class="space-y-2">
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Proveedores</span>
                    </a>

                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Compras</span>
                    </a>

                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Pedidos</span>
                    </a>

                    <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Ventas</span>
                    </a>
                </nav>
            </div>
            @endif

            {{-- ===================== ADMIN ===================== --}}
            @if($user->role === User::ROLE_ADMIN)
            <div>
                <p class="text-xs text-yellow-500 mb-2 tracking-wider">ADMINISTRACIÓN</p>

                <nav class="space-y-2">
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Usuarios</span>
                    </a>
                </nav>
                <nav class="space-y-2">
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                        <span>Clientes</span>
                    </a>
                </nav>
            </div>
            @endif

        </div>

        <!-- USER -->
        <div class="border-t border-gray-800 p-4 text-sm">
            <p class="font-semibold">{{ $user->name }}</p>

            <p class="text-xs text-yellow-500 capitalize">
                {{ str_replace('_', ' ', $user->role) }}
            </p>

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
