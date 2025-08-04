<!-- Hero Section -->
<section class="relative min-h-[90vh] md:h-screen bg-cover bg-center" style="background-image: url('/images/hero.jpg')">
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
<section id="agenda" class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Agenda de Carreras</h2>

        <div class="bg-white rounded-2xl shadow-lg p-4 overflow-auto">
            <div id="calendar" class="text-sm md:text-base"></div>
        </div>
    </div>
</section>

<!-- Resultados -->
<section id="resultados" class="bg-white py-16 px-6">
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
<section id="ranking" class="bg-gray-50 py-16 px-6">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Ranking Mensual</h2>
        <div class="grid md:grid-cols-3 gap-6 text-center">
            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-xl font-display text-red-600">1° Luis Martínez</h3>
                <p>Categoría Junior</p>
                <p>Club San Salvador</p>
                <p>Puntos: 120</p>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-xl font-display text-gray-600">2° Diego Ramos</h3>
                <p>Categoría Junior</p>
                <p>Club Santa Ana</p>
                <p>Puntos: 115</p>
            </div>
            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-xl font-display text-yellow-600">3° Kevin López</h3>
                <p>Categoría Junior</p>
                <p>Club La Libertad</p>
                <p>Puntos: 110</p>
            </div>
        </div>
    </div>
</section>

<!-- Atletas -->
<section id="atletas" class="bg-white py-16 px-6">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Atletas</h2>
        <div class="grid md:grid-cols-3 gap-6 text-center">
            <?php foreach ($atletas as $atleta): ?>
                <a href="<?= base_url('atletas/' . esc($atleta['slug'])) ?>" class="bg-gray-100 p-4 rounded shadow block hover:shadow-lg transition" >
                    <img src="<?= base_url('/uploads/' . $atleta['foto'] . '.png') ?>" alt="<?= esc($atleta['nombres']) ?>" class="mx-auto mb-3 rounded-full" style="width: 150px;height: 200px;">
                    <h3 class="text-xl font-display"><?= esc($atleta['nombres']) ?></h3>
                    <p class="text-sm">Edad: <?= esc($atleta['edad']) ?> | Club: <?= esc($atleta['club']) ?></p>
                    <p><?= ''//esc($atleta['descripcion']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- Galería -->
<section id="galeria" class="bg-gray-50 py-16 px-6">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Galería</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <img src="https://source.unsplash.com/300x200/?bmx,action" alt="Evento 1" class="rounded shadow">
            <img src="https://source.unsplash.com/301x200/?bmx,bike" alt="Evento 2" class="rounded shadow">
            <img src="https://source.unsplash.com/302x200/?bmx,track" alt="Evento 3" class="rounded shadow">
        </div>
    </div>
</section>

<!-- Noticias -->
<section id="noticias" class="bg-white py-16 px-6">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Noticias</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-xl font-display">Luis Martínez gana en San Salvador</h3>
                <p class="text-sm text-gray-600">Publicado el 3 de julio de 2025</p>
                <p>Con una salida impecable, Luis se llevó el primer lugar de la competencia junior en la capital.</p>
            </div>
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-xl font-display">Entrevista a Kevin López</h3>
                <p class="text-sm text-gray-600">Publicado el 1 de julio de 2025</p>
                <p>El campeón nacional nos comparte su rutina, metas y cómo motiva a nuevos riders.</p>
            </div>
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-xl font-display">Ranking actualizado de julio</h3>
                <p class="text-sm text-gray-600">Publicado el 5 de julio de 2025</p>
                <p>Consulta el ranking oficial del mes y cómo se mueven los favoritos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Únete -->
<section id="unete" class="bg-red-600 text-white py-20 px-6">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-display mb-6">¿Quieres unirte al BMX?</h2>
        <p class="mb-6">Conoce cómo formar parte del equipo y comenzar tu aventura en el bicicross.</p>
        <a href="#contacto" class="bg-white text-red-600 font-bold py-3 px-6 rounded-full transition hover:bg-gray-200">Contáctanos</a>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="bg-gray-900 text-white py-16 px-6">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Contacto</h2>
        <div class="text-center">[Formulario de contacto y redes sociales]</div>
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

