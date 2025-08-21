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

    <?php
    $maxVisible = 6;
    $total = count($galeria);
    $showMoreCount = max(0, $total - $maxVisible);
    ?>
    <h3 class="text-2xl font-display text-red-600 mt-8 mb-4">Galería</h3>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="galeria-grid">
        <?php foreach ($galeria as $index => $img):
            $src = base_url('/uploads/atletas/' . $atleta['slug'] . '/' . $img['imagen']);
            $alt = !empty($img['descripcion']) ? $img['descripcion'] : ($atleta['nombres'] . ' ' . $atleta['apellidos'] . ' - Foto ' . ($index + 1));
            $caption = $img['descripcion'] ?? '';
            $isHidden = ($index >= $maxVisible);
            ?>

            <?php if ($index === ($maxVisible - 1) && $showMoreCount > 0): ?>
            <!-- Tarjeta 6 con overlay "+N fotos" -->
            <button type="button"
                    class="relative group bg-gray-100 rounded shadow overflow-hidden focus:outline-none focus:ring-2 focus:ring-red-500"
                    data-toggle-galeria
                    aria-label="Ver <?= $showMoreCount ?> fotos más">
                <img src="<?= esc($src) ?>"
                     alt="<?= esc($alt) ?>"
                     class="w-full h-48 object-cover brightness-50 group-hover:brightness-75 transition"
                     loading="lazy">
                <span class="absolute inset-0 flex items-center justify-center">
                    <span class="text-white text-xl font-semibold bg-black/50 px-3 py-1 rounded-lg">
                        +<?= $showMoreCount ?> fotos
                    </span>
                </span>
            </button>

        <?php elseif ($index < $maxVisible): ?>
            <!-- Primeras 5 tarjetas normales -->
            <button type="button"
                    class="group bg-gray-100 rounded shadow overflow-hidden focus:outline-none focus:ring-2 focus:ring-red-500"
                    data-gallery-item
                    data-src="<?= esc($src) ?>"
                    data-alt="<?= esc($alt) ?>"
                    data-caption="<?= esc($caption) ?>">
                <img src="<?= esc($src) ?>" alt="<?= esc($alt) ?>"
                     class="w-full h-48 object-cover transition group-hover:opacity-90"
                     loading="lazy" referrerpolicy="no-referrer">
                <?php if (!empty($img['descripcion'])): ?>
                    <p class="text-sm text-gray-600 p-2"><?= esc($img['descripcion']) ?></p>
                <?php endif; ?>
            </button>

        <?php else: ?>
            <!-- Resto de tarjetas ocultas inicialmente -->
            <button type="button"
                    class="group bg-gray-100 rounded shadow overflow-hidden focus:outline-none focus:ring-2 focus:ring-red-500 <?= $isHidden ? 'hidden' : '' ?>"
                    data-gallery-item
                    data-src="<?= esc($src) ?>"
                    data-alt="<?= esc($alt) ?>"
                    data-caption="<?= esc($caption) ?>"
                    data-galeria-hidden>
                <img src="<?= esc($src) ?>" alt="<?= esc($alt) ?>"
                     class="w-full h-48 object-cover transition group-hover:opacity-90"
                     loading="lazy" referrerpolicy="no-referrer">
                <?php if (!empty($img['descripcion'])): ?>
                    <p class="text-sm text-gray-600 p-2"><?= esc($img['descripcion']) ?></p>
                <?php endif; ?>
            </button>
        <?php endif; ?>

        <?php endforeach; ?>
    </div>

    <?php if ($showMoreCount > 0): ?>
        <div class="mt-4 text-center">
            <button type="button" id="btn-toggle-galeria"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 hover:border-gray-400 text-sm font-semibold">
                Mostrar todas
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    <?php endif; ?>


    <!-- Lightbox Modal -->
    <div id="lightbox"
         class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm"
         role="dialog" aria-modal="true" aria-labelledby="lightbox-title" aria-hidden="true">
        <div class="absolute inset-0 flex items-center justify-center px-4">
            <div class="relative w-full max-w-5xl">
                <!-- Imagen -->
                <img id="lightbox-image"
                     src=""
                     alt=""
                     class="max-h-[80vh] w-auto mx-auto rounded-xl shadow-2xl select-none"
                     draggable="false">
                <!-- Caption -->
                <div class="mt-3 text-center text-gray-200">
                    <h4 id="lightbox-title" class="sr-only">Visor de imágenes</h4>
                    <p id="lightbox-caption" class="text-sm"></p>
                    <p id="lightbox-counter" class="text-xs text-gray-300 mt-1"></p>
                </div>

                <!-- Controles -->
                <button type="button" id="lightbox-close"
                        class="absolute -top-4 -right-4 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 shadow focus:outline-none focus:ring-2 focus:ring-red-500"
                        aria-label="Cerrar (Esc)">
                    <!-- X -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <button type="button" id="lightbox-prev"
                        class="absolute top-1/2 -translate-y-1/2 -left-4 md:-left-10 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow focus:outline-none focus:ring-2 focus:ring-red-500"
                        aria-label="Anterior (←)">
                    <!-- ← -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <button type="button" id="lightbox-next"
                        class="absolute top-1/2 -translate-y-1/2 -right-4 md:-right-10 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow focus:outline-none focus:ring-2 focus:ring-red-500"
                        aria-label="Siguiente (→)">
                    <!-- → -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const items = Array.from(document.querySelectorAll('[data-gallery-item]'));
            if (!items.length) return;

            const modal = document.getElementById('lightbox');
            const imgEl = document.getElementById('lightbox-image');
            const captionEl = document.getElementById('lightbox-caption');
            const counterEl = document.getElementById('lightbox-counter');
            const btnClose = document.getElementById('lightbox-close');
            const btnPrev = document.getElementById('lightbox-prev');
            const btnNext = document.getElementById('lightbox-next');

            const images = items.map((el) => ({
                src: el.dataset.src,
                alt: el.dataset.alt || '',
                caption: el.dataset.caption || ''
            }));

            let current = 0;
            let lastFocused = null;
            let touchStartX = 0, touchEndX = 0;

            function open(index) {
                current = (index + images.length) % images.length;
                render();
                lastFocused = document.activeElement;
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.documentElement.style.overflow = 'hidden'; // bloqueo scroll
                btnClose.focus();
            }

            function close() {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.documentElement.style.overflow = '';
                if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
            }

            function render() {
                const it = images[current];
                imgEl.src = it.src;
                imgEl.alt = it.alt;
                captionEl.textContent = it.caption;
                counterEl.textContent = (current + 1) + ' / ' + images.length;
            }

            function prev() { open(current - 1); }
            function next() { open(current + 1); }

            // Click en tarjetas
            items.forEach((el, idx) => {
                el.addEventListener('click', () => open(idx));
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(idx); }
                });
                el.setAttribute('tabindex', '0'); // accesible con teclado
            });

            // Controles
            btnClose.addEventListener('click', close);
            btnPrev.addEventListener('click', () => open(current - 1));
            btnNext.addEventListener('click', () => open(current + 1));

            // Cerrar si clic fuera del contenido (fondo)
            modal.addEventListener('click', (e) => {
                // si se hace click directo en el overlay (no en los controles internos)
                if (e.target === modal) close();
            });

            // Teclado
            document.addEventListener('keydown', (e) => {
                if (modal.classList.contains('hidden')) return;
                if (e.key === 'Escape') close();
                else if (e.key === 'ArrowLeft') prev();
                else if (e.key === 'ArrowRight') next();
            });

            // Swipe en móviles
            imgEl.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
            imgEl.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const dx = touchEndX - touchStartX;
                if (Math.abs(dx) > 50) { dx > 0 ? prev() : next(); }
            }, { passive: true });

            // Prevención de arrastre de imagen
            imgEl.addEventListener('dragstart', (e) => e.preventDefault());
        })();
    </script>

    <script>
        (function () {
            const btnOverlay = document.querySelector('[data-toggle-galeria]');
            const btnToggle = document.getElementById('btn-toggle-galeria');
            const hiddenCards = document.querySelectorAll('[data-galeria-hidden]');
            let expanded = false;

            function setExpanded(state) {
                expanded = state;
                hiddenCards.forEach(el => el.classList.toggle('hidden', !expanded));
                if (btnToggle) {
                    btnToggle.innerHTML = expanded
                        ? `Mostrar menos <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>`
                        : `Mostrar todas <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>`;
                }
            }

            if (btnOverlay) btnOverlay.addEventListener('click', () => setExpanded(true));
            if (btnToggle) btnToggle.addEventListener('click', () => setExpanded(!expanded));
        })();
    </script>


</main>
