@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="space-y-10 pb-32">

    <!-- =========================================================
         HEADER
         ========================================================= -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mt-10 gap-4">

        <div>
            <h1 class="text-2xl font-semibold text-white">Productos</h1>
            <p class="text-sm text-gray-400">
                Gestión de productos del sistema
            </p>
        </div>

        <a href="{{ route('products.create') }}"
           class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
                  text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nuevo producto
        </a>
    </div>

    <!-- =========================================================
         ALERTA DE BAJO STOCK
         ========================================================= -->
    @if($lowStockProducts->isNotEmpty())
        <div x-data="{ open: true }" x-show="open"
             class="bg-red-600/10 border border-red-500/30 rounded-xl p-4 flex items-start gap-4">

            <div class="flex-1">
                @include('products._low_stock_alert', ['products' => $lowStockProducts])
            </div>

            <button @click="open = false"
                    class="text-red-400 hover:text-red-300 font-bold">
                ✕
            </button>
        </div>
    @endif

    <!-- =========================================================
         BUSCADOR / FILTROS
         ========================================================= -->
    <form method="GET" action="{{ route('products.index') }}"
          class="flex flex-wrap gap-4 mb-6">

        <input
            type="text"
            name="name"
            value="{{ request('name') }}"
            placeholder="Buscar por nombre"
            class="px-4 py-2 rounded-lg bg-[#111827] text-white border border-[#1F2933]"
        >

        <select name="status"
                class="px-4 py-2 rounded-lg bg-[#111827] text-white border border-[#1F2933]">
            <option value="">Todos los estados</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
        </select>

        <select name="category_id"
                class="px-4 py-2 rounded-lg bg-[#111827] text-white border border-[#1F2933]">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-4 py-2 rounded-lg font-semibold">
            Filtrar
        </button>

        @if(request()->hasAny(['name', 'status', 'category_id']))
            <a href="{{ route('products.index') }}"
               class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold">
                Eliminar filtros
            </a>
        @endif
    </form>

    <!-- =========================================================
         TABLA
         ========================================================= -->
    <div class="bg-[#111827] border border-[#1F2933] rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Imagen</th>
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Categoría</th>
                    <th class="px-6 py-4 text-right">Precio</th>
                    <th class="px-6 py-4 text-right">Stock</th>
                    <th class="px-6 py-4 text-center">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">

                @forelse($products as $product)
                    <tr class="hover:bg-[#0B1220] transition">
                        <td class="px-6 py-4">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     class="h-12 w-12 object-cover rounded-lg">
                            @else
                                <div class="h-12 w-12 bg-gray-700 rounded-lg
                                            flex items-center justify-center text-gray-400">—</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-white font-medium">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-right text-white">${{ number_format($product->price, 2) }}</td>

                        <td class="px-6 py-4 text-right">
                            <span class="{{ $product->stock <= $product->min_stock ? 'text-red-400 font-semibold' : 'text-green-400' }}">
                                {{ $product->stock }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $product->status ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                {{ $product->status ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">

                                <!-- Editar -->
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="flex items-center gap-1 text-[#F59E0B] hover:text-[#FBBF24] text-sm transition"
                                    title="Editar"
                                >
                                    <!-- Ícono lápiz -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5h2m-1-1v2m-6.414 6.414
                                            a2 2 0 010-2.828l7.07-7.07
                                            a2 2 0 012.828 0l2.828 2.828
                                            a2 2 0 010 2.828l-7.07 7.07
                                            a2 2 0 01-2.828 0l-1.414-1.414z"/>
                                    </svg>
                                    Editar
                                </a>

                                <!-- Eliminar -->
                                <div x-data>
                                    <form
                                        x-ref="deleteForm{{ $product->id }}"
                                        method="POST"
                                        action="{{ route('products.destroy', $product) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            @click="$store.modal.show({
                                                title: 'Eliminar producto',
                                                message: '¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.',
                                                onConfirm: () => $refs.deleteForm{{ $product->id }}.submit()
                                            })"
                                            class="flex items-center gap-1 text-red-500 hover:text-red-400 text-sm transition"
                                            title="Eliminar"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142
                                                    A2 2 0 0116.138 21H7.862
                                                    a2 2 0 01-1.995-1.858L5 7
                                                    m5 4v6m4-6v6
                                                    M9 7h6m2 0H7
                                                    m2-3h6a1 1 0 011 1v1H8V5
                                                    a1 1 0 011-1z"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </td>

                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            No hay productos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- =========================================================
         PAGINACIÓN
         ========================================================= -->
    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
