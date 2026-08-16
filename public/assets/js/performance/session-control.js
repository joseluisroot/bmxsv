const sessionId = document.getElementById('sessionId').value;

async function loadSessionControl() {
try {
const response = await fetch(`/api/timing/session/${sessionId}/summary`);
const data = await response.json();

document.getElementById('loading').classList.add('hidden');
document.getElementById('sessionControlDashboard').classList.remove('hidden');

if (!data.success) {
document.getElementById('sessionControlDashboard').innerHTML =
`<div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-red-300">${data.message}</div>`;
return;
}

document.getElementById('sessionName').innerText = data.session?.nombre ?? '--';
document.getElementById('sessionDate').innerText = data.session?.fecha ?? '--';
document.getElementById('sessionStatus').innerText = 'Abierta';
document.getElementById('hitsCount').innerText = data.hits_count ?? 0;

renderSessionHits(data.hits ?? []);

} catch (error) {
console.error(error);
document.getElementById('loading').innerText = 'Error cargando control de sesión.';
}
}

function renderSessionHits(hits) {
const container = document.getElementById('sessionHitsList');
container.innerHTML = '';

if (!hits.length) {
container.innerHTML = `<p class="text-slate-500">No hay hits registrados.</p>`;
return;
}

hits.forEach(hit => {
container.innerHTML += `
<div class="bg-slate-800/60 border border-slate-700 rounded-lg p-4 flex items-center justify-between">
    <div>
        <p class="font-bold">${hit.athlete?.nombre ?? '--'}</p>
        <p class="text-xs text-slate-400">
            Hit #${hit.numero_hit} · Plato ${hit.bike_setup?.plato ?? '--'} · Tiempo ${hit.total_seconds ?? '--'}s
        </p>
    </div>

    <div class="flex gap-2">
        <a href="/performance/hit/${hit.hit_id}/compare/${hit.hit_id}"
           class="px-3 py-1 rounded bg-slate-700 hover:bg-slate-600 text-xs">
            Ver
        </a>
    </div>
</div>
`;
});
}

async function loadAthletes() {
    const response = await fetch('/api/performance/athletes');
    const data = await response.json();

    const select = document.getElementById('athleteId');

    select.innerHTML = '';

    data.athletes.forEach(athlete => {
        select.innerHTML += `
            <option value="${athlete.id}">
                ${athlete.nombres} ${athlete.apellidos}
            </option>
        `;
    });
}

async function loadConfigurations() {
    const response = await fetch('/api/performance/configurations');
    const data = await response.json();

    const select = document.getElementById('configurationId');

    select.innerHTML = '';

    data.configurations.forEach(config => {
        select.innerHTML += `
            <option value="${config.id}">
                ${config.marca}
                ${config.modelo}
                | ${config.plato}/${config.pinon}
            </option>
        `;
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
const response = await fetch(`/api/performance/session/${sessionId}/hit`, {
method: 'POST',
headers: {
'Content-Type': 'application/json',
},
body: JSON.stringify(payload),
});

const data = await response.json();

if (!data.success) {
message.className = 'text-sm text-red-400';
message.innerText = data.message ?? 'No se pudo crear el hit.';
return;
}

message.className = 'text-sm text-green-400';
message.innerText = `Hit creado correctamente. ID: ${data.hit_id}`;

document.getElementById('createHitForm').reset();

loadSessionControl();

} catch (error) {
console.error(error);
message.className = 'text-sm text-red-400';
message.innerText = 'Error creando hit.';
}
});

loadAthletes();
loadConfigurations();
loadSessionControl();