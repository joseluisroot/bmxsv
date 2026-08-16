let timingPoints = [];
let configurations = [];
let sessions = [];

const els = {
    list: document.getElementById('sessionsList'),
    loading: document.getElementById('sessionsLoading'),
    editor: document.getElementById('sessionEditor'),
    message: document.getElementById('sessionMessage'),
};

async function api(url, options = {}) {
    const response = await fetch(url, {
        headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
        ...options,
    });
    const data = await response.json();
    if (!response.ok || data.success === false) {
        throw new Error(data.message || 'Error en la solicitud.');
    }
    return data;
}

function showMessage(message, type = 'success') {
    els.message.className = 'mb-4 rounded-lg border px-4 py-3 text-sm ' + (
        type === 'error'
            ? 'border-red-500/30 bg-red-500/10 text-red-300'
            : 'border-green-500/30 bg-green-500/10 text-green-300'
    );
    els.message.textContent = message;
    els.message.classList.remove('hidden');
}

async function loadCatalogs() {
    const [pointsData, configData] = await Promise.all([
        api('/api/performance/sessions/timing-points'),
        api('/api/performance/configurations'),
    ]);

    timingPoints = pointsData.timing_points || [];
    configurations = configData.configurations || configData.data || [];

    fillPointSelect('startNodeId');
    fillPointSelect('endNodeId');

    const configSelect = document.getElementById('defaultConfigurationId');
    configSelect.innerHTML = '<option value="">Sin configuración por defecto</option>';
    configurations.forEach(item => {
        const id = item.id ?? item.configuracion_id;
        const plato = item.plato ?? '--';
        const pinon = item.pinon ?? '--';
        const bike = item.bicicleta ?? item.modelo ?? '';
        configSelect.innerHTML += `<option value="${id}">${bike ? bike + ' · ' : ''}Plato ${plato} / Piñón ${pinon}</option>`;
    });
}

function fillPointSelect(id) {
    const select = document.getElementById(id);
    select.innerHTML = '<option value="">Seleccionar punto...</option>';
    timingPoints.forEach(point => {
        select.innerHTML += `<option value="${point.id}">${point.codigo} · ${point.nombre}</option>`;
    });
}

async function loadSessions() {
    els.loading.classList.remove('hidden');
    els.list.classList.add('hidden');

    const data = await api('/api/performance/sessions');
    sessions = data.sessions || [];
    renderSessions();

    els.loading.classList.add('hidden');
    els.list.classList.remove('hidden');
}

function renderSessions() {
    els.list.innerHTML = '';

    if (!sessions.length) {
        els.list.innerHTML = '<div class="text-slate-500">No hay sesiones creadas todavía.</div>';
        return;
    }

    sessions.forEach(session => {
        const stateClass = session.estado === 'abierta'
            ? 'text-green-400 border-green-500/30 bg-green-500/10'
            : session.estado === 'cerrada'
                ? 'text-slate-400 border-slate-600 bg-slate-800'
                : 'text-yellow-400 border-yellow-500/30 bg-yellow-500/10';

        const start = session.start_node?.codigo ?? '--';
        const end = session.end_node?.codigo ?? '--';

        els.list.innerHTML += `
            <article class="border border-slate-800 rounded-xl p-4 bg-slate-950/40">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-lg font-bold">${escapeHtml(session.nombre || 'Sesión')}</h3>
                            <span class="text-xs px-2 py-1 rounded-full border ${stateClass}">${String(session.estado || 'borrador').toUpperCase()}</span>
                        </div>
                        <p class="text-sm text-slate-400 mt-1">${session.fecha || '--'} · ${escapeHtml(session.pista || 'Sin pista')}</p>
                        <p class="text-xs text-slate-500 mt-2">Timing: ${start} → ${end} · Modo: ${session.modo_hits || 'manual'} · ID: ${session.id}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="editSession(${session.id})" class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded-lg text-sm">Editar</button>
                        ${session.estado !== 'abierta' ? `<button onclick="setSessionStatus(${session.id}, 'abierta')" class="bg-green-700 hover:bg-green-600 px-3 py-2 rounded-lg text-sm">Abrir</button>` : `<button onclick="setSessionStatus(${session.id}, 'cerrada')" class="bg-orange-700 hover:bg-orange-600 px-3 py-2 rounded-lg text-sm">Cerrar</button>`}
                        <a href="/performance/session/${session.id}/control" class="bg-cyan-700 hover:bg-cyan-600 px-3 py-2 rounded-lg text-sm">Control / Hits</a>
                        <a href="/performance/session/${session.id}/simulator" class="bg-purple-700 hover:bg-purple-600 px-3 py-2 rounded-lg text-sm">Simulador</a>
                        <a href="/performance/coach/${session.id}" class="bg-blue-700 hover:bg-blue-600 px-3 py-2 rounded-lg text-sm">Coach</a>
                        <a href="/performance/session/${session.id}/live" class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded-lg text-sm">Live</a>
                    </div>
                </div>
            </article>`;
    });
}

function openNewSession() {
    document.getElementById('sessionForm').reset();
    document.getElementById('sessionEditId').value = '';
    document.getElementById('editorTitle').textContent = 'Nueva sesión';
    document.getElementById('sessionDate').value = new Date().toISOString().slice(0, 10);
    document.getElementById('sessionStatus').value = 'borrador';
    document.getElementById('hitMode').value = 'manual';
    document.getElementById('autoCloseHit').checked = true;
    els.editor.classList.remove('hidden');
    els.editor.scrollIntoView({ behavior: 'smooth' });
}

function editSession(id) {
    const session = sessions.find(item => Number(item.id) === Number(id));
    if (!session) return;

    document.getElementById('sessionEditId').value = session.id;
    document.getElementById('editorTitle').textContent = `Editar sesión #${session.id}`;
    document.getElementById('sessionName').value = session.nombre || '';
    document.getElementById('sessionDate').value = session.fecha || '';
    document.getElementById('sessionTrack').value = session.pista || '';
    document.getElementById('sessionCoach').value = session.coach || '';
    document.getElementById('startNodeId').value = session.nodo_inicio_id || '';
    document.getElementById('endNodeId').value = session.nodo_fin_id || '';
    document.getElementById('hitMode').value = session.modo_hits || 'manual';
    document.getElementById('defaultConfigurationId').value = session.configuracion_bicicleta_default_id || '';
    document.getElementById('sessionStatus').value = session.estado || 'borrador';
    document.getElementById('autoCloseHit').checked = Number(session.auto_close_hit || 0) === 1;
    document.getElementById('sessionObjective').value = session.objetivo || '';
    document.getElementById('sessionNotes').value = session.notas || '';

    els.editor.classList.remove('hidden');
    els.editor.scrollIntoView({ behavior: 'smooth' });
}

async function saveSession(event) {
    event.preventDefault();

    const id = document.getElementById('sessionEditId').value;
    const payload = {
        nombre: document.getElementById('sessionName').value.trim(),
        fecha: document.getElementById('sessionDate').value,
        pista: document.getElementById('sessionTrack').value.trim() || null,
        coach: document.getElementById('sessionCoach').value.trim() || null,
        nodo_inicio_id: Number(document.getElementById('startNodeId').value),
        nodo_fin_id: Number(document.getElementById('endNodeId').value),
        modo_hits: document.getElementById('hitMode').value,
        configuracion_bicicleta_default_id: document.getElementById('defaultConfigurationId').value ? Number(document.getElementById('defaultConfigurationId').value) : null,
        estado: document.getElementById('sessionStatus').value,
        auto_close_hit: document.getElementById('autoCloseHit').checked,
        objetivo: document.getElementById('sessionObjective').value.trim() || null,
        notas: document.getElementById('sessionNotes').value.trim() || null,
    };

    try {
        if (id) {
            await api(`/api/performance/sessions/${id}`, { method: 'PUT', body: JSON.stringify(payload) });
            showMessage('Sesión actualizada correctamente.');
        } else {
            const data = await api('/api/performance/sessions', { method: 'POST', body: JSON.stringify(payload) });
            showMessage(`Sesión creada correctamente. ID: ${data.session?.id ?? '--'}`);
        }

        els.editor.classList.add('hidden');
        await loadSessions();
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

async function setSessionStatus(id, estado) {
    try {
        await api(`/api/performance/sessions/${id}/status`, {
            method: 'POST',
            body: JSON.stringify({ estado }),
        });
        showMessage(`Sesión ${estado} correctamente.`);
        await loadSessions();
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

window.editSession = editSession;
window.setSessionStatus = setSessionStatus;

document.getElementById('newSessionBtn').addEventListener('click', openNewSession);
document.getElementById('refreshSessionsBtn').addEventListener('click', () => loadSessions().catch(error => showMessage(error.message, 'error')));
document.getElementById('closeEditorBtn').addEventListener('click', () => els.editor.classList.add('hidden'));
document.getElementById('cancelSessionBtn').addEventListener('click', () => els.editor.classList.add('hidden'));
document.getElementById('sessionForm').addEventListener('submit', saveSession);

(async function init() {
    try {
        await loadCatalogs();
        await loadSessions();
    } catch (error) {
        showMessage(error.message, 'error');
        els.loading.textContent = 'No se pudo cargar el Session Manager.';
    }
})();
