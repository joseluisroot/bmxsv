<main class="container mx-auto px-6 py-12">
    <div class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-1 text-center">
            <img src="https://via.placeholder.com/200" alt="Luis Martínez" class="rounded-full mx-auto mb-4">
            <h2 class="text-3xl font-display text-red-600"><?= $atleta['nombres'] . ' ' . $atleta['apellidos'] ?></h2>
            <p class="text-gray-600">Categoría: <?= $atleta['categoria'] ?></p>
            <p class="text-sm">Club: <?= $atleta['club'] ?></p>
            <p class="text-sm">Edad: <?= $atleta['edad'] ?> años</p>
            <p class="text-sm">Años en BMX: <?= $atleta['anios_bmx'] ?></p>
        </div>

        <div class="md:col-span-2">
            <h3 class="text-2xl font-display mb-4">Palmarés</h3>
            <ul class="list-disc list-inside text-gray-700 space-y-1">
                <?= $atleta['palmares'] ?>
            </ul>

            <h3 class="text-2xl font-display mt-8 mb-4">Estilo de conducción</h3>
            <p class="text-gray-700"><?= $atleta['estilo'] ?></p>

            <h3 class="text-2xl font-display mt-8 mb-4">Equipamiento</h3>
            <ul class="list-disc list-inside text-gray-700 space-y-1">
                <?= $atleta['equipamiento'] ?>
            </ul>

            <h3 class="text-2xl font-display mt-8 mb-4">Hobbies</h3>
            <p class="text-gray-700"><?= $atleta['hobbies'] ?></p>
        </div>
    </div>
</main>
