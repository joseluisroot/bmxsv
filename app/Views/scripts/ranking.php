<script>
    (function(){
        const tabs = document.querySelectorAll('#tabs-container [data-period-id]');
        const content = document.getElementById('ranking-content');
        const activeClasses = ['bg-red-600','text-white'];
        const inactiveClasses = ['bg-gray-100','text-gray-800'];

        function setActive(btn) {
            tabs.forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
                b.setAttribute('aria-pressed','false');
            });
            btn.classList.remove(...inactiveClasses);
            btn.classList.add(...activeClasses);
            btn.setAttribute('aria-pressed','true');
        }

        async function loadPeriodo(periodId, btn) {
            try {
                // Puedes usar window.BASE_URL si la tienes definida
                const url = '<?= base_url('ranking/periodo') ?>/' + periodId;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Error cargando período');
                const html = await res.text();
                content.innerHTML = html;
                setActive(btn);
                // Scroll suave a la sección (opcional)
                document.getElementById('ranking').scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (e) {
                content.innerHTML = '<p class="text-red-600 text-center">No se pudo cargar el ranking.</p>';
                console.error(e);
            }
        }

        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-period-id');
                if (!id) return;
                loadPeriodo(id, btn);
            });
        });
    })();
</script>
