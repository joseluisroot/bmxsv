<script>
    (function () {
        // Selecciona solo el contenedor del FAQ si quieres limitar el alcance
        const faqRoot = document.querySelector('main');
        if (!faqRoot) return;

        const details = Array.from(faqRoot.querySelectorAll('details'));
        if (!details.length) return;

        // 1) IDs predecibles para deep-link (si no los pusiste manualmente)
        details.forEach((d, i) => {
            if (!d.id) {
                const txt = d.querySelector('summary')?.textContent?.trim() || `faq-${i+1}`;
                d.id = 'faq-' + txt.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g,'')   // quita tildes
                    .replace(/[^a-z0-9]+/g,'-')                        // espacios -> guiones
                    .replace(/^-+|-+$/g,'');                           // recorta guiones
            }
        });

        // 2) Abrir pregunta si hay #hash en la URL
        function openFromHash() {
            const hash = decodeURIComponent(location.hash.replace('#','').trim());
            if (!hash) return;
            const target = document.getElementById(hash);
            if (target && target.tagName.toLowerCase() === 'details') {
                // Cierra las demás (opcional)
                details.forEach(d => { if (d !== target) d.open = false; });
                target.open = true;
                // Scroll ajustado por header sticky
                const header = document.getElementById('siteHeader');
                const offset = header ? header.getBoundingClientRect().height + 12 : 80;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        }
        window.addEventListener('hashchange', openFromHash);
        openFromHash(); // por si llegas con hash directo

        // 3) Botones "Abrir todo / Cerrar todo"
        // Si no quieres los botones, comenta este bloque y el HTML que inserta.
        const controls = document.createElement('div');
        controls.className = 'max-w-3xl mx-auto mb-4 flex items-center gap-2 justify-end';
        controls.innerHTML = `
    <button type="button" id="faq-open-all"
      class="px-3 py-2 rounded-lg bg-gray-100 text-gray-800 text-sm font-semibold hover:bg-gray-200">
      Abrir todo
    </button>
    <button type="button" id="faq-close-all"
      class="px-3 py-2 rounded-lg bg-gray-100 text-gray-800 text-sm font-semibold hover:bg-gray-200">
      Cerrar todo
    </button>
  `;

        // Inserta los botones justo antes del primer <details>
        const firstDetails = details[0];
        if (firstDetails && firstDetails.parentElement) {
            firstDetails.parentElement.insertBefore(controls, firstDetails);
        }

        document.getElementById('faq-open-all')?.addEventListener('click', () => {
            details.forEach(d => d.open = true);
        });
        document.getElementById('faq-close-all')?.addEventListener('click', () => {
            details.forEach(d => d.open = false);
            // Si venías con hash, quítalo para no reabrir
            if (location.hash) history.replaceState(null, '', location.pathname + location.search);
        });

        // 4) Al dar clic en un summary, copia el hash (para compartir el enlace a esa pregunta)
        details.forEach(d => {
            const s = d.querySelector('summary');
            if (!s) return;
            s.addEventListener('click', (e) => {
                // Espera al toggle
                setTimeout(() => {
                    history.replaceState(null, '', '#' + d.id);
                }, 0);
            });
        });
    })();
</script>
