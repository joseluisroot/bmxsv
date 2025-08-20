<section id="galeria" class="bg-white py-16 px-6 shadow-md scroll-mt-24">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Galería</h2>

        <!-- Filtros -->
        <form method="get" class="flex flex-wrap gap-3 justify-center mb-8">
            <select name="anio" class="px-4 py-2 rounded border text-sm">
                <option value="">Todos los años</option>
                <?php foreach ($anios as $y): ?>
                    <option value="<?= esc($y) ?>" <?= ($filtros['anio']==$y?'selected':'') ?>><?= esc($y) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="categoria" class="px-4 py-2 rounded border text-sm">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= esc($c) ?>" <?= ($filtros['categoria']==$c?'selected':'') ?>><?= esc($c) ?></option>
                <?php endforeach; ?>
            </select>

            <button class="px-4 py-2 rounded bg-red-600 text-white text-sm">Filtrar</button>
        </form>

        <?php if (empty($albums)): ?>
            <p class="text-center text-gray-600">No hay álbumes de galería registrados.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($albums as $a): ?>
                    <a href="<?= base_url('galeria/album/'.$a['slug']) ?>"
                       class="block bg-gray-50 border rounded overflow-hidden hover:shadow-lg transition">
                        <div class="aspect-[4/3] bg-gray-200 overflow-hidden">
                            <img src="<?= base_url($a['portada'] ?: 'assets/img/galeria-placeholder.jpg') ?>"
                                 alt="<?= esc($a['titulo']) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1"><?= esc($a['titulo']) ?></h3>
                            <p class="text-sm text-gray-600">
                                <?= esc($a['categoria'] ?: 'General') ?> ·
                                <?= esc($a['anio'] ?: ( $a['fecha_evento'] ? date('Y', strtotime($a['fecha_evento'])) : '')) ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
