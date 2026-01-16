<div
    x-show="$store.modal.open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- Overlay -->
    <div
        class="absolute inset-0 bg-black bg-opacity-70"
        @click="$store.modal.close()"
    ></div>

    <!-- Modal -->
    <div class="relative bg-[#111827] border border-[#1F2933] rounded-2xl w-full max-w-md p-6 shadow-xl">

        <h2 class="text-xl font-semibold text-white" x-text="$store.modal.title"></h2>

        <p class="text-sm text-gray-400 mt-2" x-text="$store.modal.message"></p>

        <div class="flex justify-end gap-3 mt-6">
            <button
                @click="$store.modal.close()"
                class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300 hover:bg-[#0B1220]"
            >
                Cancelar
            </button>

            <button
                @click="$store.modal.confirm()"
                class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
            >
                Confirmar
            </button>
        </div>
    </div>
</div>
