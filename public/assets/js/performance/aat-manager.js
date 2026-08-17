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
    const message = document.getElementById('aatMessage');
    const payload = {
        uid: document.getElementById('uid').value.trim(),
        serial_number: document.getElementById('serialNumber').value.trim() || null,
        firmware_version: document.getElementById('firmwareVersion').value.trim() || null,
        ownership_type: document.getElementById('ownershipType').value,
        owner_athlete_id: document.getElementById('ownerAthleteId').value || null,
        notes: document.getElementById('aatNotes').value.trim() || null,
    };

    const response = await fetch('/api/hardware/aats', {method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)});
    const data = await response.json();
    message.textContent = data.message ?? '';
    message.className = `text-sm ${data.success ? 'text-green-400' : 'text-red-400'}`;
    if (data.success) {
        e.target.reset();
        await loadAats();
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
    const payload = {
        athlete_id: Number(document.getElementById('assignAthlete').value),
        assignment_type: document.getElementById('assignmentType').value,
        session_id: document.getElementById('assignmentSession').value || null,
    };
    const response = await fetch(`/api/hardware/aats/${id}/assign`, {method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)});
    const data = await response.json();
    if (!data.success) return alert(data.message ?? 'No se pudo asignar.');
    closeAssignModal();
    await loadAats();
}

async function returnAat(id) {
    if (!confirm('¿Registrar devolución de este AAT?')) return;
    const response = await fetch(`/api/hardware/aats/${id}/return`, {method: 'POST'});
    const data = await response.json();
    if (!data.success) return alert(data.message ?? 'No se pudo devolver.');
    await loadAats();
}

async function showHistory(id) {
    const response = await fetch(`/api/hardware/aats/${id}/history`);
    const data = await response.json();
    const lines = (data.history ?? []).map(h => `${h.starts_at} · ${h.nombres} ${h.apellidos} · ${h.assignment_type}${h.returned_at ? ` · devuelto ${h.returned_at}` : ' · activo'}`);
    alert(lines.length ? lines.join('\n') : 'Sin historial de asignaciones.');
}

loadReferenceData().then(loadAats).catch(console.error);
