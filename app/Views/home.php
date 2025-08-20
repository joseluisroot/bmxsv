<!-- Hero Section -->
<section class="relative min-h-[90vh] md:h-screen bg-cover bg-center shadow-md"
         style="background-image: url('/images/hero.jpg')">
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
<section id="agenda" class="py-16 bg-white shadow-md scroll-mt-24">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Agenda de Carreras</h2>

        <div class="bg-white rounded-2xl shadow-lg p-4 overflow-auto">
            <div id="calendar" class="text-sm md:text-base"></div>
        </div>
    </div>
</section>

<!-- Resultados -->
<section id="resultados" class="bg-white py-16 px-6 shadow-md scroll-mt-24">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">Resultados Recientes</h2>

            <?php if (!empty($ultimaCompetencia)): ?>
                <div class="text-sm text-gray-600">
                    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 border">
                        <?= esc($ultimaCompetencia['nombre']) ?>
                        <?php if (!empty($ultimaCompetencia['sede'])): ?>
                            · <?= esc($ultimaCompetencia['sede']) ?>
                        <?php endif; ?>
                        · <?= date('d M Y', strtotime($ultimaCompetencia['fecha'])) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($ultimaCompetencia)): ?>
            <p class="text-gray-600">Aún no hay competencias registradas.</p>
        <?php else: ?>
            <?php if (empty($ganadoresPorCategoria)): ?>
                <p class="text-gray-600">No hay resultados cargados para esta competencia.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-2">Categoría</th>
                            <th class="p-2">Ganador</th>
                            <th class="p-2">Tiempo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ganadoresPorCategoria as $row): ?>
                            <tr class="border-t">
                                <td class="p-2"><?= esc($row['categoria']) ?></td>
                                <td class="p-2">
                                    <?php $url = route_to('atleta_perfil', $row['slug']); ?>
                                    <a href="<?= esc($url) ?>"
                                       class="font-semibold hover:underline hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 rounded"
                                       aria-label="Ver perfil de <?= esc($row['nombres'] . ' ' . $row['apellidos']) ?>, categoría <?= esc($row['categoria']) ?>">
                                        <?= esc($row['nombres'] . ' ' . $row['apellidos']) ?>
                                    </a>
                                </td>

                                <td class="p-2">
                                    <?= esc(format_time_ms($row['tiempo_ms'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Ranking -->
<section id="ranking" class="py-16 px-6 bg-white shadow-md scroll-mt-24">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-8 text-center">
            Ranking Mensual de Atletas
        </h2>

        <div id="tabs-container" class="flex flex-wrap justify-center gap-2 mb-6">
            <?php foreach ($rankingTabs as $tab):
                $isActive = ($rankingPeriodo && $tab['id'] === $rankingPeriodo['id']);
                $label = $tab['nombre_publico'] ?: (sprintf('%02d', $tab['mes']) . '/' . $tab['anio']);
                ?>
                <button
                        type="button"
                        class="px-3 py-1 rounded-full border <?= $isActive ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-800' ?>"
                        data-period-id="<?= (int)$tab['id'] ?>"
                        aria-pressed="<?= $isActive ? 'true' : 'false' ?>"
                >
                    <?= esc($label) ?>
                </button>
            <?php endforeach; ?>
        </div>


        <div id="ranking-content">
            <?= view('partials/ranking_content', ['periodo' => $rankingPeriodo, 'agrupado' => $rankingDatos]) ?>
        </div>
    </div>
</section>

<!-- Atletas -->
<script>
    <?php

    $atletas = null;

    ?>
    const atletasData = <?= json_encode($atletas) ?>;
</script>


<section id="atletas" class="bg-white py-16 px-6 shadow-md scroll-mt-24">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-10 text-center">Nuestros Atletas</h2>

        <p class="text-center text-gray-700 max-w-3xl mx-auto mb-10 text-base sm:text-lg">
            Conoce a los atletas que representan el espíritu del BMX salvadoreño. Cada uno de ellos entrena con pasión,
            disciplina y entrega para destacar en cada competencia nacional e internacional.
        </p>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 justify-center mb-6">
            <select id="filtro-club" class="px-4 py-2 rounded border text-sm">
                <option value="">Todos los clubes</option>
                <!-- opciones generadas dinámicamente -->
            </select>

            <select id="filtro-categoria" class="px-4 py-2 rounded border text-sm">
                <option value="">Todas las categorías</option>
                <!-- opciones generadas dinámicamente -->
            </select>

            <input type="text" id="buscador" placeholder="Buscar atleta..."
                   class="px-4 py-2 border rounded text-sm w-full sm:w-auto">
        </div>

        <!-- Contenedor de tarjetas -->
        <?php if (empty($atletas)): ?>
            <p class="text-center text-gray-600 text-lg mb-6">
                No hay atletas registrados en este momento.
            </p>
        <?php else: ?>
            <div id="contenedor-atletas"
                 class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">
                <!-- Tarjetas insertadas vía JS -->
            </div>
        <?php endif; ?>

        <!-- Paginación -->
        <?php if ($atletas != null ): ?>
        <div class="flex justify-center mt-8">
            <button id="anterior" class="px-4 py-2 border rounded-l text-sm bg-gray-100 hover:bg-gray-200">Anterior
            </button>
            <button id="siguiente" class="px-4 py-2 border rounded-r text-sm bg-gray-100 hover:bg-gray-200">Siguiente
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Galería (Destacados en Home) -->
<section id="galeria" class="bg-gray-50 py-16 px-6 shadow-md scroll-mt-24">
    <div class="container mx-auto">
        <h2 class="text-3xl sm:text-4xl font-display text-red-600 mb-10 text-center">Galería destacada</h2>

        <?php if (empty($galeriaDestacados)): ?>
            <p class="text-center text-gray-600">No hay elementos destacados en este momento.</p>
        <?php else: ?>
            <!-- Barra de filtros simple (cliente) -->
            <div class="flex flex-wrap gap-2 justify-center mb-6">
                <button data-categoria="todos" class="filter-btn bg-red-600 text-white px-4 py-2 rounded-full text-sm">Todos</button>
                <button data-categoria="campeonato" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Campeonato</button>
                <button data-categoria="entrenamiento" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Entrenamiento</button>
                <button data-categoria="openhouse" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Open House</button>

                <button data-anio="todos" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm">Todos los años</button>
                <?php
                $aniosHome = array_values(array_unique(array_filter(array_map(fn($i)=>$i['anio']??null,$galeriaDestacados))));
                rsort($aniosHome);
                foreach ($aniosHome as $y):
                    ?>
                    <button data-anio="<?= esc($y) ?>" class="filter-btn bg-gray-200 px-4 py-2 rounded-full text-sm"><?= esc($y) ?></button>
                <?php endforeach; ?>
            </div>

            <!-- Slider (mobile) + grid (md+) -->
            <div class="swiper md:hidden">
                <div class="swiper-wrapper">
                    <?php foreach ($galeriaDestacados as $idx => $it): ?>
                        <div class="swiper-slide">
                            <article
                                    class="relative bg-white rounded-xl shadow border overflow-hidden galeria-item"
                                    data-index="<?= $idx ?>"
                                    data-categoria="<?= esc(strtolower($it['categoria'] ?? '')) ?>"
                                    data-anio="<?= esc($it['anio'] ?? '') ?>"
                                    data-tipo="<?= esc($it['tipo']) ?>"
                                    data-title="<?= esc($it['titulo'] ?: ($it['tipo'] === 'video' ? 'Video' : 'Foto')) ?>"
                                    data-img="<?= esc(base_url($it['src'] ?: $it['thumb'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                                    data-thumb="<?= esc(base_url($it['thumb'] ?: $it['src'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                                    data-embed="<?php
                                    $embed = '';
                                    if ($it['tipo']==='video') {
                                        if (($it['video_provider'] ?? '') === 'youtube' && !empty($it['video_id'])) {
                                            $embed = "https://www.youtube.com/embed/{$it['video_id']}?rel=0&modestbranding=1";
                                        } elseif (!empty($it['video_url'])) {
                                            $embed = $it['video_url'];
                                        }
                                    }
                                    echo esc($embed);
                                    ?>"
                            >
                                <?php if (!empty($it['destacado'])): ?>
                                    <span class="absolute top-2 left-2 z-10 text-[11px] uppercase tracking-wider bg-yellow-400 text-black px-2 py-1 rounded">Destacado</span>
                                <?php endif; ?>

                                <?php if ($it['tipo'] === 'video'): ?>
                                    <button type="button" class="block w-full group open-modal-slide">
                                        <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
                                            <img src="<?= esc(base_url($it['thumb'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                                                 alt="<?= esc($it['alt'] ?? $it['titulo'] ?? 'Video') ?>"
                                                 class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                                 loading="lazy">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition">
                                                <svg class="w-14 h-14 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="block w-full group open-modal-slide">
                                        <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
                                            <img src="<?= esc(base_url($it['thumb'] ?: $it['src'])) ?>"
                                                 alt="<?= esc($it['alt'] ?? $it['titulo'] ?? 'Foto') ?>"
                                                 class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                                 loading="lazy">
                                        </div>
                                    </button>
                                <?php endif; ?>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-1"><?= esc($it['titulo'] ?: ($it['tipo']==='video'?'Video':'Foto')) ?></h3>
                                    <p class="text-sm text-gray-600"><?= esc($it['categoria'] ?: 'General') ?> · <?= esc($it['anio'] ?: '') ?></p>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="hidden md:grid md:grid-cols-3 gap-6" id="galeria-grid">
                <?php foreach ($galeriaDestacados as $idx => $it): ?>
                    <!-- misma card que arriba, sin .swiper-slide -->
                    <article
                            class="relative bg-white rounded-xl shadow border overflow-hidden galeria-item"
                            data-index="<?= $idx ?>"
                            data-categoria="<?= esc(strtolower($it['categoria'] ?? '')) ?>"
                            data-anio="<?= esc($it['anio'] ?? '') ?>"
                            data-tipo="<?= esc($it['tipo']) ?>"
                            data-title="<?= esc($it['titulo'] ?: ($it['tipo'] === 'video' ? 'Video' : 'Foto')) ?>"
                            data-img="<?= esc(base_url($it['src'] ?: $it['thumb'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                            data-thumb="<?= esc(base_url($it['thumb'] ?: $it['src'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                            data-embed="<?php
                            $embed = '';
                            if ($it['tipo']==='video') {
                                if (($it['video_provider'] ?? '') === 'youtube' && !empty($it['video_id'])) {
                                    $embed = "https://www.youtube.com/embed/{$it['video_id']}?rel=0&modestbranding=1";
                                } elseif (!empty($it['video_url'])) {
                                    $embed = $it['video_url'];
                                }
                            }
                            echo esc($embed);
                            ?>"
                    >
                        <?php if (!empty($it['destacado'])): ?>
                            <span class="absolute top-2 left-2 z-10 text-[11px] uppercase tracking-wider bg-yellow-400 text-black px-2 py-1 rounded">Destacado</span>
                        <?php endif; ?>

                        <?php if ($it['tipo'] === 'video'): ?>
                            <button type="button" class="block w-full group open-modal-slide">
                                <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
                                    <img src="<?= esc(base_url($it['thumb'] ?: 'assets/img/galeria-placeholder.jpg')) ?>"
                                         alt="<?= esc($it['alt'] ?? $it['titulo'] ?? 'Video') ?>"
                                         class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                         loading="lazy">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition">
                                        <svg class="w-14 h-14 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </button>
                        <?php else: ?>
                            <button type="button" class="block w-full group open-modal-slide">
                                <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
                                    <img src="<?= esc(base_url($it['thumb'] ?: $it['src'])) ?>"
                                         alt="<?= esc($it['alt'] ?? $it['titulo'] ?? 'Foto') ?>"
                                         class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                         loading="lazy">
                                </div>
                            </button>
                        <?php endif; ?>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1"><?= esc($it['titulo'] ?: ($it['tipo']==='video'?'Video':'Foto')) ?></h3>
                            <p class="text-sm text-gray-600"><?= esc($it['categoria'] ?: 'General') ?> · <?= esc($it['anio'] ?: '') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal slider -->
    <div id="galeria-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="relative bg-black rounded-xl shadow-xl w-[95%] max-w-5xl">
            <button type="button" class="absolute -top-10 right-0 text-white hover:text-red-400" id="galeria-close" aria-label="Cerrar">✕ Cerrar</button>
            <div class="swiper" id="galeria-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($galeriaDestacados as $it): ?>
                        <div class="swiper-slide">
                            <?php if ($it['tipo']==='video'): ?>
                                <div class="relative aspect-video bg-black flex items-center justify-center">
                                    <iframe class="w-full h-full" data-embed="<?php
                                    $embed = '';
                                    if (($it['video_provider'] ?? '') === 'youtube' && !empty($it['video_id'])) {
                                        $embed = "https://www.youtube.com/embed/{$it['video_id']}?rel=0&modestbranding=1";
                                    } elseif (!empty($it['video_url'])) {
                                        $embed = $it['video_url'];
                                    }
                                    echo esc($embed);
                                    ?>" src="" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                </div>
                            <?php else: ?>
                                <div class="relative bg-black">
                                    <img src="<?= esc(base_url($it['src'] ?: $it['thumb'])) ?>" alt="<?= esc($it['alt'] ?? $it['titulo'] ?? 'Foto') ?>" class="block max-h-[75vh] mx-auto" loading="lazy">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev text-white"></div>
                <div class="swiper-button-next text-white"></div>
                <div class="swiper-pagination !bottom-2"></div>
            </div>
        </div>
    </div>
</section>

<script>
    (function(){
        // Swiper mobile (cards)
        const mobileSwiper = document.querySelector('#galeria .swiper:not(#galeria-swiper)');
        if (mobileSwiper) {
            new Swiper(mobileSwiper, { slidesPerView: 1.1, spaceBetween: 12, loop: false, grabCursor: true });
        }

        // Filtros (cliente)
        const botones = document.querySelectorAll("#galeria .filter-btn");
        const items = document.querySelectorAll("#galeria .galeria-item");
        let filtroCategoriaGaleria = "todos";
        let filtroAnio = "todos";

        function applyActiveStyles(){
            botones.forEach(b=>{
                b.classList.remove("bg-red-600","text-white","shadow-md","ring-2","ring-red-400");
                b.classList.add("bg-gray-100","text-black");
            });
            botones.forEach(b=>{
                const isCategoria = b.dataset.categoria && b.dataset.categoria === filtroCategoriaGaleria;
                const isAnio = b.dataset.anio && b.dataset.anio === filtroAnio;
                if (isCategoria || isAnio) {
                    b.classList.remove("bg-gray-100","text-black");
                    b.classList.add("bg-red-600","text-white","shadow-md","ring-2","ring-red-400");
                }
            });
        }

        function applyFilters(){
            items.forEach(item=>{
                const cat = item.getAttribute('data-categoria') || '';
                const anio = item.getAttribute('data-anio') || '';
                const okCat = (filtroCategoriaGaleria==='todos') || (cat===filtroCategoriaGaleria);
                const okAnio = (filtroAnio==='todos') || (anio===filtroAnio);
                item.style.display = (okCat && okAnio) ? 'block' : 'none';
            });
        }

        botones.forEach(btn=>{
            btn.addEventListener("click", ()=>{
                const categoria = btn.dataset.categoria;
                const anio = btn.dataset.anio;
                if (categoria) filtroCategoriaGaleria = categoria;
                if (anio) filtroAnio = anio;
                applyActiveStyles();
                applyFilters();
            });
        });
        applyActiveStyles();

        // Modal + Swiper grande (fotos/videos)
        const modal  = document.getElementById('galeria-modal');
        const close  = document.getElementById('galeria-close');
        const bigSwiperEl = document.getElementById('galeria-swiper');
        let bigSwiper = null;

        function openModal(index){
            if (!bigSwiper) {
                bigSwiper = new Swiper(bigSwiperEl, {
                    loop: false,
                    slidesPerView: 1,
                    spaceBetween: 0,
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    on: {
                        slideChange: manageVideo,
                        init: manageVideo
                    }
                });
            }
            modal.classList.remove('hidden'); modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            bigSwiper.slideTo(index, 0);
            manageVideo();
        }

        function closeModal(){
            modal.classList.add('hidden'); modal.classList.remove('flex');
            document.body.style.overflow = '';
            // detener video
            const iframes = bigSwiperEl.querySelectorAll('iframe');
            iframes.forEach(f=> f.src = '');
        }

        function manageVideo(){
            const slides = bigSwiperEl.querySelectorAll('.swiper-slide');
            slides.forEach((slide, i)=>{
                const iframe = slide.querySelector('iframe');
                if (!iframe) return;
                if (i === bigSwiper.activeIndex) {
                    const url = iframe.getAttribute('data-embed');
                    if (url) iframe.src = url + (url.includes('?') ? '&' : '?') + 'autoplay=1';
                } else {
                    iframe.src = '';
                }
            });
        }

        document.querySelectorAll('#galeria .open-modal-slide').forEach(btn=>{
            btn.addEventListener('click', (e)=>{
                const article = e.currentTarget.closest('.galeria-item');
                const index = parseInt(article.getAttribute('data-index') || '0', 10);
                openModal(index);
            });
        });
        close.addEventListener('click', closeModal);
        modal.addEventListener('click', (e)=>{
            if (e.target === modal || e.target.classList.contains('bg-black/70')) closeModal();
        });
    })();
</script>

<!-- Noticias -->
<section id="noticias" class="bg-white py-16 px-6 shadow-md scroll-mt-24">
    <div class="container mx-auto">
        <h2 class="text-4xl font-display mb-6 text-center">Noticias</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($ultimasNoticias as $noticia): ?>
                <a href="<?= base_url('noticias/' . esc($noticia['slug'])) ?>"
                   class="block bg-gray-100 rounded shadow hover:shadow-lg transition overflow-hidden">
                    <img src="<?= base_url('uploads/noticias/' . $noticia['imagen_destacada']) ?>"
                         alt="<?= esc($noticia['titulo']) ?>"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-display mb-1"><?= esc($noticia['titulo']) ?></h3>
                        <p class="text-sm text-gray-600 mb-2">Publicado
                            el <?= date('d M Y', strtotime($noticia['fecha_publicacion'])) ?></p>
                        <p class="text-gray-800"><?= esc($noticia['resumen']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Botón para ver más noticias -->
        <div class="mt-10 text-center">
            <a href="<?= base_url('noticias') ?>"
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-full transition">
                Ver más noticias
            </a>
        </div>
    </div>
</section>

<!-- Horarios de Entrenamiento -->
<section id="horarios" class="bg-gray-50 py-16 px-6 scroll-mt-24">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-4xl font-display mb-8 text-center text-red-600">Horarios de Entrenamiento</h2>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Horario semanal -->
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Días y Horarios</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><strong>Lunes, Miércoles y Viernes:</strong> 4:00 PM – 6:00 PM</li>
                    <li><strong>Sábados:</strong> 9:00 AM – 11:00 AM</li>
                </ul>
            </div>

            <!-- Categorías y entrenadores -->
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Categorías y Entrenadores</h3>
                <ul class="space-y-3 text-gray-700">
                    <li>
                        <span class="block font-bold text-red-600">Championship (10–20 años)</span>
                        <span>Entrenador: Federico Polo</span>
                    </li>
                    <li>
                        <span class="block font-bold text-red-600">Inicial (3–9 años)</span>
                        <span>Entrenador: Hugo Rubio</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Únete -->
<section id="unete" class="bg-red-600 text-white py-20 px-6 scroll-mt-24">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-display mb-6">¿Quieres unirte al BMX?</h2>
        <p class="mb-6 text-lg">Conoce cómo formar parte del equipo y comenzar tu aventura en el bicicross.</p>

        <a href="https://wa.me/50379146855?text=Hola%2C%20quiero%20unirme%20al%20equipo%20de%20BMX%20Race%20El%20Salvador%21"
           target="_blank"
           class="bg-white text-red-600 font-bold py-3 px-6 rounded-full transition hover:bg-gray-200 inline-flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-xl"></i>
            Escríbenos por WhatsApp
        </a>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="bg-gray-900 text-white py-16 px-6 scroll-mt-24">
    <div class="container mx-auto max-w-3xl">
        <h2 class="text-4xl font-display mb-6 text-center">Contacto</h2>

        <!-- Formulario -->
        <form action="<?= base_url('contacto/enviar') ?>" method="POST"
              class="space-y-4 bg-gray-800 p-6 rounded shadow">
            <div>
                <label for="nombre" class="block mb-1 font-semibold">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required
                       class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="email" class="block mb-1 font-semibold">Correo electrónico</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label for="mensaje" class="block mb-1 font-semibold">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="4" required
                          class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="text-center pt-4">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition">
                    Enviar mensaje
                </button>
            </div>
        </form>

        <!-- Redes sociales -->
        <div class="mt-10 text-center">
            <p class="text-lg font-semibold mb-4">Síguenos en nuestras redes:</p>
            <div class="flex justify-center space-x-6 text-3xl">
                <a href="https://www.facebook.com/ESAbicicross" target="_blank" class="hover:text-red-500">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.instagram.com/esabicicross" target="_blank" class="hover:text-red-500">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Patrocinadores -->
<section id="patrocinadores" class="bg-white py-20 px-6 scroll-mt-24">
    <div class="container mx-auto max-w-5xl text-center">
        <h2 class="text-4xl font-display mb-6 text-red-600">Patrocinadores</h2>
        <p class="mb-10 text-gray-700 text-lg">
            Apoya el desarrollo del BMX en El Salvador. Tu marca puede formar parte del crecimiento de jóvenes talentos
            nacionales.
        </p>

        <!-- Simulación de logos -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
            <div class="bg-gray-100 rounded shadow p-6 flex items-center justify-center h-24">
                <span class="text-gray-400 italic">Tu logo aquí</span>
            </div>
        </div>

        <!-- Botón de contacto -->
        <a href="https://wa.me/50370123456?text=Hola%2C%20estoy%20interesado%20en%20patrocinar%20el%20proyecto%20BMX%20Race%20El%20Salvador"
           target="_blank"
           class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full transition inline-flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-xl"></i>
            Quiero ser patrocinador
        </a>
    </div>
</section>

<!-- Modal Detalle Evento -->
<div id="eventoModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
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

