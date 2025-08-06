<!-- Hero Section -->
<section class="relative min-h-[90vh] md:h-screen bg-cover bg-center shadow-md" style="background-image: url('/images/hero.jpg')">
    <div class="absolute inset-0 bg-black/60 flex items-center justify-center px-4">
        <div class="text-center text-white max-w-2xl animate-fade-in-up">
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-display font-bold mb-4 leading-tight">
                Bicicross El Salvador
            </h1>
            <p class="text-base sm:text-lg md:text-xl mb-6 text-white/90">
                Vive la emoción del BMX Race y forma parte del movimiento
            </p>
            <a href="#unete"
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow-lg">
                ÚNETE AL BMX
            </a>
        </div>
    </div>
</section>


<!-- Agenda de Carreras -->
<section id="agenda" class="py-16 bg-white shadow-md">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Agenda de Carreras</h2>

        <div class="bg-white rounded-2xl shadow-lg p-4 overflow-auto">
            <div id="calendar" class="text-sm md:text-base"></div>
        </div>
    </div>
</section>

<!-- Resultados -->
<section id="resultados" class="bg-white py-16 px-6 shadow-md">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Resultados Recientes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2">Carrera</th>
                    <th class="p-2">Categoría</th>
                    <th class="p-2">Ganador</th>
                    <th class="p-2">Tiempo</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-t">
                    <td class="p-2">Fecha 1 - San Salvador</td>
                    <td class="p-2">Junior</td>
                    <td class="p-2">Luis Martínez</td>
                    <td class="p-2">38.9s</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Ranking -->
<section id="ranking" class="py-16 px-6 bg-white shadow-md">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Ranking Mensual de Atletas</h2>

        <!-- Tabs de meses -->
        <div id="tabs-container" class="flex flex-wrap justify-center gap-2 mb-6">
            <!-- Botones generados por JS -->
        </div>

        <!-- Contenido de rankings -->
        <div id="ranking-content">
            <!-- Se rellena dinámicamente -->
        </div>
    </div>
</section>

<!-- Atletas -->
<script>
    const atletasData = <?= json_encode($atletas) ?>;
</script>
<section id="atletas" class="bg-white py-16 px-6 shadow-md">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-10 text-center">Nuestros Atletas</h2>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 justify-center mb-6">
            <select id="filtro-club" class="px-4 py-2 rounded border text-sm">
                <option value="">Todos los clubes</option>
                <!-- opciones generadas dinámicamente -->
            </select>

            <select id="filtro-categoria" class="px-4 py-2 rounded border text-sm">
                <option value="">Todas las categorías</option>
                <!-- opciones generadas dinámicamente -->
            </select>

            <input type="text" id="buscador" placeholder="Buscar atleta..."
                   class="px-4 py-2 border rounded text-sm w-full sm:w-auto">
        </div>

        <!-- Contenedor de tarjetas -->
        <div id="contenedor-atletas"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">
            <!-- Tarjetas insertadas vía JS -->
        </div>

        <!-- Paginación -->
        <div class="flex justify-center mt-8">
            <button id="anterior" class="px-4 py-2 border rounded-l text-sm bg-gray-100 hover:bg-gray-200">Anterior</button>
            <button id="siguiente" class="px-4 py-2 border rounded-r text-sm bg-gray-100 hover:bg-gray-200">Siguiente</button>
        </div>
    </div>
</section>

<!-- Galería -->
<section id="galeria" class="bg-gray-50 py-16 px-6 shadow-md">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-10 text-center">Galería por Evento y Año</h2>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-2 justify-center mb-8">
            <!-- Categorías -->
            <button data-categoria="todos" class="filter-btn bg-red-600 text-white px-4 py-2 rounded-full text-sm">Todos</button>
            <button data-categoria="campeonato" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Campeonato</button>
            <button data-categoria="entrenamiento" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Entrenamiento</button>
            <button data-categoria="openhouse" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Open House</button>

            <!-- Años -->
            <button data-anio="todos" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Todos los años</button>
            <button data-anio="2024" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">2024</button>
            <button data-anio="2025" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">2025</button>
        </div>

        <!-- Galería -->
        <div id="galeria-grid" class="md:grid md:grid-cols-3 gap-6 overflow-x-auto flex md:flex-none space-x-4 snap-x snap-mandatory scroll-smooth px-1">
            <!-- Ejemplo de imagen con categoría y año -->
            <div class="galeria-item min-w-[80%] sm:min-w-[300px] flex-shrink-0 snap-center" data-categoria="campeonato" data-anio="2025">
                <img src="https://picsum.photos/600/400?random=1" alt="Campeonato" class="rounded-xl shadow w-full">
            </div>
            <div class="galeria-item min-w-[80%] sm:min-w-[300px] flex-shrink-0 snap-center" data-categoria="entrenamiento" data-anio="2024">
                <img src="https://picsum.photos/600/400?random=2" alt="Entrenamiento" class="rounded-xl shadow w-full">
            </div>
            <div class="galeria-item min-w-[80%] sm:min-w-[300px] flex-shrink-0 snap-center" data-categoria="openhouse" data-anio="2025">
                <img src="https://picsum.photos/600/400?random=3" alt="Open House" class="rounded-xl shadow w-full">
            </div>
            <div class="galeria-item min-w-[80%] sm:min-w-[300px] flex-shrink-0 snap-center" data-categoria="campeonato" data-anio="2024">
                <img src="https://picsum.photos/600/400?random=4" alt="Campeonato" class="rounded-xl shadow w-full">
            </div>
        </div>
    </div>
</section>

<!-- Noticias -->
<section id="noticias" class="bg-white py-16 px-6 shadow-md">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Noticias</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Noticia 1 -->
            <a href="<?= base_url('noticias/luis-martinez-gana-en-san-salvador') ?>" class="block bg-gray-100 rounded shadow hover:shadow-lg transition overflow-hidden">
                <img src="https://picsum.photos/600/300?random=101" alt="Luis Martínez" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-display mb-1">Luis Martínez gana en San Salvador</h3>
                    <p class="text-sm text-gray-600 mb-2">Publicado el 3 de julio de 2025</p>
                    <p class="text-gray-800">Con una salida impecable, Luis se llevó el primer lugar de la competencia junior en la capital.</p>
                </div>
            </a>

            <!-- Noticia 2 -->
            <a href="<?= base_url('noticias/entrevista-a-kevin-lopez') ?>" class="block bg-gray-100 rounded shadow hover:shadow-lg transition overflow-hidden">
                <img src="https://picsum.photos/600/300?random=102" alt="Kevin López" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-display mb-1">Entrevista a Kevin López</h3>
                    <p class="text-sm text-gray-600 mb-2">Publicado el 1 de julio de 2025</p>
                    <p class="text-gray-800">El campeón nacional nos comparte su rutina, metas y cómo motiva a nuevos riders.</p>
                </div>
            </a>

            <!-- Noticia 3 -->
            <a href="<?= base_url('noticias/ranking-actualizado-julio') ?>" class="block bg-gray-100 rounded shadow hover:shadow-lg transition overflow-hidden">
                <img src="https://picsum.photos/600/300?random=103" alt="Ranking Julio" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-display mb-1">Ranking actualizado de julio</h3>
                    <p class="text-sm text-gray-600 mb-2">Publicado el 5 de julio de 2025</p>
                    <p class="text-gray-800">Consulta el ranking oficial del mes y cómo se mueven los favoritos.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Botón para ver más noticias -->
    <div class="mt-10 text-center">
        <a href="<?= base_url('noticias') ?>" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-full transition">
            Ver más noticias
        </a>
    </div>

</section>

<!-- Horarios de Entrenamiento -->
<section id="horarios" class="bg-gray-50 py-16 px-6">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-4xl font-display mb-8 text-center text-red-600">Horarios de Entrenamiento</h2>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Horario semanal -->
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Días y Horarios</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><strong>Lunes, Miércoles y Viernes:</strong> 4:00 PM – 6:00 PM</li>
                    <li><strong>Sábados:</strong> 9:00 AM – 11:00 AM</li>
                </ul>
            </div>

            <!-- Categorías y entrenadores -->
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Categorías y Entrenadores</h3>
                <ul class="space-y-3 text-gray-700">
                    <li>
                        <span class="block font-bold text-red-600">Championship (10–20 años)</span>
                        <span>Entrenador: Federico Polo</span>
                    </li>
                    <li>
                        <span class="block font-bold text-red-600">Inicial (3–9 años)</span>
                        <span>Entrenador: Hugo Rubio</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Únete -->
<section id="unete" class="bg-red-600 text-white py-20 px-6">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-display mb-6">¿Quieres unirte al BMX?</h2>
        <p class="mb-6 text-lg">Conoce cómo formar parte del equipo y comenzar tu aventura en el bicicross.</p>

        <a href="https://wa.me/50379146855?text=Hola%2C%20quiero%20unirme%20al%20equipo%20de%20BMX%20Race%20El%20Salvador%21"
           target="_blank"
           class="bg-white text-red-600 font-bold py-3 px-6 rounded-full transition hover:bg-gray-200 inline-flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-xl"></i>
            Escríbenos por WhatsApp
        </a>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="bg-gray-900 text-white py-16 px-6">
    <div class="container mx-auto max-w-3xl">
        <h2 class="text-4xl font-display mb-6 text-center">Contacto</h2>

        <!-- Formulario -->
        <form action="<?= base_url('contacto/enviar') ?>" method="POST" class="space-y-4 bg-gray-800 p-6 rounded shadow">
            <div>
                <label for="nombre" class="block mb-1 font-semibold">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="email" class="block mb-1 font-semibold">Correo electrónico</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="mensaje" class="block mb-1 font-semibold">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="4" required class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="text-center pt-4">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition">Enviar mensaje</button>
            </div>
        </form>

        <!-- Redes sociales -->
        <div class="mt-10 text-center">
            <p class="text-lg font-semibold mb-4">Síguenos en nuestras redes:</p>
            <div class="flex justify-center space-x-6 text-3xl">
                <a href="https://www.facebook.com/ESAbicicross" target="_blank" class="hover:text-red-500">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.instagram.com/esabicicross" target="_blank" class="hover:text-red-500">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Patrocinadores -->
<section id="patrocinadores" class="bg-white py-20 px-6">
    <div class="container mx-auto max-w-5xl text-center">
        <h2 class="text-4xl font-display mb-6 text-red-600">Patrocinadores</h2>
        <p class="mb-10 text-gray-700 text-lg">
            Apoya el desarrollo del BMX en El Salvador. Tu marca puede formar parte del crecimiento de jóvenes talentos nacionales.
        </p>

        <!-- Simulación de logos -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
        </div>

        <!-- Botón de contacto -->
        <a href="https://wa.me/50370123456?text=Hola%2C%20estoy%20interesado%20en%20patrocinar%20el%20proyecto%20BMX%20Race%20El%20Salvador"
           target="_blank"
           class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full transition inline-flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-xl"></i>
            Quiero ser patrocinador
        </a>
    </div>
</section>

<!-- Modal Detalle Evento -->
<div id="eventoModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div id="modalContent" class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative animate-fade-in-up">
        <!-- Botón cerrar -->
        <button id="cerrarModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i class="fas fa-times"></i>
        </button>

        <h3 id="modalTitulo" class="text-2xl font-bold text-red-600 mb-2"></h3>
        <p id="modalFecha" class="text-gray-700 mb-4"></p>
        <div id="modalContenido" class="text-gray-800 text-sm space-y-2"></div>
    </div>
</div>

