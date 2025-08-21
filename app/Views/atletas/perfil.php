<main class="container mx-auto px-6 py-12">
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Columna izquierda: Foto e info básica -->
        <div class="md:col-span-1 text-center">
            <img src="<?= base_url('/uploads/atletas/' . $atleta['foto'] . '.png') ?>"
                 alt="<?= esc($atleta['nombres'] . ' ' . $atleta['apellidos']) ?>"
                 class="rounded-full mx-auto mb-4"
                 style="width: 150px; height: 200px;">
            <h2 class="text-3xl font-display text-red-600"><?= esc($atleta['nombres'] . ' ' . $atleta['apellidos']) ?></h2>
            <p class="text-gray-600">Categoría: <?= esc($atleta['categoria']) ?></p>
            <p class="text-sm">Club: <?= esc($atleta['club']) ?></p>
            <p class="text-sm">Edad: <?= esc($atleta['edad']) ?> años</p>
            <p class="text-sm">Tiempo en BMX: <?= esc($atleta['anios_bmx']) ?></p>

            <?php if (!empty($redes_sociales)): ?>
                <div class="flex justify-center gap-4 mt-4">
                    <?php foreach ($redes_sociales as $red): ?>
                        <a href="<?= esc($red['url']) ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-blue-600 text-xl">
                            <i class="fab <?= esc($red['icono']) ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna derecha: Detalle completo -->
        <div class="md:col-span-2 space-y-8">
            <?php if (!empty($atleta['descripcion'])): ?>
                <div>
                    <h3 class="text-2xl font-display mb-2">Descripción</h3>
                    <p class="text-gray-700 leading-relaxed"><?= nl2br(esc($atleta['descripcion'])) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($atleta['palmares'])): ?>
                <div>
                    <h3 class="text-2xl font-display mb-2">Palmarés</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <?= $atleta['palmares'] ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($atleta['estilo'])): ?>
                <div>
                    <h3 class="text-2xl font-display mb-2">Estilo de conducción</h3>
                    <p class="text-gray-700"><?= esc($atleta['estilo']) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($atleta['equipamiento'])): ?>
                <div>
                    <h3 class="text-2xl font-display mb-2">Equipamiento</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <?= $atleta['equipamiento'] ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($atleta['hobbies'])): ?>
                <div>
                    <h3 class="text-2xl font-display mb-2">Hobbies</h3>
                    <p class="text-gray-700"><?= esc($atleta['hobbies']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <h3 class="text-2xl font-display mt-8 mb-4">Galería</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php foreach ($galeria as $img): ?>
            <div class="bg-gray-100 rounded shadow overflow-hidden">
                <img src="<?= base_url('/uploads/galeria/' . $img['imagen']) ?>" alt="<?= esc($img['descripcion']) ?>" class="w-full h-48 object-cover">
                <?php if (!empty($img['descripcion'])): ?>
                    <p class="text-sm text-gray-600 p-2"><?= esc($img['descripcion']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</main>
