const sessionId = document.getElementById('sessionId').value;

const sensorMap = {
    TP01: 'ESP32-GATE',
    TP02: 'ESP32-RAMPA',
    TP03: 'ESP32-CURVA1',
    TP04: 'ESP32-CURVA2',
    TP05: 'ESP32-CURVA3',
    TP06: 'ESP32-META',
};

async function loadSessionHits() {
    const selector = document.getElementById('hitSelector');
    const log = document.getElementById('simulatorLog');

    try {
        const response = await fetch(`/api/performance/session/${sessionId}/hits`);
        const data = await response.json();

        selector.innerHTML = '';

        if (!data.success || !data.hits?.length) {
            selector.innerHTML = `<option value="">No hay hits disponibles</option>`;
            logMessage('No hay hits registrados para esta sesión.', 'warning');
            return;
        }

        data.hits.forEach(hit => {
            const estado = getHitStatus(hit);
            selector.innerHTML += `
                <option 
                    value="${hit.hit_id}" 
                     data-athlete-id="${hit.athlete?.id ?? ''}"
                     data-configuration-id="${hit.bike_setup?.configuration_id ?? hit.bike_setup?.configuracion_id ?? ''}"
                    ${estado === 'completado' ? 'disabled' : ''}>
                    Hit #${hit.numero_hit} - ${hit.athlete?.nombre ?? 'Atleta'} - Plato ${hit.bike_setup?.plato ?? '--'} - ${estado.toUpperCase()}
                </option>
            `;
        });

        renderMultiHitList(data.hits ?? []);

        logMessage('Hits cargados correctamente.', 'success');

    } catch (error) {
        console.error(error);
        logMessage('Error cargando hits de la sesión.', 'error');
    }
}

async function sendTimingPoint(pointCode) {
    const hitId = document.getElementById('hitSelector').value;

    if (!hitId) {
        logMessage('Selecciona un hit antes de enviar una lectura.', 'warning');
        return;
    }

    const payload = {
        device_code: sensorMap[pointCode],
        timing_point_code: pointCode,
        hit_entrenamiento_id: Number(hitId),
        timestamp_ms: Date.now(),
        raw_data: {
            source: 'BTPS_SIMULATOR',
            simulated: true,
            point: pointCode,
        },
    };

    try {
        const response = await fetch('/api/timing/pass', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (!data.success) {
            logMessage(data.message ?? `Error enviando ${pointCode}`, 'error');
            return;
        }

        logMessage(`${pointCode} enviado correctamente para Hit #${hitId}`, 'success');

        await loadSessionHits();

    } catch (error) {
        console.error(error);
        logMessage(`Error enviando ${pointCode}`, 'error');
    }
}

function logMessage(message, type = 'info') {
    const log = document.getElementById('simulatorLog');

    const colorClass = {
        success: 'text-green-400',
        error: 'text-red-400',
        warning: 'text-yellow-400',
        info: 'text-slate-300',
    }[type] ?? 'text-slate-300';

    const time = new Date().toLocaleTimeString();

    log.innerHTML = `
        <div class="${colorClass} text-sm border-b border-slate-800 py-2">
            <span class="text-slate-500">[${time}]</span> ${message}
        </div>
    ` + log.innerHTML;
}

async function autoRunSimulation() {
    const hitId = document.getElementById('hitSelector').value;

    if (!hitId) {
        logMessage('Selecciona un hit antes de simular carrera completa.', 'warning');
        return;
    }

    const button = document.getElementById('autoRunBtn');
    button.disabled = true;
    button.innerText = 'Simulando...';

    const baseTimestamp = Date.now();

    const sequence = generateRaceProfile();

    for (const item of sequence) {
        await sendTimingPointWithTimestamp(
            item.point,
            baseTimestamp + item.offset
        );

        await wait(600);
    }

    button.disabled = false;
    button.innerText = '▶ Simular carrera completa';

    const totalTime = (
        sequence[sequence.length - 1].offset / 1000
    ).toFixed(3);

    logMessage(`Tiempo total simulado: ${totalTime}s`, 'info');

    logMessage(`Carrera completa simulada para Hit #${hitId}`, 'success');
}

async function sendTimingPointWithTimestamp(pointCode, timestampMs) {
    const hitId = document.getElementById('hitSelector').value;

    const payload = {
        device_code: sensorMap[pointCode],
        timing_point_code: pointCode,
        hit_entrenamiento_id: Number(hitId),
        timestamp_ms: timestampMs,
        raw_data: {
            source: 'BTPS_AUTO_SIMULATOR',
            simulated: true,
            point: pointCode,
        },
    };

    try {
        const response = await fetch('/api/timing/pass', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (!data.success) {
            logMessage(data.message ?? `Error enviando ${pointCode}`, 'error');
            return;
        }

        logMessage(`${pointCode} auto enviado para Hit #${hitId}`, 'success');

        await loadSessionHits();

    } catch (error) {
        console.error(error);
        logMessage(`Error enviando ${pointCode}`, 'error');
    }
}

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function randomBetween(min, max) {
    return Math.floor(
        Math.random() * (max - min + 1)
    ) + min;
}

function generateRaceProfile() {
    const gate = randomBetween(2400, 3200);
    const firstStraight = randomBetween(6500, 8500);
    const middleOne = randomBetween(7000, 9000);
    const middleTwo = randomBetween(6500, 8500);
    const finalSprint = randomBetween(5500, 7500);

    return [
        { point: 'TP01', offset: 0 },
        { point: 'TP02', offset: gate },
        { point: 'TP03', offset: gate + firstStraight },
        { point: 'TP04', offset: gate + firstStraight + middleOne },
        { point: 'TP05', offset: gate + firstStraight + middleOne + middleTwo },
        { point: 'TP06', offset: gate + firstStraight + middleOne + middleTwo + finalSprint },
    ];
}

function getSelectedAthleteProfile() {
    const selector = document.getElementById('hitSelector');
    const selectedText = selector.options[selector.selectedIndex]?.text ?? '';

    if (selectedText.toLowerCase().includes('lucas')) {
        return 'fast';
    }

    if (selectedText.toLowerCase().includes('rafael')) {
        return 'consistent';
    }

    return 'standard';
}

function generateRaceProfile() {
    const profile = getSelectedAthleteProfile();

    let ranges = {
        gate: [2400, 3200],
        firstStraight: [6500, 8500],
        middleOne: [7000, 9000],
        middleTwo: [6500, 8500],
        finalSprint: [5500, 7500],
    };

    if (profile === 'fast') {
        ranges = {
            gate: [2300, 2800],
            firstStraight: [6200, 7800],
            middleOne: [6800, 8300],
            middleTwo: [6300, 7900],
            finalSprint: [5200, 6900],
        };
    }

    if (profile === 'consistent') {
        ranges = {
            gate: [2500, 2900],
            firstStraight: [6700, 7600],
            middleOne: [7200, 8100],
            middleTwo: [6800, 7600],
            finalSprint: [5600, 6600],
        };
    }

    const gate = randomBetween(...ranges.gate);
    const firstStraight = randomBetween(...ranges.firstStraight);
    const middleOne = randomBetween(...ranges.middleOne);
    const middleTwo = randomBetween(...ranges.middleTwo);
    const finalSprint = randomBetween(...ranges.finalSprint);

    return [
        { point: 'TP01', offset: 0 },
        { point: 'TP02', offset: gate },
        { point: 'TP03', offset: gate + firstStraight },
        { point: 'TP04', offset: gate + firstStraight + middleOne },
        { point: 'TP05', offset: gate + firstStraight + middleOne + middleTwo },
        { point: 'TP06', offset: gate + firstStraight + middleOne + middleTwo + finalSprint },
    ];
}

function getHitStatus(hit) {
    if (hit.total_seconds !== null && hit.records_count >= 6) {
        return 'completado';
    }

    if (hit.records_count > 0) {
        return 'en progreso';
    }

    return 'pendiente';
}

function renderMultiHitList(hits) {
    const container = document.getElementById('multiHitList');

    if (!container) return;

    container.innerHTML = '';

    const availableHits = hits.filter(hit => getHitStatus(hit) !== 'completado');

    if (!availableHits.length) {
        container.innerHTML = `<p class="text-slate-500">No hay hits pendientes disponibles.</p>`;
        return;
    }

    availableHits.forEach(hit => {
        container.innerHTML += `
            <label class="bg-slate-800/60 border border-slate-700 rounded-lg p-3 flex items-center gap-3">
                <input
                    type="checkbox"
                    class="multi-hit-checkbox"
                    value="${hit.hit_id}">
                <span>
                    <strong>${hit.athlete?.nombre ?? 'Atleta'}</strong>
                    <span class="text-xs text-slate-400">
                        · Hit #${hit.numero_hit}
                        · Plato ${hit.bike_setup?.plato ?? '--'}
                    </span>
                </span>
            </label>
        `;
    });
}

function renderMultiHitList(hits) {
    const container = document.getElementById('multiHitList');

    if (!container) return;

    container.innerHTML = '';

    const availableHits = hits.filter(hit => getHitStatus(hit) !== 'completado');

    if (!availableHits.length) {
        container.innerHTML = `<p class="text-slate-500">No hay hits pendientes disponibles.</p>`;
        return;
    }

    availableHits.forEach(hit => {
        container.innerHTML += `
            <label class="bg-slate-800/60 border border-slate-700 rounded-lg p-3 flex items-center gap-3">
                <input
                    type="checkbox"
                    class="multi-hit-checkbox"
                    value="${hit.hit_id}">
                <span>
                    <strong>${hit.athlete?.nombre ?? 'Atleta'}</strong>
                    <span class="text-xs text-slate-400">
                        · Hit #${hit.numero_hit}
                        · Plato ${hit.bike_setup?.plato ?? '--'}
                    </span>
                </span>
            </label>
        `;
    });
}

async function autoRunMultiSimulation() {
    const selected = Array.from(
        document.querySelectorAll('.multi-hit-checkbox:checked')
    ).map(input => input.value);

    if (!selected.length) {
        logMessage('Selecciona al menos un hit para simular múltiples atletas.', 'warning');
        return;
    }

    const button = document.getElementById('multiRunBtn');
    const selector = document.getElementById('hitSelector');

    button.disabled = true;
    selector.disabled = true;
    button.innerText = 'Simulando múltiples atletas...';

    for (const hitId of selected) {
        selector.value = hitId;

        logMessage(`Iniciando simulación para Hit #${hitId}`, 'info');

        const baseTimestamp = Date.now();
        const sequence = generateRaceProfile();

        for (const item of sequence) {
            await sendTimingPointWithTimestamp(
                item.point,
                baseTimestamp + item.offset
            );

            await wait(250);
        }

        const totalTime = (
            sequence[sequence.length - 1].offset / 1000
        ).toFixed(3);

        logMessage(`Hit #${hitId} completado: ${totalTime}s`, 'success');

        await wait(500);
    }

    await loadSessionHits();

    button.disabled = false;
    selector.disabled = false;
    button.innerText = '🏁 Simular múltiples atletas';

    logMessage('Simulación multi atleta finalizada.', 'success');
}

/*async function createNextHitForSelectedAthlete() {
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

    const payload = {
        athlete_id: Number(athleteId),
        configuration_id: Number(configurationId),
        notas_coach: 'Hit creado desde simulador',
        sensacion_atleta: null,
    };

    const response = await fetch(`/api/Performance/session/${sessionId}/hit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    const data = await response.json();

    if (!data.success) {
        logMessage(data.message ?? 'No se pudo crear el nuevo hit.', 'error');
        return;
    }

    logMessage(`Nuevo hit creado: #${data.numero_hit} / ID ${data.hit_id}`, 'success');

    await loadSessionHits();

    document.getElementById('hitSelector').value = data.hit_id;
}*/

document.querySelectorAll('.sensor-btn').forEach(button => {
    button.addEventListener('click', function () {
        const pointCode = this.dataset.point;
        sendTimingPoint(pointCode);
    });
});

document.getElementById('autoRunBtn')?.addEventListener('click', autoRunSimulation);

document.getElementById('clearLogBtn')?.addEventListener('click', function () {
    document.getElementById('simulatorLog').innerHTML = '';
});

document
    .getElementById('multiRunBtn')
    ?.addEventListener('click', autoRunMultiSimulation);


//document.getElementById('createNextHitBtn')?.addEventListener('click', createNextHitForSelectedAthlete);

loadSessionHits();