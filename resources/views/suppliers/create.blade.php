@extends('layouts.app')

@section('title', 'Nuevo proveedor')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <h1 class="text-2xl font-semibold text-white mb-6">
        Nuevo proveedor
    </h1>

    <form x-ref="form"
          action="{{ route('suppliers.store') }}"
          method="POST"
          class="bg-[#111827] border border-[#1F2933]
                 rounded-2xl p-6 shadow-lg">

        @include('suppliers._form')

    </form>
</div>
@endsection
