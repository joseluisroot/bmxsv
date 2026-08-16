const atletaId = document.getElementById('atletaId').value;

let performanceChart = null;
let setupChart = null;
let progressChart = null;

async function loadDashboard() {
    try {
        const response = await fetch(`/api/performance/athlete/${atletaId}/full-dashboard`);
        const data = await response.json();

        const progressResponse = await fetch(`/api/performance/athlete/${atletaId}/progress`);
        const progressData = await progressResponse.json();

        if (progressData.success) {
            renderAthleteProgress(progressData);
        }

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('dashboard').classList.remove('hidden');

        document.getElementById('lastUpdated').innerText =
            'Actualizado: ' + new Date().toLocaleTimeString();

        const summary = data.dashboard?.summary ?? null;

        if (summary) {
            document.getElementById('bestTime').innerText = formatSeconds(summary.best_time);
            document.getElementById('averageTime').innerText = formatSeconds(summary.average_time);
            document.getElementById('lastTime').innerText = formatSeconds(summary.last_time);
            document.getElementById('validHits').innerText = summary.valid_hits ?? 0;
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
                    <td class="py-2 font-bold text-green-400">${formatSeconds(hit.total_seconds)}</td>
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
            <td class="py-2 font-bold text-green-400"${formatSeconds(hit.total_seconds)}</td>
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

function renderAthleteProgress(data) {
    const card = document.getElementById('progressCard');
    const content = document.getElementById('progressContent');

    const improvement = data.improvement;

    card.classList.remove('hidden');

    const className =
        improvement.status === 'improved'
            ? 'text-green-400'
            : improvement.status === 'worse'
                ? 'text-red-400'
                : 'text-slate-300';

    content.innerHTML = `
        <p class="text-sm text-slate-400">Comparando primer mes vs último mes registrado</p>
        <div class="mt-3 flex flex-wrap gap-6">
            <div>
                <p class="text-slate-400 text-sm">Cambio</p>
                <p class="text-3xl font-bold ${className}">
                    ${improvement.seconds}s
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-sm">Porcentaje</p>
                <p class="text-3xl font-bold ${className}">
                    ${improvement.percent ?? '--'}%
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-sm">Meses analizados</p>
                <p class="text-3xl font-bold">
                    ${data.progress?.length ?? 0}
                </p>
            </div>
        </div>
    `;

    renderProgressChart(data.progress ?? []);
    renderAthleteTrend(data);
}

function renderProgressChart(progress) {
    const canvas = document.getElementById('progressChart');
    const card = document.getElementById('progressChartCard');

    if (!canvas || !card || !progress.length) {
        return;
    }

    card.classList.remove('hidden');

    const labels = progress.map(item => item.month);
    const bestTimes = progress.map(item => item.best_time);
    const averageTimes = progress.map(item => item.average_time);

    const ctx = canvas.getContext('2d');

    if (progressChart) {
        progressChart.destroy();
    }

    progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Mejor tiempo mensual',
                    data: bestTimes,
                    borderWidth: 3,
                    tension: 0.3
                },
                {
                    label: 'Promedio mensual',
                    data: averageTimes,
                    borderWidth: 3,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function renderAthleteTrend(data) {
    const card = document.getElementById('trendCard');
    const content = document.getElementById('trendContent');

    if (!card || !content) {
        return;
    }

    const trend = data.trend ?? {};
    const projection = data.projection ?? {};

    card.classList.remove('hidden');

    const direction = trend.direction ?? 'stable';

    const colorClass =
        direction === 'improving'
            ? 'text-green-400'
            : direction === 'declining'
                ? 'text-red-400'
                : 'text-slate-300';

    const label =
        direction === 'improving'
            ? 'Mejorando'
            : direction === 'declining'
                ? 'Empeorando'
                : 'Sin tendencia suficiente';

    content.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-slate-400 text-sm">Estado</p>
                <p class="text-2xl font-bold ${colorClass}">${label}</p>
            </div>

            <div>
                <p class="text-slate-400 text-sm">Mejora mensual</p>
                <p class="text-2xl font-bold ${colorClass}">
                    ${trend.monthly_improvement ?? '--'}s
                </p>
            </div>

            <div>
                <p class="text-slate-400 text-sm">Proyección 1 mes</p>
                <p class="text-2xl font-bold">
                    ${projection.next_month_best_time ?? '--'}s
                </p>
            </div>

            <div>
                <p class="text-slate-400 text-sm">Proyección 3 meses</p>
                <p class="text-2xl font-bold">
                    ${projection.three_month_projection ?? '--'}s
                </p>
            </div>
        </div>
    `;
}

function formatSeconds(value) {
    if (
        value === null ||
        value === undefined ||
        value === '' ||
        Number.isNaN(Number(value))
    ) {
        return '--';
    }

    return `${Number(value).toFixed(3)}s`;
}

loadDashboard();

setInterval(loadDashboard, 3000);