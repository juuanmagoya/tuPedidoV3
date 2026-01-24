@props([
    'items',
    'title' => 'Stock bajo',
    'entity' => 'ítem'
])

@if($items->isNotEmpty())
<div
    x-data="{ open: true }"
    x-show="open"
    class="bg-red-600 text-white rounded-xl p-4 mb-6 shadow flex flex-col md:flex-row md:items-center md:justify-between gap-4"
>
    <div>
        <h2 class="font-semibold text-lg">
            {{ $title }}
        </h2>
        <p class="text-sm opacity-90">
            Hay {{ $items->count() }} {{ $entity }}(s) con stock menor o igual al mínimo.
        </p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach($items as $item)
            <span class="bg-red-500 px-3 py-1 rounded-full text-sm font-medium">
                {{ $item->name }} ({{ $item->stock }})
            </span>
        @endforeach
    </div>

    <button
        @click="open = false"
        class="ml-auto text-white font-bold text-xl"
    >
        ×
    </button>
</div>
@endif
