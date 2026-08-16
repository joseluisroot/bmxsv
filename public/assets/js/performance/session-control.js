const sessionId = document.getElementById('sessionId').value;

async function fetchJson(url, options = {}) {
    const response = await fetch(url, options);
    const data = await response.json();
    return { response, data };
}

async function loadSessionControl() {
    try {
        const sessionResult = await fetchJson(`/api/performance/sessions/${sessionId}`);

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('sessionControlDashboard').classList.remove('hidden');

        if (!sessionResult.response.ok || !sessionResult.data.success) {
            document.getElementById('sessionControlDashboard').innerHTML =
                `<div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-red-300">${sessionResult.data.message ?? 'Sesión no encontrada.'}</div>`;
            return;
        }

        const session = sessionResult.data.session;
        document.getElementById('sessionName').innerText = session?.nombre ?? '--';
        document.getElementById('sessionDate').innerText = session?.fecha ?? '--';
        document.getElementById('sessionStatus').innerText = String(session?.estado ?? '--').toUpperCase();

        const hitsResult = await fetchJson(`/api/performance/session/${sessionId}/hits`);
        const hits = hitsResult.response.ok && hitsResult.data.success ? (hitsResult.data.hits ?? []) : [];

        document.getElementById('hitsCount').innerText = hits.length;
        renderSessionHits(hits);

        const form = document.getElementById('createHitForm');
        const message = document.getElementById('createHitMessage');

        if (session.estado !== 'abierta') {
            form.querySelectorAll('input, select, textarea, button').forEach(el => el.disabled = true);
            message.className = 'text-sm text-yellow-400';
            message.innerText = 'Abre la sesión desde Session Manager para crear hits.';
        } else {
            form.querySelectorAll('input, select, textarea, button').forEach(el => el.disabled = false);
            if (message.dataset.locked === 'true') message.innerText = '';
        }
    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando control de sesión.';
    }
}

function renderSessionHits(hits) {
    const container = document.getElementById('sessionHitsList');
    container.innerHTML = '';

    if (!hits.length) {
        container.innerHTML = `<p class="text-slate-500">No hay hits registrados. Crea el primer hit con el formulario superior.</p>`;
        return;
    }

    hits.forEach(hit => {
        const status = hit.total_seconds !== null && hit.records_count >= 2
            ? 'completado'
            : hit.records_count > 0 ? 'en progreso' : 'pendiente';

        container.innerHTML += `
            <div class="bg-slate-800/60 border border-slate-700 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="font-bold">${hit.athlete?.nombre ?? '--'}</p>
                    <p class="text-xs text-slate-400">
                        Hit #${hit.numero_hit} · ID ${hit.hit_id} · Plato ${hit.bike_setup?.plato ?? '--'} · ${status.toUpperCase()} · Tiempo ${hit.total_seconds ?? '--'}s
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="/performance/session/${sessionId}/simulator" class="px-3 py-1 rounded bg-purple-700 hover:bg-purple-600 text-xs">Simular</a>
                </div>
            </div>`;
    });
}

async function loadAthletes() {
    const { data } = await fetchJson('/api/performance/athletes');
    const select = document.getElementById('athleteId');
    select.innerHTML = '';

    (data.athletes ?? []).forEach(athlete => {
        select.innerHTML += `<option value="${athlete.id}">${athlete.nombres} ${athlete.apellidos ?? ''}</option>`;
    });
}

async function loadConfigurations() {
    const { data } = await fetchJson('/api/performance/configurations');
    const select = document.getElementById('configurationId');
    select.innerHTML = '';

    (data.configurations ?? []).forEach(config => {
        select.innerHTML += `<option value="${config.id}">${config.marca ?? ''} ${config.modelo ?? ''} | ${config.plato}/${config.pinon ?? '--'}</option>`;
    });
}

document.getElementById('createHitForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const message = document.getElementById('createHitMessage');
    message.innerText = 'Creando hit...';

    const payload = {
        athlete_id: Number(document.getElementById('athleteId').value),
        configuration_id: Number(document.getElementById('configurationId').value),
        notas_coach: document.getElementById('notasCoach').value || null,
        sensacion_atleta: document.getElementById('sensacionAtleta').value || null,
    };

    try {
        const { response, data } = await fetchJson(`/api/performance/session/${sessionId}/hit`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        if (!response.ok || !data.success) {
            message.className = 'text-sm text-red-400';
            message.innerText = data.message ?? 'No se pudo crear el hit.';
            return;
        }

        message.className = 'text-sm text-green-400';
        message.innerText = `Hit creado correctamente. ID: ${data.hit_id}`;
        document.getElementById('createHitForm').reset();
        await Promise.all([loadAthletes(), loadConfigurations()]);
        await loadSessionControl();
    } catch (error) {
        console.error(error);
        message.className = 'text-sm text-red-400';
        message.innerText = 'Error creando hit.';
    }
});

Promise.all([loadAthletes(), loadConfigurations()])
    .then(loadSessionControl)
    .catch(error => {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando datos de la sesión.';
    });
