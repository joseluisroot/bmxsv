<script>

    // Scroll Spy para destacar el menú activo
    const secciones = document.querySelectorAll("section[id]");
    const enlacesMenu = document.querySelectorAll(".menu-link");

    window.addEventListener("scroll", () => {
        let scrollY = window.pageYOffset;

        secciones.forEach(seccion => {
            const altura = seccion.offsetHeight;
            const top = seccion.offsetTop - 150; // Compensar por header sticky
            const id = seccion.getAttribute("id");

            if (scrollY >= top && scrollY < top + altura) {
                enlacesMenu.forEach(enlace => {
                    enlace.classList.remove("text-red-600", "font-bold");
                    if (enlace.getAttribute("href") === `#${id}`) {
                        enlace.classList.add("text-red-600", "font-bold");
                    }
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            height: 'auto', // o ej. 600
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
            locale: 'es',
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            buttonText: {
                today: 'Hoy' // Puedes cambiarlo a 'Este día' o 'Ir a hoy', etc.
            },
            views: {
                listMonth: {
                    buttonText: 'Lista',
                    noEventsContent: 'No hay eventos programados'
                },
                dayGridMonth: {
                    buttonText: 'Mes'
                }
            },
            allDayText: 'Todo el día',
            events: [
                {
                    title: 'Entrenamiento (Pista San Andrés)',
                    start: '2025-08-02',
                    color: '#34d399',
                    description: 'Entrenamiento libre para todas las categorías.'
                },
                {
                    title: 'Competencia Interna - Fecha 2',
                    start: '2025-07-14',
                    color: '#60a5fa',
                    description: 'Evento interno para clubes locales. Pista BMX Apopa.'
                },
                {
                    title: 'Entrenamiento (Pista San Andrés)',
                    start: '2025-08-02',
                    color: '#34d399'
                },
                {
                    title: 'Competencia Interna - Fecha 2',
                    start: '2025-07-14',
                    color: '#60a5fa'
                },
                {
                    title: 'Vacaciones Agostinas',
                    start: '2025-07-21',
                    end: '2025-07-23',
                    color: '#f87171'
                },
                {
                    title: 'Cuarta Fecha',
                    start: '2025-08-31',
                    color: '#f87171'
                },
                {
                    title: 'Competencia Interna - Fecha 2',
                    start: '2025-08-14',
                    color: '#60a5fa',
                    extendedProps: {
                        descripcionHTML: `
                                        <img src="/images/competencia2.jpg" class="rounded-lg mb-3 w-full" alt="Competencia Fecha 2">
                                        <p>Competencia interna para clubes locales. Habrá medallas y premiación por categoría.</p>
                                        <a href="https://forms.gle/ejemplo" target="_blank" class="inline-block mt-3 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                          Inscribirse
                                        </a>
                                      `
                    }
                }
            ]
        });

        // Mostrar modal
        calendar.on('eventClick', function(info) {
            info.jsEvent.preventDefault();

            const titulo = info.event.title;
            const fecha = new Date(info.event.start).toLocaleDateString('es-SV', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });

            document.getElementById('modalTitulo').innerText = titulo;
            document.getElementById('modalFecha').innerText = `📅 ${fecha}`;

            const contenido = info.event.extendedProps.descripcionHTML || '<p class="text-gray-500">Sin descripción disponible.</p>';
            document.getElementById('modalContenido').innerHTML = contenido;

            const modal = document.getElementById('eventoModal');
            modal.classList.remove('hidden');
            modal.classList.remove('fade-out');
            document.body.classList.add('overflow-hidden');
        });

        function cerrarModal() {
            const modal = document.getElementById('eventoModal');
            modal.classList.add('fade-out');
            document.body.classList.remove('overflow-hidden'); // <- permitir scroll
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('fade-out');
            }, 300);
        }

        // Botón cerrar
        document.getElementById('cerrarModal').addEventListener('click', cerrarModal);

        // Clic fuera del contenido para cerrar
        document.getElementById('eventoModal').addEventListener('click', function (e) {
            const modalContent = document.getElementById('modalContent');
            if (!modalContent.contains(e.target)) {
                cerrarModal();
            }
        });

        calendar.render();

        const btnArriba = document.getElementById("btnArriba");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 400) {
                btnArriba.classList.remove("opacity-0", "pointer-events-none");
            } else {
                btnArriba.classList.add("opacity-0", "pointer-events-none");
            }
        });

        btnArriba.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });

        const siteHeader = document.getElementById('siteHeader');
        const headerInner = document.getElementById('headerInner');
        const logoImg = document.getElementById('logoImg');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                headerInner.classList.replace('py-4', 'py-2');
                logoImg.classList.replace('h-14', 'h-10');
            } else {
                headerInner.classList.replace('py-2', 'py-4');
                logoImg.classList.replace('h-10', 'h-14');
            }
        });
    });

    const toggleBtn = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    const menuLinks = menu.querySelectorAll('a');

    function closeMenu() {
        menu.classList.remove('max-h-[500px]');
        menu.classList.add('max-h-0');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    }

    toggleBtn.addEventListener('click', () => {
        const isOpen = menu.classList.contains('max-h-0');

        if (isOpen) {
            menu.classList.remove('max-h-0');
            menu.classList.add('max-h-[500px]');
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            closeMenu();
        }
    });

    // Cerrar menú al hacer clic en cualquier enlace del menú móvil
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            closeMenu();
        });
    });

    // tailwind.config.js
    /*module.exports = {
        theme: {
            extend: {
                keyframes: {
                    'fade-in-up': {
                        '0%': { opacity: '0', transform: 'translateY(20px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' },
                    }
                },
                animation: {
                    'fade-in-up': 'fade-in-up 0.8s ease-out both'
                }
            }
        }
    }*/

    // Cerrar modal
    document.getElementById('cerrarModal').addEventListener('click', () => {
        document.getElementById('eventoModal').classList.add('hidden');
    });

    /*const rankingData = {
        "julio": [
            {
                tipo: "puntos",
                categoria: "Infantil Masculino",
                atletas: [
                    { posicion: 1, nombre: "Lucas Martínez", valor: 120, club: "Club San Salvador" },
                    { posicion: 2, nombre: "Luis Rafael", valor: 115, club: "Club Ilopango" }
                ]
            },
            {
                tipo: "tiempo",
                categoria: "Infantil Femenino",
                atletas: [
                    { posicion: 1, nombre: "Ana López", valor: "1:23.56", club: "Club Apopa" },
                    { posicion: 2, nombre: "Carla Pérez", valor: "1:25.12", club: "Club Cuscatlán" }
                ]
            }
        ],
        "agosto": [
            {
                tipo: "puntos",
                categoria: "Juvenil Masculino",
                atletas: [
                    { posicion: 1, nombre: "Daniel Chávez", valor: 130, club: "Club Santa Ana" },
                    { posicion: 2, nombre: "Carlos Ruiz", valor: 125, club: "Club La Libertad" }
                ]
            }
        ]
    };

    const tabsContainer = document.getElementById('tabs-container');
    const contentContainer = document.getElementById('ranking-content');

    // Generar Tabs
    Object.keys(rankingData).forEach((mes, index) => {
        const btn = document.createElement('button');
        btn.textContent = mes.charAt(0).toUpperCase() + mes.slice(1);
        btn.setAttribute('data-tab', mes);
        btn.className = `tab-btn px-4 py-2 rounded ${index === 0 ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'} hover:bg-gray-300`;
        tabsContainer.appendChild(btn);
    });

    // Generar contenido
    Object.entries(rankingData).forEach(([mes, rankings], index) => {
        const tabDiv = document.createElement('div');
        tabDiv.id = `tab-${mes}`;
        tabDiv.className = `tab-content ${index !== 0 ? 'hidden' : ''}`;

        rankings.forEach(ranking => {
            const tabla = `
        <div class="mb-8">
          <p class="mb-2 text-sm text-gray-600 italic">Ranking por <strong>${ranking.tipo}</strong> – ${ranking.categoria}</p>
          <div class="overflow-auto rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-700">
              <thead class="bg-gray-100 text-gray-800">
                <tr>
                  <th class="px-4 py-2">#</th>
                  <th class="px-4 py-2">Nombre</th>
                  <th class="px-4 py-2">${ranking.tipo === 'tiempo' ? 'Tiempo' : 'Puntos'}</th>
                  <th class="px-4 py-2">Club</th>
                </tr>
              </thead>
              <tbody>
                ${ranking.atletas.map(atleta => `
                  <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">${atleta.posicion}</td>
                    <td class="px-4 py-2">${atleta.nombre}</td>
                    <td class="px-4 py-2">${atleta.valor}</td>
                    <td class="px-4 py-2">${atleta.club}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </div>
      `;
            tabDiv.innerHTML += tabla;
        });

        contentContainer.appendChild(tabDiv);
    });

    // Funcionalidad de tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedTab = btn.getAttribute('data-tab');

            // Mostrar contenido
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById('tab-' + selectedTab).classList.remove('hidden');

            // Estilos activos
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-red-600', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-800');
            });
            btn.classList.add('bg-red-600', 'text-white');
            btn.classList.remove('bg-gray-200', 'text-gray-800');
        });
    });*/

    const atletasPorPagina = 6;
    let paginaActual = 1;
    let atletasFiltrados = [];

    const contenedor = document.getElementById('contenedor-atletas');
    const inputBuscar = document.getElementById('buscador');
    const filtroClub = document.getElementById('filtro-club');
    const filtroCategoria = document.getElementById('filtro-categoria');
    const btnAnterior = document.getElementById('anterior');
    const btnSiguiente = document.getElementById('siguiente');

    // Obtener opciones únicas para los filtros
    function llenarFiltros() {
        const clubes = [...new Set(atletasData.map(a => a.club))].sort();
        const categorias = [...new Set(atletasData.map(a => a.categoria))].sort();

        clubes.forEach(club => {
            const opt = document.createElement('option');
            opt.value = club;
            opt.textContent = club;
            filtroClub.appendChild(opt);
        });

        categorias.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.textContent = cat;
            filtroCategoria.appendChild(opt);
        });
    }

    // Renderizar tarjetas de atletas
    function mostrarAtletas() {
        contenedor.innerHTML = '';
        const inicio = (paginaActual - 1) * atletasPorPagina;
        const fin = inicio + atletasPorPagina;
        const atletasPagina = atletasFiltrados.slice(inicio, fin);

        atletasPagina.forEach(a => {
            const card = document.createElement('a');
            card.href = `/atleta/${a.slug}`;
            card.className = 'bg-gray-100 p-4 rounded shadow hover:shadow-lg transition block';

            card.innerHTML = `
            <img src="/uploads/${a.foto}.png" alt="${a.nombres}"
                 class="mx-auto mb-3 rounded-full object-cover w-32 h-40">
            <h3 class="text-xl font-display mb-1">${a.nombres}</h3>
            <p class="text-sm text-gray-600 mb-1">Edad: ${a.edad ?? '-'} | Club: ${a.club ?? '-'}</p>
            <p class="text-sm text-gray-700 line-clamp-3">${a.descripcion ?? ''}</p>
        `;
            contenedor.appendChild(card);
        });

        btnAnterior.disabled = paginaActual === 1;
        btnSiguiente.disabled = paginaActual >= Math.ceil(atletasFiltrados.length / atletasPorPagina);
    }

    function aplicarFiltros() {
        const texto = inputBuscar.value.toLowerCase();
        const club = filtroClub.value;
        const categoria = filtroCategoria.value;

        atletasFiltrados = atletasData.filter(a =>
            a.nombres.toLowerCase().includes(texto) &&
            (club === '' || a.club === club) &&
            (categoria === '' || a.categoria === categoria)
        );

        paginaActual = 1;
        mostrarAtletas();
    }

    inputBuscar.addEventListener('input', aplicarFiltros);
    filtroClub.addEventListener('change', aplicarFiltros);
    filtroCategoria.addEventListener('change', aplicarFiltros);

    btnAnterior.addEventListener('click', () => {
        if (paginaActual > 1) {
            paginaActual--;
            mostrarAtletas();
        }
    });

    btnSiguiente.addEventListener('click', () => {
        if (paginaActual < Math.ceil(atletasFiltrados.length / atletasPorPagina)) {
            paginaActual++;
            mostrarAtletas();
        }
    });

    // Inicializar
    llenarFiltros();
    atletasFiltrados = atletasData;
    mostrarAtletas();

    const botones = document.querySelectorAll(".filter-btn");
    const items = document.querySelectorAll(".galeria-item");

    let filtroCategoriaGaleria = "todos";
    let filtroAnio = "todos";

    botones.forEach(btn => {
        btn.addEventListener("click", () => {
            const categoria = btn.dataset.categoria;
            const anio = btn.dataset.anio;

            if (categoria) filtroCategoriaGaleria = categoria;
            if (anio) filtroAnio = anio;

            // Limpiar todos los estilos activos
            botones.forEach(b => {
                b.classList.remove("bg-red-600", "text-white", "shadow-md", "ring-2", "ring-red-400");
                b.classList.add("bg-gray-200", "text-black");
            });

            // Activar estilos visuales para botones seleccionados
            botones.forEach(b => {
                const isCategoria = b.dataset.categoria === filtroCategoriaGaleria;
                const isAnio = b.dataset.anio === filtroAnio;

                if ((b.dataset.categoria && isCategoria) || (b.dataset.anio && isAnio)) {
                    b.classList.remove("bg-gray-200", "text-black");
                    b.classList.add("bg-red-600", "text-white", "shadow-md", "ring-2", "ring-red-400");
                }
            });

            // Mostrar u ocultar imágenes según los filtros
            items.forEach(item => {
                const itemCat = item.dataset.categoria;
                const itemAnio = item.dataset.anio;

                const coincideCategoria = filtroCategoriaGaleria === "todos" || itemCat === filtroCategoriaGaleria;
                const coincideAnio = filtroAnio === "todos" || itemAnio === filtroAnio;

                item.style.display = (coincideCategoria && coincideAnio) ? "block" : "none";
            });
        });
    });

    new Swiper('.swiper', {
        slidesPerView: 1.1,
        spaceBetween: 12,
        loop: false,
        grabCursor: true,
    });

</script>
