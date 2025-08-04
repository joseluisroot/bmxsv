<script>
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


</script>
