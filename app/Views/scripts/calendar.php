<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: [
                {
                    title: 'Entrenamiento (Pista Las Delicias)',
                    start: '2025-07-10',
                    color: '#34d399'
                },
                {
                    title: 'Competencia Interna - Fecha 2',
                    start: '2025-07-14',
                    color: '#60a5fa'
                },
                {
                    title: 'UCI - Campeonato Nacional',
                    start: '2025-07-21',
                    end: '2025-07-23',
                    color: '#f87171'
                },
                {
                    title: 'Cuarta Fecha',
                    start: '2025-08-31',
                    end: '2025-08-31',
                    color: '#f87171'
                }
            ]
        });
        calendar.render();
    });
</script>
