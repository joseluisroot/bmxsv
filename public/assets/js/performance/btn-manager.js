let btns = [];
let timingPoints = [];

async function loadTimingPoints() {
    const response = await fetch('/api/performance/sessions/timing-points');
    const data = await response.json();
    timingPoints = data.timing_points ?? [];
    const select = document.getElementById('timingPointId');
    select.innerHTML = '<option value="">Seleccionar...</option>';
    timingPoints.forEach(p => select.insertAdjacentHTML('beforeend', `<option value="${p.id}">${p.codigo} · ${p.nombre}</option>`));
}

async function loadBtns() {
    const response = await fetch('/api/hardware/btns');
    const data = await response.json();
    btns = data.devices ?? [];
    renderBtns();
}

function renderBtns() {
    const tbody = document.getElementById('btnTable');
    tbody.innerHTML = '';
    let healthy = 0, locked = 0, warnings = 0;

    btns.forEach(d => {
        if (d.health_status === 'healthy') healthy++;
        if (d.clock_status === 'locked') locked++;
        if (['warning', 'error', 'offline'].includes(d.health_status) || ['warning', 'unlocked', 'error'].includes(d.clock_status)) warnings++;

        tbody.insertAdjacentHTML('beforeend', `
            <tr class="border-t border-slate-800">
                <td class="p-3"><div class="font-bold">${d.codigo_dispositivo}</div><div class="text-xs text-slate-500">${d.punto_codigo ?? ''} · ${d.punto_nombre ?? ''}</div></td>
                <td class="p-3"><div>${d.network_mode ?? 'local'} · ${d.ip_address ?? 'sin IP'}</div><div class="text-xs text-slate-500 max-w-xs truncate">${d.endpoint_url ?? 'sin endpoint'}</div></td>
                <td class="p-3"><div>${d.clock_status ?? 'unknown'}</div><div class="text-xs text-slate-500">offset ${d.clock_offset_us ?? '—'} µs</div></td>
                <td class="p-3"><div>${d.health_status ?? 'unknown'}</div><div class="text-xs text-slate-500">RSSI ${d.signal_dbm ?? '—'} dBm · ${d.battery_mv ?? '—'} mV</div></td>
                <td class="p-3">${d.ultima_conexion ?? '—'}<div class="text-xs text-slate-500">sync ${d.last_sync_at ?? '—'}</div></td>
                <td class="p-3"><button onclick="editBtn(${d.id})" class="px-3 py-1 bg-cyan-600 rounded hover:bg-cyan-500">Editar</button></td>
            </tr>`);
    });

    document.getElementById('btnTotal').textContent = btns.length;
    document.getElementById('btnHealthy').textContent = healthy;
    document.getElementById('btnLocked').textContent = locked;
    document.getElementById('btnWarnings').textContent = warnings;
    if (!btns.length) tbody.innerHTML = '<tr><td colspan="6" class="p-6 text-slate-500">No hay BTN registrados.</td></tr>';
}

function editBtn(id) {
    const d = btns.find(x => Number(x.id) === Number(id));
    if (!d) return;
    document.getElementById('btnId').value = d.id;
    document.getElementById('deviceCode').value = d.codigo_dispositivo ?? '';
    document.getElementById('timingPointId').value = d.punto_control_id ?? '';
    document.getElementById('btnFirmware').value = d.firmware_version ?? '';
    document.getElementById('networkMode').value = d.network_mode ?? 'local';
    document.getElementById('endpointUrl').value = d.endpoint_url ?? '';
    document.getElementById('ipAddress').value = d.ip_address ?? '';
    document.getElementById('sensorType').value = d.tipo_sensor ?? 'AAT_LF_RF';
    document.getElementById('btnNotes').value = d.notes ?? '';
    document.getElementById('btnFormTitle').textContent = `Editar ${d.codigo_dispositivo}`;
    window.scrollTo({top: 0, behavior: 'smooth'});
    BTPSAlerts.success('Modo edición', `${d.codigo_dispositivo} está listo para actualizarse.`);
}

function resetBtnForm() {
    document.getElementById('btnForm').reset();
    document.getElementById('btnId').value = '';
    document.getElementById('sensorType').value = 'AAT_LF_RF';
    document.getElementById('networkMode').value = 'local';
    document.getElementById('btnFormTitle').textContent = 'Registrar BTN';
}

document.getElementById('btnForm').addEventListener('submit', async e => {
    e.preventDefault();
    const id = document.getElementById('btnId').value;
    const payload = {
        codigo_dispositivo: document.getElementById('deviceCode').value.trim(),
        punto_control_id: Number(document.getElementById('timingPointId').value),
        tipo_dispositivo: 'BTPS_BTN',
        tipo_sensor: document.getElementById('sensorType').value.trim() || 'AAT_LF_RF',
        network_mode: document.getElementById('networkMode').value,
        endpoint_url: document.getElementById('endpointUrl').value.trim() || null,
        ip_address: document.getElementById('ipAddress').value.trim() || null,
        firmware_version: document.getElementById('btnFirmware').value.trim() || null,
        notes: document.getElementById('btnNotes').value.trim() || null,
    };

    if (!payload.codigo_dispositivo || !payload.punto_control_id) {
        return BTPSAlerts.error('Información incompleta', 'Código de dispositivo y punto de control son obligatorios.');
    }

    try {
        BTPSAlerts.loading(id ? 'Actualizando BTN' : 'Registrando BTN', 'Guardando configuración de hardware y red.');
        const response = await fetch(id ? `/api/hardware/btns/${id}` : '/api/hardware/btns', {
            method: id ? 'PUT' : 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload),
        });
        const data = await response.json();
        BTPSAlerts.close();

        if (!data.success) {
            return BTPSAlerts.error(id ? 'No se pudo actualizar el BTN' : 'No se pudo registrar el BTN', data.message ?? 'Revisa los datos e inténtalo nuevamente.');
        }

        const deviceCode = payload.codigo_dispositivo;
        resetBtnForm();
        await loadBtns();
        BTPSAlerts.success(id ? 'BTN actualizado' : 'BTN registrado', `${deviceCode} quedó guardado correctamente.`);
    } catch (error) {
        BTPSAlerts.close();
        BTPSAlerts.error('Error de conexión', 'No fue posible comunicarse con BTPS para guardar el nodo.');
    }
});

Promise.all([loadTimingPoints(), loadBtns()]).catch(() => {
    BTPSAlerts.error('No se pudo cargar Device Manager', 'Revisa la conexión con el servidor BTPS.');
});
