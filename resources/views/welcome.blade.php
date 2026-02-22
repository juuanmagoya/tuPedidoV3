{{-- resources/views/landing.blade.php --}}
@extends('layouts.landing')

@section('content')

{{-- ================= STYLES Y SCRIPTS ADICIONALES ================= --}}
{{-- Lo ideal es ponerlos en tu layout principal, pero si no, aquí van --}}
@push('styles')
{{-- Animate.css para animaciones básicas --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
{{-- AOS Library (Animate On Scroll) --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
{{-- Font Awesome (para iconos) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- Google Fonts (Poppins) --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    /* Clase personalizada para el fondo de panadería */
    .hero-pattern {
        background-color: #3E2723;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f59e0b' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    /* Transición suave para las tarjetas */
    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-8px);
    }
    /* Estilo para el badge de oferta */
    .offer-badge {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
    }
    /* Animación para el botón de llamada a la acción (CTA) */
    .cta-button {
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
    }
    .cta-button:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(245, 158, 11, 0.4);
    }
</style>
@endpush

<!-- ================= HERO MEJORADO ================= -->
<section class="relative hero-pattern text-white py-32 overflow-hidden">
    {{-- Overlay sutil para mejorar legibilidad --}}
    <div class="absolute inset-0 bg-black opacity-30"></div>

    <div class="relative max-w-7xl mx-auto px-6 text-center z-10">
        {{-- Animación de entrada para el H1 --}}
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight animate__animated animate__fadeInDown"
            style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
            El Sabor de la <span class="text-[#F59E0B]">Tradición</span>
        </h1>

        {{-- Animación de entrada para el párrafo con delay --}}
        <p class="text-lg md:text-2xl text-gray-200 max-w-2xl mx-auto mb-10 animate__animated animate__fadeInUp animate__delay-1s">
            Horneamos cada día con pasión, tradición y los mejores ingredientes para endulzar tus momentos.
        </p>

        {{-- Animación para el botón --}}
        <a href="#catalogo"
           class="cta-button mt-4 inline-block bg-[#F59E0B] hover:bg-yellow-500 text-gray-900 font-bold px-10 py-4 rounded-full transition-all duration-300 shadow-2xl text-lg animate__animated animate__fadeInUp animate__delay-2s">
            <i class="fas fa-bread-slice mr-2"></i> Ver Productos
        </a>
    </div>
</section>

<!-- ================= DESTACADOS CON AOS ================= -->
@if($featured->count())
<section class="py-24 bg-[#FFF8F0]">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-bold text-[#3E2723] mb-4 text-center" data-aos="fade-down">
            Productos Destacados
        </h2>
        <p class="text-gray-500 text-center mb-16 max-w-2xl mx-auto" data-aos="fade-down" data-aos-delay="100">
            Los favoritos de nuestra panadería, horneados a la perfección.
        </p>

        <div class="grid md:grid-cols-4 gap-8">
            @foreach($featured as $index => $product)
                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl product-card p-6 group"
                     data-aos="fade-up"
                     data-aos-delay="{{ $index * 50 }}">
                    {{-- Contenedor de imagen con overflow hidden para el efecto zoom --}}
                    <div class="overflow-hidden rounded-xl mb-5">
                        <img src="{{ asset('storage/'.$product->image) }}"
                             alt="{{ $product->name }}"
                             class="h-52 w-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                    </div>

                    <h3 class="font-bold text-xl text-[#3E2723] mb-1">
                        {{ $product->name }}
                    </h3>

                    {{-- Rating de estrellas estático (Ejemplo) --}}
                    <div class="flex items-center mb-2">
                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                        <span class="text-gray-400 text-xs ml-2">(120)</span>
                    </div>

                    <p class="text-2xl font-bold text-[#F59E0B] mt-3">
                        ${{ number_format($product->price, 2) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ================= PROMOCIONES CON BADGE MEJORADO ================= -->
@if($promotions->count())
<section class="py-24 bg-gradient-to-br from-amber-50 to-orange-100">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-bold text-[#3E2723] mb-4 text-center" data-aos="fade-down">
            <i class="fas fa-fire text-red-500 mr-2"></i> Promociones Especiales
        </h2>
         <p class="text-gray-600 text-center mb-16 max-w-2xl mx-auto" data-aos="fade-down" data-aos-delay="100">
            ¡Aprovecha estas ofertas por tiempo limitado!
        </p>

        <div class="grid md:grid-cols-4 gap-8">
            @foreach($promotions as $index => $product)
                <div class="relative bg-white rounded-3xl shadow-lg product-card p-6 hover:shadow-2xl"
                     data-aos="zoom-in"
                     data-aos-delay="{{ $index * 100 }}">

                    {{-- Badge de oferta más vistoso --}}
                    <span class="absolute -top-3 -right-3 offer-badge text-white text-sm font-bold px-4 py-2 rounded-full z-10">
                        <i class="fas fa-tag mr-1"></i> -20%
                    </span>

                    <div class="overflow-hidden rounded-xl mb-5">
                        <img src="{{ asset('storage/'.$product->image) }}"
                             alt="{{ $product->name }}"
                             class="h-48 w-full object-cover group-hover:scale-110 transition duration-700">
                    </div>

                    <h3 class="font-bold text-xl text-[#3E2723] mb-1">
                        {{ $product->name }}
                    </h3>

                    {{-- Precio con tachado y nuevo precio --}}
                    <p class="text-gray-400 line-through text-sm">
                        ${{ number_format($product->price * 1.2, 2) }}
                    </p>
                    <p class="text-3xl font-bold text-red-600 mt-1">
                        ${{ number_format($product->price, 2) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ================= CATÁLOGO MEJORADO ================= -->
<section id="catalogo" class="py-24 bg-[#FDF6EC]">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-4xl font-bold text-center text-[#3E2723] mb-4" data-aos="fade-down">
            Nuestro Catálogo
        </h2>
        <p class="text-gray-500 text-center mb-12 max-w-2xl mx-auto" data-aos="fade-down" data-aos-delay="100">
            Explora nuestra gran variedad de productos artesanales.
        </p>

        <!-- FILTROS MEJORADOS -->
        <form method="GET"
              class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 mb-16 flex flex-col md:flex-row gap-4 items-center border border-white/50"
              data-aos="fade-up">

            <div class="relative flex-1 w-full">
                <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="¿Qué antojas hoy?..."
                       class="w-full border-2 border-gray-200 rounded-full pl-12 pr-5 py-4 focus:ring-4 focus:ring-[#F59E0B] focus:ring-opacity-50 focus:border-[#F59E0B] focus:outline-none transition">
            </div>

            <div class="relative w-full md:w-64">
                <i class="fas fa-chevron-down absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                <select name="category"
                        class="w-full appearance-none border-2 border-gray-200 rounded-full px-5 py-4 focus:ring-4 focus:ring-[#F59E0B] focus:ring-opacity-50 focus:border-[#F59E0B] focus:outline-none transition bg-white">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="w-full md:w-auto bg-[#3E2723] text-white px-10 py-4 rounded-full hover:bg-[#5D4037] transition-all duration-300 font-semibold shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </form>

        <!-- PRODUCTOS CON AOS -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($products as $index => $product)
                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl product-card p-6 group"
                     data-aos="fade-up"
                     data-aos-delay="{{ $index * 50 }}">

                    <div class="overflow-hidden rounded-xl mb-5">
                        <img src="{{ asset('storage/'.$product->image) }}"
                             alt="{{ $product->name }}"
                             class="h-44 w-full object-cover group-hover:scale-110 transition duration-700">
                    </div>

                    <h3 class="font-bold text-xl text-[#3E2723] mb-1">
                        {{ $product->name }}
                    </h3>

                    <p class="text-gray-400 text-sm font-medium mb-3">
                        <i class="fas fa-tag mr-1 text-[#F59E0B]"></i> {{ $product->category->name }}
                    </p>

                    <div class="flex items-center justify-between mt-4">
                        <p class="text-2xl font-bold text-[#F59E0B]">
                            ${{ number_format($product->price, 2) }}
                        </p>
                        {{-- Botón de compra rápida (solo visual) --}}
                        <button class="bg-gray-100 hover:bg-[#F59E0B] w-10 h-10 rounded-full flex items-center justify-center transition group/btn"
                                onclick="alert('Funcionalidad de carrito próximamente')">
                            <i class="fas fa-shopping-basket text-gray-500 group-hover/btn:text-white transition"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20" data-aos="fade-up">
                    <i class="fas fa-sad-tear text-6xl text-gray-300 mb-4"></i>
                    <p class="text-2xl text-gray-500">No encontramos productos con esos filtros.</p>
                    <a href="{{ route('home') }}" class="inline-block mt-4 text-[#F59E0B] hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i> Ver todos los productos
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Si usas paginación, aquí se mostraría --}}
        {{-- <div class="mt-16">
            {{ $products->links() }}
        </div> --}}
    </div>
</section>

<!-- ================= FOOTER MEJORADO ================= -->
<footer class="bg-[#3E2723] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-8 mb-12">
        <div data-aos="fade-right">
            <h3 class="text-2xl font-bold mb-4 flex items-center">
                <i class="fas fa-bread-slice text-[#F59E0B] mr-2"></i> Panadería
            </h3>
            <p class="text-gray-300 text-sm">
                Horneando sonrisas desde 1990. Ingredientes frescos y recetas tradicionales.
            </p>
        </div>
        <div data-aos="fade-up" data-aos-delay="100">
            <h4 class="font-semibold text-lg mb-4">Horarios</h4>
            <ul class="text-gray-300 text-sm space-y-2">
                <li><i class="far fa-clock mr-2 text-[#F59E0B]"></i> Lun - Vie: 7am - 8pm</li>
                <li><i class="far fa-clock mr-2 text-[#F59E0B]"></i> Sáb: 8am - 6pm</li>
                <li><i class="far fa-clock mr-2 text-[#F59E0B]"></i> Dom: 9am - 2pm</li>
            </ul>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
            <h4 class="font-semibold text-lg mb-4">Contacto</h4>
            <ul class="text-gray-300 text-sm space-y-2">
                <li><i class="fas fa-map-marker-alt mr-2 text-[#F59E0B]"></i> Av. Principal #123</li>
                <li><i class="fas fa-phone mr-2 text-[#F59E0B]"></i> +123 456 7890</li>
                <li><i class="fas fa-envelope mr-2 text-[#F59E0B]"></i> hola@panaderia.com</li>
            </ul>
        </div>
        <div data-aos="fade-left" data-aos-delay="300">
            <h4 class="font-semibold text-lg mb-4">Síguenos</h4>
            <div class="flex space-x-4">
                <a href="#" class="bg-[#5D4037] hover:bg-[#F59E0B] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="bg-[#5D4037] hover:bg-[#F59E0B] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="bg-[#5D4037] hover:bg-[#F59E0B] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-[#5D4037] pt-8 text-center">
        <p class="text-sm text-gray-400">
            © {{ date('Y') }} Panadería Artesanal. Todos los derechos reservados. <br class="md:hidden">
            Hecho con <i class="fas fa-heart text-red-500 mx-1"></i> y mucha harina.
        </p>
    </div>
</footer>

{{-- ================= SCRIPTS PARA ANIMACIONES ================= --}}
@push('scripts')
{{-- AOS Library JS --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Inicializar AOS (Animate On Scroll)
    AOS.init({
        duration: 800, // Duración de la animación
        once: true, // La animación ocurre solo una vez
        offset: 100, // Offset (en px) desde el punto de activación original
    });
</script>
@endpush

@endsection