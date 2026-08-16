const sessionId = document.getElementById('sessionId').value;

const sensorMap = {
    TP01: 'ESP32-GATE',
    TP02: 'ESP32-RAMPA',
    TP03: 'ESP32-CURVA1',
    TP04: 'ESP32-CURVA2',
    TP05: 'ESP32-CURVA3',
    TP06: 'ESP32-META',
};

function newEventId() {
    if (window.crypto?.randomUUID) {
        return window.crypto.randomUUID();
    }

    return `sim-${Date.now()}-${Math.random().toString(16).slice(2)}`;
}

function logMessage(message, type = 'info') {
    const log = document.getElementById('simulatorLog');
    const colorClass = {
        success: 'text-green-400',
        error: 'text-red-400',
        warning: 'text-yellow-400',
        info: 'text-slate-300',
    }[type] ?? 'text-slate-300';

    log.innerHTML = `
        <div class="${colorClass} text-sm border-b border-slate-800 py-2">
            <span class="text-slate-500">[${new Date().toLocaleTimeString()}]</span> ${message}
        </div>
    ` + log.innerHTML;
}

function getHitStatus(hit) {
    if (hit.total_seconds !== null && hit.records_count >= 2 && hit.estado === 'completado') {
        return 'completado';
    }

    if (hit.estado) {
        return hit.estado;
    }

    return hit.records_count > 0 ? 'en_progreso' : 'pendiente';
}

async function loadSessionHits() {
    const selector = document.getElementById('hitSelector');

    try {
        const response = await fetch(`/api/performance/session/${sessionId}/hits`);
        const data = await response.json();
        selector.innerHTML = '';

        if (!data.success || !data.hits?.length) {
            selector.innerHTML = '<option value="">No hay hits disponibles</option>';
            renderMultiHitList([]);
            return;
        }

        data.hits.forEach(hit => {
            const status = getHitStatus(hit);
            selector.innerHTML += `
                <option
                    value="${hit.hit_id}"
                    data-athlete-id="${hit.athlete?.id ?? ''}"
                    data-configuration-id="${hit.bike_setup?.configuration_id ?? hit.bike_setup?.configuracion_id ?? ''}"
                    ${status === 'completado' ? 'disabled' : ''}>
                    Hit #${hit.numero_hit} - ${hit.athlete?.nombre ?? 'Atleta'} - Plato ${hit.bike_setup?.plato ?? '--'} - ${status.toUpperCase()}
                </option>
            `;
        });

        renderMultiHitList(data.hits);
    } catch (error) {
        console.error(error);
        logMessage('Error cargando hits de la sesión.', 'error');
    }
}

function renderMultiHitList(hits) {
    const container = document.getElementById('multiHitList');
    if (!container) return;

    const availableHits = hits.filter(hit => getHitStatus(hit) !== 'completado');
    container.innerHTML = '';

    if (!availableHits.length) {
        container.innerHTML = '<p class="text-slate-500">No hay hits pendientes disponibles.</p>';
        return;
    }

    availableHits.forEach(hit => {
        container.innerHTML += `
            <label class="bg-slate-800/60 border border-slate-700 rounded-lg p-3 flex items-center gap-3">
                <input type="checkbox" class="multi-hit-checkbox" value="${hit.hit_id}">
                <span>
                    <strong>${hit.athlete?.nombre ?? 'Atleta'}</strong>
                    <span class="text-xs text-slate-400"> · Hit #${hit.numero_hit} · Plato ${hit.bike_setup?.plato ?? '--'}</span>
                </span>
            </label>
        `;
    });
}

async function postTimingEvent(hitId, pointCode, timestampMs, source) {
    const payload = {
        event_id: newEventId(),
        device_code: sensorMap[pointCode],
        timing_point_code: pointCode,
        hit_entrenamiento_id: Number(hitId),
        timestamp_ms: timestampMs,
        raw_data: {
            source,
            simulated: true,
            point: pointCode,
        },
    };

    const response = await fetch('/api/timing/pass', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload),
    });

    const data = await response.json();

    if (!data.success) {
        throw new Error(data.message ?? `Error enviando ${pointCode}`);
    }

    return data;
}

async function sendTimingPoint(pointCode) {
    const hitId = document.getElementById('hitSelector').value;
    if (!hitId) {
        logMessage('Selecciona un hit antes de enviar una lectura.', 'warning');
        return;
    }

    try {
        await postTimingEvent(hitId, pointCode, Date.now(), 'BTPS_SIMULATOR');
        logMessage(`${pointCode} enviado correctamente para Hit #${hitId}`, 'success');
        await loadSessionHits();
    } catch (error) {
        logMessage(error.message, 'error');
    }
}

function randomBetween(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getAthleteProfileFromOption(option) {
    const text = option?.text?.toLowerCase() ?? '';
    if (text.includes('lucas')) return 'fast';
    if (text.includes('rafael')) return 'consistent';
    return 'standard';
}

function generateRaceProfile(profile = 'standard') {
    let ranges = {
        gate: [2400, 3200], firstStraight: [6500, 8500], middleOne: [7000, 9000], middleTwo: [6500, 8500], finalSprint: [5500, 7500],
    };

    if (profile === 'fast') {
        ranges = {
            gate: [2300, 2800], firstStraight: [6200, 7800], middleOne: [6800, 8300], middleTwo: [6300, 7900], finalSprint: [5200, 6900],
        };
    } else if (profile === 'consistent') {
        ranges = {
            gate: [2500, 2900], firstStraight: [6700, 7600], middleOne: [7200, 8100], middleTwo: [6800, 7600], finalSprint: [5600, 6600],
        };
    }

    const gate = randomBetween(...ranges.gate);
    const first = randomBetween(...ranges.firstStraight);
    const middleOne = randomBetween(...ranges.middleOne);
    const middleTwo = randomBetween(...ranges.middleTwo);
    const final = randomBetween(...ranges.finalSprint);

    return [
        {point: 'TP01', offset: 0},
        {point: 'TP02', offset: gate},
        {point: 'TP03', offset: gate + first},
        {point: 'TP04', offset: gate + first + middleOne},
        {point: 'TP05', offset: gate + first + middleOne + middleTwo},
        {point: 'TP06', offset: gate + first + middleOne + middleTwo + final},
    ];
}

async function autoRunSimulation() {
    const selector = document.getElementById('hitSelector');
    const hitId = selector.value;

    if (!hitId) {
        logMessage('Selecciona un hit antes de simular carrera completa.', 'warning');
        return;
    }

    const button = document.getElementById('autoRunBtn');
    button.disabled = true;

    try {
        const profile = getAthleteProfileFromOption(selector.options[selector.selectedIndex]);
        const sequence = generateRaceProfile(profile);
        const baseTimestamp = Date.now();

        for (const item of sequence) {
            await postTimingEvent(hitId, item.point, baseTimestamp + item.offset, 'BTPS_AUTO_SIMULATOR');
            logMessage(`${item.point} auto enviado para Hit #${hitId}`, 'success');
        }

        logMessage(`Hit #${hitId} completado: ${(sequence.at(-1).offset / 1000).toFixed(3)}s`, 'success');
        await loadSessionHits();
    } catch (error) {
        logMessage(error.message, 'error');
    } finally {
        button.disabled = false;
    }
}

async function autoRunMultiSimulation() {
    const selected = Array.from(document.querySelectorAll('.multi-hit-checkbox:checked')).map(input => input.value);
    if (!selected.length) {
        logMessage('Selecciona al menos un hit para simular múltiples atletas.', 'warning');
        return;
    }

    const button = document.getElementById('multiRunBtn');
    const selector = document.getElementById('hitSelector');
    button.disabled = true;
    selector.disabled = true;

    try {
        const baseTimestamp = Date.now();
        const events = [];

        selected.forEach(hitId => {
            const option = Array.from(selector.options).find(item => item.value === hitId);
            const sequence = generateRaceProfile(getAthleteProfileFromOption(option));

            sequence.forEach(item => {
                events.push({hitId, point: item.point, timestamp: baseTimestamp + item.offset});
            });
        });

        events.sort((a, b) => a.timestamp - b.timestamp);

        for (const event of events) {
            await postTimingEvent(event.hitId, event.point, event.timestamp, 'BTPS_MULTI_SIMULATOR');
            logMessage(`${event.point} intercalado para Hit #${event.hitId}`, 'success');
        }

        logMessage('Simulación multi atleta intercalada finalizada.', 'success');
        await loadSessionHits();
    } catch (error) {
        logMessage(error.message, 'error');
    } finally {
        button.disabled = false;
        selector.disabled = false;
    }
}

async function createNextHitForSelectedAthlete() {
    const selector = document.getElementById('hitSelector');
    const selectedOption = selector.options[selector.selectedIndex];

    if (!selectedOption || !selector.value) {
        logMessage('Selecciona un hit base para crear el siguiente hit.', 'warning');
        return;
    }

    const athleteId = selectedOption.dataset.athleteId;
    const configurationId = selectedOption.dataset.configurationId;

    if (!athleteId || !configurationId) {
        logMessage('No se pudo obtener atleta/configuración del hit seleccionado.', 'error');
        return;
    }

    try {
        const response = await fetch(`/api/performance/session/${sessionId}/hit`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                athlete_id: Number(athleteId),
                configuration_id: Number(configurationId),
                notas_coach: 'Hit creado desde simulador',
                sensacion_atleta: null,
            }),
        });

        const data = await response.json();
        if (!data.success) throw new Error(data.message ?? 'No se pudo crear el nuevo hit.');

        logMessage(`Nuevo hit creado: #${data.numero_hit} / ID ${data.hit_id}`, 'success');
        await loadSessionHits();
        selector.value = data.hit_id;
    } catch (error) {
        logMessage(error.message, 'error');
    }
}

document.querySelectorAll('.sensor-btn').forEach(button => {
    button.addEventListener('click', () => sendTimingPoint(button.dataset.point));
});

document.getElementById('autoRunBtn')?.addEventListener('click', autoRunSimulation);
document.getElementById('multiRunBtn')?.addEventListener('click', autoRunMultiSimulation);
document.getElementById('createNextHitBtn')?.addEventListener('click', createNextHitForSelectedAthlete);
document.getElementById('clearLogBtn')?.addEventListener('click', () => {
    document.getElementById('simulatorLog').innerHTML = '';
});

loadSessionHits();
