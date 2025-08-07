<?= $this->extend('padre/layout') ?>
<?= $this->section('contenido') ?>

    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl sm:text-4xl font-display text-red-600 mb-8">Tus Atletas</h1>

        <?php if (empty($atletas)): ?>
            <div class="bg-yellow-100 text-yellow-800 p-6 rounded shadow text-center">
                <p>No tienes atletas vinculados todavía. Comunícate con el administrador para asociarlos.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($atletas as $a): ?>
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 text-center">
                        <img src="<?= base_url('/uploads/' . esc($a['foto']) . '.png') ?>"
                             alt="<?= esc($a['nombres']) ?>"
                             class="w-28 h-36 object-cover rounded-full mx-auto mb-4 shadow">

                        <h3 class="text-xl font-display text-gray-800 mb-1"><?= esc($a['nombres']) ?></h3>
                        <p class="text-sm text-gray-600 mb-1">Edad: <?= esc($a['edad']) ?></p>
                        <p class="text-sm text-gray-600 mb-1">Club: <?= esc($a['club']) ?></p>
                        <p class="text-sm text-gray-600 mb-2">Categoría: <?= esc($a['categoria']) ?></p>

                        <a href="<?= base_url('/atletas/' . esc($a['slug'])) ?>"
                           class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-full hover:bg-blue-700 transition">
                            Ver perfil
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
<?php
