<main class="container mx-auto px-6 py-12 max-w-4xl">
    <article itemscope itemtype="https://schema.org/NewsArticle">

        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center space-x-2">
                <li>
                    <a href="<?= base_url() ?>" class="hover:underline hover:text-red-600">Inicio</a>
                </li>
                <li>/</li>
                <li>
                    <a href="<?= base_url('noticias') ?>" class="hover:underline hover:text-red-600">Noticias</a>
                </li>
                <li>/</li>
                <li class="text-gray-700 truncate" aria-current="page"><?= esc($noticia['titulo']) ?></li>
            </ol>
        </nav>

        <!-- Título -->
        <h1 itemprop="headline" class="text-4xl font-display text-red-600 mb-4"><?= esc($noticia['titulo']) ?></h1>

        <!-- Datos del autor y fecha -->
        <p class="text-sm text-gray-500 mb-6">
            Publicado el <time itemprop="datePublished" datetime="<?= $noticia['fecha_publicacion'] ?>">
                <?= date('d M Y', strtotime($noticia['fecha_publicacion'])) ?>
            </time>
            por <span itemprop="author"><?= esc($noticia['autor']) ?></span>
        </p>

        <!-- Imagen destacada -->
        <figure>
            <img itemprop="image" src="<?= base_url('/uploads/noticias/' . $noticia['imagen_destacada']) ?>"
                 alt="<?= esc($noticia['titulo']) ?>"
                 class="mb-8 rounded shadow w-full max-h-[400px] object-cover">
        </figure>

        <!-- Contenido -->
        <div itemprop="articleBody" class="prose prose-lg max-w-none text-gray-800">
            <?= $noticia['contenido'] ?>
        </div>

        <!-- Etiquetas -->
        <?php if (!empty($noticia['etiquetas'])): ?>
            <div class="mt-6">
                <?php foreach (explode(',', $noticia['etiquetas']) as $etiqueta): ?>
                    <a href="<?= base_url('noticias/etiqueta/' . urlencode(trim($etiqueta))) ?>"
                       class="inline-block bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full mr-2 mb-2 hover:bg-red-200 transition">
                        #<?= trim($etiqueta) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Botones para compartir -->
        <div class="mt-12">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Compartir esta noticia</h3>
            <div class="bg-gray-50 p-4 rounded-xl shadow-md flex flex-wrap gap-4 text-xl">

                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>"
                   target="_blank"
                   class="text-blue-600 hover:text-blue-800"
                   title="Compartir en Facebook">
                    <i class="fab fa-facebook-square"></i>
                </a>

                <!-- X (Twitter) -->
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($noticia['titulo']) ?>"
                   target="_blank"
                   class="text-black hover:text-gray-800"
                   title="Compartir en X">
                    <i class="fab fa-x-twitter"></i>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/?text=<?= urlencode($noticia['titulo'] . ' ' . current_url()) ?>"
                   target="_blank"
                   class="text-green-500 hover:text-green-600"
                   title="Compartir en WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>

                <!-- Telegram -->
                <a href="https://t.me/share/url?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($noticia['titulo']) ?>"
                   target="_blank"
                   class="text-blue-500 hover:text-blue-700"
                   title="Compartir en Telegram">
                    <i class="fab fa-telegram"></i>
                </a>

                <!-- Threads -->
                <a href="https://www.threads.net/intent/post?text=<?= urlencode($noticia['titulo'] . ' ' . current_url()) ?>"
                   target="_blank"
                   class="text-black hover:text-gray-700"
                   title="Compartir en Threads">
                    <i class="fab fa-threads"></i>
                </a>
            </div>
        </div>
    </article>
</main>

<section class="bg-gray-50 mt-16 py-12 border-t">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-display mb-6 text-red-600">Noticias relacionadas</h2>

        <?php if (!empty($relacionadas)): ?>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($relacionadas as $rel): ?>
                    <a href="<?= base_url('noticias/' . $rel['slug']) ?>" class="block bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                        <img src="<?= base_url('/uploads/noticias/' . $rel['imagen_destacada']) ?>"
                             alt="<?= esc($rel['titulo']) ?>"
                             class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-display mb-1"><?= esc($rel['titulo']) ?></h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Publicado el <?= date('d M Y', strtotime($rel['fecha_publicacion'])) ?>
                            </p>
                            <p class="text-gray-800 text-sm"><?= esc(character_limiter($rel['resumen'], 100)) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600 text-sm italic">Aún no hay noticias relacionadas. ¡Vuelve pronto para más actualizaciones!</p>
        <?php endif; ?>
    </div>
</section>


