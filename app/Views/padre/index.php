<?= $this->extend('padre/layout') ?>
<?= $this->section('contenido') ?>

<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl sm:text-4xl font-display text-red-600 mb-6">Bienvenido, <?= session('usuario.nombre') ?></h1>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center text-center">
            <i class="fas fa-biking text-4xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Atletas Asociados</h3>
            <p class="text-3xl font-bold text-gray-900">3</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center text-center">
            <i class="fas fa-calendar-alt text-4xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Próxima Carrera</h3>
            <p class="text-gray-800">10 de agosto, San Salvador</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center text-center">
            <i class="fas fa-medal text-4xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Último Resultado</h3>
            <p class="text-gray-800">Lucas - 2º lugar en Junior</p>
        </div>



    </div>

    <!-- Mensaje motivacional -->
    <div class="bg-red-600 text-white rounded-xl p-6 text-center shadow">
        <h2 class="text-2xl font-display font-bold mb-2">¡Sigue apoyando el talento BMX!</h2>
        <p class="text-lg">Gracias por acompañar a tus hijos en cada carrera, entrenamiento y meta alcanzada.</p>
    </div>
</div>

<?= $this->endSection() ?>
