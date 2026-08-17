let aats = [];
let athletes = [];

async function loadReferenceData() {
    const [athleteRes, sessionRes] = await Promise.all([
        fetch('/api/performance/athletes'),
        fetch('/api/performance/sessions'),
    ]);
    const athleteData = await athleteRes.json();
    const sessionData = await sessionRes.json();
    athletes = athleteData.athletes ?? [];

    const owner = document.getElementById('ownerAthleteId');
    const assign = document.getElementById('assignAthlete');
    athletes.forEach(a => {
        const label = `${a.nombres ?? ''} ${a.apellidos ?? ''}`.trim();
        owner.insertAdjacentHTML('beforeend', `<option value="${a.id}">${label}</option>`);
        assign.insertAdjacentHTML('beforeend', `<option value="${a.id}">${label}</option>`);
    });

    const sessionSelect = document.getElementById('assignmentSession');
    (sessionData.sessions ?? []).forEach(s => {
        sessionSelect.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.nombre} · ${s.fecha} · ${s.estado}</option>`);
    });
}

async function loadAats() {
    const response = await fetch('/api/hardware/aats');
    const data = await response.json();
    aats = data.aats ?? [];
    renderAats();
}

function renderAats() {
    const tbody = document.getElementById('aatTable');
    tbody.innerHTML = '';

    let available = 0, assigned = 0, maintenance = 0;
    aats.forEach(a => {
        if (a.status === 'available') available++;
        if (['assigned', 'loaned'].includes(a.status)) assigned++;
        if (a.status === 'maintenance') maintenance++;

        const current = a.assigned_athlete_id ? `${a.assigned_nombres ?? ''} ${a.assigned_apellidos ?? ''}`.trim() : '—';
        const owner = a.ownership_type === 'athlete' ? `Atleta: ${`${a.owner_nombres ?? ''} ${a.owner_apellidos ?? ''}`.trim()}` : 'Club / BTPS';
        const battery = a.battery_mv ? `${a.battery_mv} mV` : '—';
        const action = a.assignment_id
            ? `<button onclick="returnAat(${a.id})" class="px-3 py-1 bg-amber-600/80 rounded hover:bg-amber-500">Devolver</button>`
            : `<button onclick="openAssignModal(${a.id})" class="px-3 py-1 bg-cyan-600 rounded hover:bg-cyan-500">Asignar</button>`;

        tbody.insertAdjacentHTML('beforeend', `
            <tr class="border-t border-slate-800">
                <td class="p-3"><div class="font-bold">${a.uid}</div><div class="text-xs text-slate-500">${a.serial_number ?? ''}</div></td>
                <td class="p-3">${owner}</td>
                <td class="p-3"><span class="px-2 py-1 rounded bg-slate-800">${a.status}</span></td>
                <td class="p-3">${current}${a.assignment_type ? `<div class="text-xs text-slate-500">${a.assignment_type}</div>` : ''}</td>
                <td class="p-3"><div>${a.firmware_version ?? '—'}</div><div class="text-xs text-slate-500">${battery}</div></td>
                <td class="p-3 flex gap-2">${action}<button onclick="showHistory(${a.id})" class="px-3 py-1 bg-slate-700 rounded hover:bg-slate-600">Historial</button></td>
            </tr>
        `);
    });

    document.getElementById('kpiTotal').textContent = aats.length;
    document.getElementById('kpiAvailable').textContent = available;
    document.getElementById('kpiAssigned').textContent = assigned;
    document.getElementById('kpiMaintenance').textContent = maintenance;

    if (!aats.length) tbody.innerHTML = '<tr><td colspan="6" class="p-6 text-slate-500">No hay AAT registrados.</td></tr>';
}

document.getElementById('aatForm').addEventListener('submit', async e => {
    e.preventDefault();
    const payload = {
        uid: document.getElementById('uid').value.trim(),
        serial_number: document.getElementById('serialNumber').value.trim() || null,
        firmware_version: document.getElementById('firmwareVersion').value.trim() || null,
        ownership_type: document.getElementById('ownershipType').value,
        owner_athlete_id: document.getElementById('ownerAthleteId').value || null,
        notes: document.getElementById('aatNotes').value.trim() || null,
    };

    try {
        BTPSAlerts.loading('Registrando AAT', 'Guardando el dispositivo en el inventario BTPS.');
        const response = await fetch('/api/hardware/aats', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        BTPSAlerts.close();

        if (!data.success) {
            return BTPSAlerts.error('No se pudo registrar el AAT', data.message ?? 'Revisa los datos e inténtalo nuevamente.');
        }

        e.target.reset();
        await loadAats();
        BTPSAlerts.success('AAT registrado', `${payload.uid} ya está disponible en el inventario.`);
    } catch (error) {
        BTPSAlerts.close();
        BTPSAlerts.error('Error de conexión', 'No fue posible comunicarse con BTPS.');
    }
});

function openAssignModal(id) {
    const aat = aats.find(a => Number(a.id) === Number(id));
    document.getElementById('assignAatId').value = id;
    document.getElementById('assignUid').textContent = aat?.uid ?? '';
    const modal = document.getElementById('assignModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function submitAssignment() {
    const id = document.getElementById('assignAatId').value;
    const aat = aats.find(a => Number(a.id) === Number(id));
    const athleteSelect = document.getElementById('assignAthlete');
    const athleteName = athleteSelect.options[athleteSelect.selectedIndex]?.text ?? 'el atleta';
    const payload = {
        athlete_id: Number(athleteSelect.value),
        assignment_type: document.getElementById('assignmentType').value,
        session_id: document.getElementById('assignmentSession').value || null,
    };

    if (!payload.athlete_id) {
        return BTPSAlerts.error('Selecciona un atleta', 'Debes elegir a quién se asignará este AAT.');
    }

    try {
        BTPSAlerts.loading('Asignando AAT', `Vinculando ${aat?.uid ?? 'el dispositivo'} con ${athleteName}.`);
        const response = await fetch(`/api/hardware/aats/${id}/assign`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        BTPSAlerts.close();

        if (!data.success) {
            return BTPSAlerts.error('No se pudo asignar', data.message ?? 'Verifica el estado del AAT.');
        }

        closeAssignModal();
        await loadAats();
        BTPSAlerts.success('AAT asignado', `${aat?.uid ?? 'El AAT'} quedó asociado a ${athleteName}.`);
    } catch (error) {
        BTPSAlerts.close();
        BTPSAlerts.error('Error de conexión', 'No fue posible completar la asignación.');
    }
}

async function returnAat(id) {
    const aat = aats.find(a => Number(a.id) === Number(id));
    const currentAthlete = aat?.assigned_athlete_id
        ? `${aat.assigned_nombres ?? ''} ${aat.assigned_apellidos ?? ''}`.trim()
        : 'el atleta actual';

    const confirmation = await BTPSAlerts.confirm({
        title: 'Registrar devolución',
        text: `${aat?.uid ?? 'Este AAT'} dejará de estar asignado a ${currentAthlete} y volverá al inventario disponible.`,
        confirmText: 'Sí, devolver',
        cancelText: 'Mantener asignado',
        icon: 'question',
        danger: true
    });

    if (!confirmation.isConfirmed) return;

    try {
        BTPSAlerts.loading('Procesando devolución', 'Actualizando inventario e historial del AAT.');
        const response = await fetch(`/api/hardware/aats/${id}/return`, {method: 'POST'});
        const data = await response.json();
        BTPSAlerts.close();

        if (!data.success) {
            return BTPSAlerts.error('No se pudo devolver el AAT', data.message ?? 'Inténtalo nuevamente.');
        }

        await loadAats();
        BTPSAlerts.success('AAT disponible', `${aat?.uid ?? 'El dispositivo'} regresó correctamente al inventario.`);
    } catch (error) {
        BTPSAlerts.close();
        BTPSAlerts.error('Error de conexión', 'No fue posible registrar la devolución.');
    }
}

async function showHistory(id) {
    const aat = aats.find(a => Number(a.id) === Number(id));

    try {
        BTPSAlerts.loading('Cargando historial', 'Consultando asignaciones anteriores.');
        const response = await fetch(`/api/hardware/aats/${id}/history`);
        const data = await response.json();
        BTPSAlerts.close();

        if (!data.success) {
            return BTPSAlerts.error('No se pudo cargar el historial', data.message ?? 'Inténtalo nuevamente.');
        }

        const history = data.history ?? [];
        if (!history.length) {
            return BTPSAlerts.info(`Historial · ${aat?.uid ?? 'AAT'}`, '<p class="text-slate-400">Este AAT todavía no registra asignaciones.</p>');
        }

        const rows = history.map(h => {
            const athlete = `${h.nombres ?? ''} ${h.apellidos ?? ''}`.trim();
            const state = h.returned_at ? `Devuelto ${h.returned_at}` : 'Asignación activa';
            const session = h.session_name ? `<div style="color:#64748b;font-size:12px;margin-top:4px">Sesión: ${h.session_name}</div>` : '';
            return `<div style="padding:12px 0;border-bottom:1px solid #1e293b;text-align:left">
                <div style="font-weight:700;color:#f8fafc">${athlete}</div>
                <div style="color:#94a3b8;font-size:13px;margin-top:3px">${h.assignment_type} · ${h.starts_at}</div>
                <div style="color:${h.returned_at ? '#94a3b8' : '#22d3ee'};font-size:12px;margin-top:4px">${state}</div>
                ${session}
            </div>`;
        }).join('');

        BTPSAlerts.info(`Historial · ${aat?.uid ?? 'AAT'}`, `<div style="max-height:420px;overflow:auto;padding-right:6px">${rows}</div>`);
    } catch (error) {
        BTPSAlerts.close();
        BTPSAlerts.error('Error de conexión', 'No fue posible recuperar el historial del AAT.');
    }
}

loadReferenceData().then(loadAats).catch(() => {
    BTPSAlerts.error('No se pudo cargar AAT Manager', 'Revisa la conexión con el servidor BTPS.');
});
