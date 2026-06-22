const hitAId = document.getElementById('hitAId').value;
const hitBId = document.getElementById('hitBId').value;

async function loadHitComparison() {
    try {
        const response = await fetch(`/api/performance/hit/${hitAId}/compare/${hitBId}`);
        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('compareDashboard').classList.remove('hidden');

        if (!data.success) {
            document.getElementById('compareDashboard').innerHTML =
                `<div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-red-300">${data.message}</div>`;
            return;
        }

        renderSummary(data);
        renderComparisonTable(data.comparison ?? []);
        renderHighlights(data.summary ?? {});

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando comparación.';
    }
}

function renderSummary(data) {
    const hitA = data.hit_a;
    const hitB = data.hit_b;

    document.getElementById('hitATime').innerText = `${hitA.total_seconds}s`;
    document.getElementById('hitBTime').innerText = `${hitB.total_seconds}s`;

    document.getElementById('hitAInfo').innerText =
        `${hitA.athlete?.nombre ?? '--'} · Plato ${hitA.bike_setup?.plato ?? '--'}`;

    document.getElementById('hitBInfo').innerText =
        `${hitB.athlete?.nombre ?? '--'} · Plato ${hitB.bike_setup?.plato ?? '--'}`;

    const diff = data.summary?.total_difference ?? 0;
    const diffEl = document.getElementById('totalDifference');

    diffEl.innerText = `${diff > 0 ? '+' : ''}${diff}s`;

    if (diff < 0) {
        diffEl.className = 'text-3xl font-bold text-green-400';
        document.getElementById('differenceStatus').innerText = 'Hit B fue más rápido';
    } else if (diff > 0) {
        diffEl.className = 'text-3xl font-bold text-red-400';
        document.getElementById('differenceStatus').innerText = 'Hit B fue más lento';
    } else {
        diffEl.className = 'text-3xl font-bold text-slate-300';
        document.getElementById('differenceStatus').innerText = 'Mismo tiempo';
    }
}

function renderComparisonTable(items) {
    const tbody = document.getElementById('comparisonTable');
    tbody.innerHTML = '';

    items.forEach(item => {
        const statusClass =
            item.status === 'improved'
                ? 'text-green-400'
                : item.status === 'worse'
                    ? 'text-red-400'
                    : 'text-slate-400';

        const statusText =
            item.status === 'improved'
                ? 'Mejoró'
                : item.status === 'worse'
                    ? 'Empeoró'
                    : 'Igual';

        tbody.innerHTML += `
            <tr class="border-b border-slate-800">
                <td class="py-3 font-semibold">${item.label}</td>
                <td class="py-3">${item.hit_a}s</td>
                <td class="py-3">${item.hit_b}s</td>
                <td class="py-3 ${statusClass} font-bold">
                    ${item.difference > 0 ? '+' : ''}${item.difference}s
                </td>
                <td class="py-3 ${statusClass}">${statusText}</td>
            </tr>
        `;
    });
}

function renderHighlights(summary) {
    renderHighlight('largestGain', summary.largest_gain, 'green');
    renderHighlight('largestLoss', summary.largest_loss, 'red');
}

function renderHighlight(elementId, item, type) {
    const el = document.getElementById(elementId);

    if (!item) {
        el.innerHTML = `<p class="text-slate-500">Sin datos.</p>`;
        return;
    }

    const color = type === 'green' ? 'text-green-400' : 'text-red-400';
    const label = type === 'green' ? 'Mejora principal' : 'Área a revisar';

    el.innerHTML = `
        <p class="text-sm text-slate-400">${label}</p>
        <h4 class="text-2xl font-bold ${color} mt-1">${item.label}</h4>
        <p class="mt-2">
            Diferencia:
            <strong class="${color}">
                ${item.difference > 0 ? '+' : ''}${item.difference}s
            </strong>
        </p>
    `;
}

loadHitComparison();