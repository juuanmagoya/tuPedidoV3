{{-- 
|--------------------------------------------------------------------------
| Modal de confirmación global
|--------------------------------------------------------------------------
|
| Este modal se utiliza para confirmar acciones críticas del sistema:
| - Crear registros
| - Actualizar registros
| - Eliminar registros
|
| Está controlado por un Alpine Store global ($store.modal)
| y puede ser invocado desde cualquier vista.
|
| NO está acoplado a formularios ni rutas específicas.
|
--}}

<div
    {{-- 
        x-show controla la visibilidad del modal según el estado global.
        Cuando $store.modal.open === true, el modal se muestra.
    --}}
    x-show="$store.modal.open"

    {{-- 
        x-cloak evita que el modal se muestre brevemente
        al cargar la página antes de que Alpine inicialice.
    --}}
    x-cloak

    {{-- 
        Modal fijo que cubre toda la pantalla.
        z-50 asegura que esté por encima de todo.
    --}}
    class="fixed inset-0 z-50 flex items-center justify-center"
>

    {{-- 
    |--------------------------------------------------------------------------
    | Overlay (fondo oscuro)
    |--------------------------------------------------------------------------
    |
    | Oscurece la pantalla y bloquea la interacción con el fondo.
    | Al hacer click, cierra el modal.
    |
    --}}
    <div
        class="absolute inset-0 bg-black bg-opacity-70"
        @click="$store.modal.close()"
    ></div>

    {{-- 
    |--------------------------------------------------------------------------
    | Contenedor principal del modal
    |--------------------------------------------------------------------------
    |
    | Diseño dark UI consistente con el panel.
    | max-w-md limita el ancho para mantener buena UX.
    |
    --}}
    <div class="relative bg-[#111827] border border-[#1F2933] rounded-2xl w-full max-w-md p-6 shadow-xl">

        {{-- 
            Título dinámico del modal.
            Se define desde la vista que lo invoca.
        --}}
        <h2
            class="text-xl font-semibold text-white"
            x-text="$store.modal.title"
        ></h2>

        {{-- 
            Mensaje descriptivo del modal.
            Explica la acción que el usuario está por confirmar.
        --}}
        <p
            class="text-sm text-gray-400 mt-2"
            x-text="$store.modal.message"
        ></p>

        {{-- 
        |--------------------------------------------------------------------------
        | Acciones del modal
        |--------------------------------------------------------------------------
        |
        | Cancelar: cierra el modal sin ejecutar nada.
        | Confirmar: ejecuta la función onConfirm definida dinámicamente.
        |
        --}}
        <div class="flex justify-end gap-3 mt-6">

            {{-- Botón Cancelar --}}
            <button
                type="button"
                @click="$store.modal.close()"
                class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300 hover:bg-[#0B1220]"
            >
                Cancelar
            </button>

            {{-- Botón Confirmar --}}
            <button
                type="button"
                @click="$store.modal.confirm()"
                class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
            >
                Confirmar
            </button>

        </div>
    </div>
</div>
