<?= $pager->links('default', 'tailwind_full') ?>
<main class="bg-white py-16">
    <div class="container mx-auto px-6 max-w-6xl">

        <!-- SEO básico de la página -->
        <h1 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Todas las noticias</h1>

        <!-- (Opcional futuro) filtros / buscador -->
        <div class="mb-8 flex flex-wrap gap-3 justify-between">
            <input type="text" placeholder="Buscar noticia..." class="border rounded px-4 py-2 w-full sm:w-80">
        </div>

        <?php if (empty($noticias)) : ?>
            <p class="text-center text-gray-600">Aún no hay noticias publicadas.</p>
        <?php else: ?>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($noticias as $n): ?>
                    <a href="<?= base_url('noticias/' . esc($n['slug'])) ?>"
                       class="block bg-gray-50 rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                        <img
                            src="<?= base_url('uploads/noticias/' . $n['imagen_destacada']) ?>"
                            alt="<?= esc($n['titulo']) ?>"
                            class="w-full h-44 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-display mb-1 line-clamp-2"><?= esc($n['titulo']) ?></h2>
                            <p class="text-sm text-gray-500 mb-2">
                                <?= date('d M Y', strtotime($n['fecha_publicacion'])) ?>
                                <?php if (!empty($n['autor'])): ?> • <?= esc($n['autor']) ?><?php endif; ?>
                            </p>
                            <p class="text-gray-700 text-sm line-clamp-3"><?= esc($n['resumen']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <div class="mt-10 flex flex-col items-center gap-4">
                <!-- Móvil -->
                <div class="md:hidden w-full flex justify-center">
                    <?= $pager->links('default', 'tailwind_simple') ?>
                </div>

                <!-- Escritorio -->
                <div class="hidden md:flex">
                    <?= $pager->links('default', 'tailwind_full') ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>