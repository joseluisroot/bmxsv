const atletaId = document.getElementById('atletaId').value;

let performanceChart = null;
let setupChart = null;

async function loadDashboard() {
    try {
        const response = await fetch(`/api/performance/athlete/${atletaId}/full-dashboard`);
        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('dashboard').classList.remove('hidden');

        document.getElementById('lastUpdated').innerText =
            'Actualizado: ' + new Date().toLocaleTimeString();

        const summary = data.dashboard?.summary ?? null;

        if (summary) {
            document.getElementById('bestTime').innerText = summary.best_time + 's';
            document.getElementById('averageTime').innerText = summary.average_time + 's';
            document.getElementById('lastTime').innerText = summary.last_time + 's';
            document.getElementById('validHits').innerText = summary.valid_hits;
        }

        const lastPerformance = data.dashboard?.last_hit?.performance ?? null;

        if (lastPerformance) {
            updateMetric('metricGate', 'barGate', lastPerformance.gate, 4);
            updateMetric('metricFirst', 'barFirst', lastPerformance.first_straight, 10);
            updateMetric('metricMiddle', 'barMiddle', lastPerformance.middle_track, 20);
            updateMetric('metricFinal', 'barFinal', lastPerformance.final_straight, 10);
        }

        const bestSetup = data.setup_comparison?.best_setup ?? null;

        if (bestSetup) {
            document.getElementById('bestSetup').innerHTML = `
                <div class="text-4xl font-bold text-yellow-400 mb-2">Plato ${bestSetup.plato}</div>
                <p>Piñón: <strong>${bestSetup.pinon ?? '--'}</strong></p>
                <p>Promedio: <strong>${bestSetup.average_time}s</strong></p>
                <p>Mejor tiempo: <strong>${bestSetup.best_time}s</strong></p>
                <p>Hits analizados: <strong>${bestSetup.hits_count}</strong></p>
            `;
        }

        const bestHits = data.best_hits?.hits ?? [];
        const history = data.history?.history ?? [];
        const setupComparison = data.setup_comparison?.setups ?? [];

        if (history.length > 0) {
            renderPerformanceChart(history);
        }

        if (setupComparison.length > 0) {
            renderSetupChart(setupComparison);
        }

        const tbody = document.getElementById('bestHitsTable');
        tbody.innerHTML = '';

        bestHits.slice(0, 10).forEach(hit => {
            tbody.innerHTML += `
                <tr class="border-b border-slate-800">
                    <td class="py-2">#${hit.numero_hit}</td>
                    <td class="py-2">${hit.session?.fecha ?? '--'}</td>
                    <td class="py-2">${hit.bike_setup?.plato ?? '--'}</td>
                    <td class="py-2">${hit.bike_setup?.pinon ?? '--'}</td>
                    <td class="py-2 font-bold text-green-400">${hit.total_seconds}s</td>
                </tr>
            `;
        });

        const historyTable = document.getElementById('historyTable');
        historyTable.innerHTML = '';

        history.slice(-20).reverse().forEach(item => {
            historyTable.innerHTML += `
        <tr class="border-b border-slate-800 hover:bg-slate-800/50">
            <td class="py-2">${item.date ?? '--'}</td>
            <td class="py-2">${item.session?.nombre ?? '--'}</td>
            <td class="py-2">#${item.numero_hit}</td>
            <td class="py-2">${item.bike_setup?.plato ?? '--'}</td>
            <td class="py-2 font-bold text-green-400">${item.total_seconds}s</td>
            <td class="py-2">${item.performance?.gate ?? '--'}s</td>
            <td class="py-2">${item.performance?.first_straight ?? '--'}s</td>
            <td class="py-2">${item.performance?.middle_track ?? '--'}s</td>
            <td class="py-2">${item.performance?.final_straight ?? '--'}s</td>
        </tr>
    `;
        });

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando dashboard.';
    }
}

function updateMetric(labelId, barId, value, max) {
    if (value === null || value === undefined) return;

    document.getElementById(labelId).innerText = value + 's';

    let percent = Math.min((value / max) * 100, 100);
    document.getElementById(barId).style.width = percent + '%';
}

function renderPerformanceChart(history) {

    const labels = history.map(item => `Hit ${item.numero_hit}`);

    const values = history.map(item => item.total_seconds);

    const ctx = document
        .getElementById('performanceChart')
        .getContext('2d');

    if (performanceChart) {
        performanceChart.destroy();
    }

    performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Tiempo Total',
                data: values,
                borderWidth: 3,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function renderSetupChart(setups) {
    const labels = setups.map(item => `Plato ${item.plato}`);
    const values = setups.map(item => item.average_time);

    const ctx = document
        .getElementById('setupChart')
        .getContext('2d');

    if (setupChart) {
        setupChart.destroy();
    }

    setupChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Promedio por configuración',
                data: values,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

loadDashboard();

setInterval(loadDashboard, 3000);