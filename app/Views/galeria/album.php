<section class="bg-white py-16 px-6 shadow-md">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-3"><?= esc($album['titulo']) ?></h2>
        <p class="text-gray-600 mb-6">
            <?= esc($album['categoria'] ?: 'General') ?>
            <?php if(!empty($album['fecha_evento'])): ?>
                · <?= date('d M Y', strtotime($album['fecha_evento'])) ?>
            <?php endif; ?>
        </p>
        <?php if (!empty($album['descripcion'])): ?>
            <p class="mb-6"><?= esc($album['descripcion']) ?></p>
        <?php endif; ?>

        <?php if (empty($fotos)): ?>
            <p class="text-gray-600">Este álbum aún no tiene fotos.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($fotos as $f): ?>
                    <a href="<?= base_url($f['archivo']) ?>" target="_blank" class="block bg-gray-100 rounded overflow-hidden">
                        <img src="<?= base_url($f['archivo']) ?>" alt="<?= esc($f['alt'] ?: $f['titulo'] ?: $album['titulo']) ?>"
                             class="w-full h-48 object-cover">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
