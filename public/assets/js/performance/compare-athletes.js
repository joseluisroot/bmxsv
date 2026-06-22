const athleteAId = document.getElementById('athleteAId').value;
const athleteBId = document.getElementById('athleteBId').value;

let athleteRadarChart = null;
let athleteCompareChart = null;


async function loadAthleteComparison() {
    try {
        const response = await fetch(`/api/performance/athlete/${athleteAId}/compare/${athleteBId}`);
        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('compareAthletesDashboard').classList.remove('hidden');

        if (!data.success) {
            document.getElementById('compareAthletesDashboard').innerHTML =
                `<div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-red-300">${data.message}</div>`;
            return;
        }

        renderAthleteHeader(data);
        renderComparisonTable(data);
        renderAthleteChart(data);
        renderAthleteRadarChart(data);
        renderInsights(data);

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando comparación.';
    }
}

function renderAthleteHeader(data) {
    const a = data.athlete_a;
    const b = data.athlete_b;

    const aName = a.best_hits?.best_hit?.athlete?.nombre
        ?? a.dashboard?.best_hit?.athlete?.nombre
        ?? `Atleta ${a.id}`;

    const bName = b.best_hits?.best_hit?.athlete?.nombre
        ?? b.dashboard?.best_hit?.athlete?.nombre
        ?? `Atleta ${b.id}`;

    document.getElementById('athleteAName').innerText = aName;
    document.getElementById('athleteBName').innerText = bName;

    document.getElementById('athleteASummary').innerText =
        `Mejor: ${a.dashboard?.summary?.best_time ?? '--'}s · Promedio: ${a.dashboard?.summary?.average_time ?? '--'}s`;

    document.getElementById('athleteBSummary').innerText =
        `Mejor: ${b.dashboard?.summary?.best_time ?? '--'}s · Promedio: ${b.dashboard?.summary?.average_time ?? '--'}s`;

    const bestA = data.comparison?.best_time?.a ?? null;
    const bestB = data.comparison?.best_time?.b ?? null;

    if (bestA !== null && bestB !== null) {
        if (bestA < bestB) {
            document.getElementById('winnerSummary').innerText = `${aName} tiene mejor tiempo`;
        } else if (bestB < bestA) {
            document.getElementById('winnerSummary').innerText = `${bName} tiene mejor tiempo`;
        } else {
            document.getElementById('winnerSummary').innerText = 'Empate en mejor tiempo';
        }
    }
}

function renderComparisonTable(data) {
    const tbody = document.getElementById('athleteComparisonTable');
    tbody.innerHTML = '';

    const metrics = [
        {
            key: 'best_time',
            label: 'Mejor tiempo',
            a: data.comparison?.best_time?.a,
            b: data.comparison?.best_time?.b,
            lowerIsBetter: true,
        },
        {
            key: 'average_time',
            label: 'Promedio',
            a: data.comparison?.average_time?.a,
            b: data.comparison?.average_time?.b,
            lowerIsBetter: true,
        },
        {
            key: 'valid_hits',
            label: 'Hits válidos',
            a: data.comparison?.valid_hits?.a,
            b: data.comparison?.valid_hits?.b,
            lowerIsBetter: false,
        },
    ];

    metrics.forEach(metric => {
        const diff = calculateDifference(metric.a, metric.b);
        const advantage = getAdvantage(metric.a, metric.b, metric.lowerIsBetter);

        tbody.innerHTML += `
            <tr class="border-b border-slate-800">
                <td class="py-3 font-semibold">${metric.label}</td>
                <td class="py-3">${formatValue(metric.a, metric.key)}</td>
                <td class="py-3">${formatValue(metric.b, metric.key)}</td>
                <td class="py-3">${diff}</td>
                <td class="py-3 ${advantage.className} font-bold">${advantage.text}</td>
            </tr>
        `;
    });
}

function renderAthleteChart(data) {
    const labels = ['Mejor tiempo', 'Promedio'];
    const valuesA = [
        data.comparison?.best_time?.a ?? 0,
        data.comparison?.average_time?.a ?? 0,
    ];
    const valuesB = [
        data.comparison?.best_time?.b ?? 0,
        data.comparison?.average_time?.b ?? 0,
    ];

    const ctx = document.getElementById('athleteCompareChart').getContext('2d');

    if (athleteCompareChart) {
        athleteCompareChart.destroy();
    }

    athleteCompareChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Atleta A',
                    data: valuesA,
                    borderWidth: 2,
                },
                {
                    label: 'Atleta B',
                    data: valuesB,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
}

function renderInsights(data) {
    const container = document.getElementById('athleteInsights');
    container.innerHTML = '';

    const aName = data.athlete_a?.nombre ?? 'Atleta A';
    const bName = data.athlete_b?.nombre ?? 'Atleta B';

    const bestA = data.comparison?.best_time?.a;
    const bestB = data.comparison?.best_time?.b;

    const avgA = data.comparison?.average_time?.a;
    const avgB = data.comparison?.average_time?.b;

    const hitsA = data.comparison?.valid_hits?.a ?? 0;
    const hitsB = data.comparison?.valid_hits?.b ?? 0;

    if (bestA !== undefined && bestB !== undefined) {
        addStaticInsight(
            container,
            bestA < bestB
                ? `${aName} tiene el mejor tiempo individual.`
                : bestB < bestA
                    ? `${bName} tiene el mejor tiempo individual.`
                    : 'Ambos tienen el mismo mejor tiempo.'
        );
    }

    if (avgA !== undefined && avgB !== undefined) {
        addStaticInsight(
            container,
            avgA < avgB
                ? `${aName} tiene mejor promedio general.`
                : avgB < avgA
                    ? `${bName} tiene mejor promedio general.`
                    : 'Ambos tienen el mismo promedio.'
        );
    }

    if (hitsA !== hitsB) {
        addStaticInsight(
            container,
            hitsA > hitsB
                ? `${aName} tiene más datos registrados para análisis.`
                : `${bName} tiene más datos registrados para análisis.`
        );
    }

    const performance = data.comparison?.performance_average;

    if (performance) {
        renderSectorInsight(container, 'Gate / Salida', performance.gate, aName, bName);
        renderSectorInsight(container, 'Primera recta', performance.first_straight, aName, bName);
        renderSectorInsight(container, 'Curvas', performance.middle_track, aName, bName);
        renderSectorInsight(container, 'Sprint final', performance.final_straight, aName, bName);
    }

    if (container.innerHTML.trim() === '') {
        container.innerHTML = `
            <div class="bg-slate-800/60 border border-slate-700 rounded-lg p-4 text-slate-400">
                No hay suficientes datos para generar insights.
            </div>
        `;
    }
}

function renderSectorInsight(container, label, metric, aName, bName) {
    if (!metric || metric.a === null || metric.b === null) {
        return;
    }

    const a = Number(metric.a);
    const b = Number(metric.b);

    if (a === b) {
        return;
    }

    const winner = a < b ? aName : bName;
    const diff = Math.abs(a - b).toFixed(3);

    addStaticInsight(
        container,
        `${winner} tiene ventaja en ${label} por ${diff}s.`
    );
}

function addInsight(container, condition, trueText, falseText) {
    container.innerHTML += `
        <div class="bg-slate-800/60 border border-slate-700 rounded-lg p-4">
            ${condition ? trueText : falseText}
        </div>
    `;
}

function addStaticInsight(container, text) {
    container.innerHTML += `
        <div class="bg-slate-800/60 border border-slate-700 rounded-lg p-4">
            ${text}
        </div>
    `;
}

function calculateDifference(a, b) {
    if (a === null || a === undefined || b === null || b === undefined) {
        return '--';
    }

    const diff = Number(b) - Number(a);
    return `${diff > 0 ? '+' : ''}${diff.toFixed(3)}`;
}

function getAdvantage(a, b, lowerIsBetter = true) {
    if (a === null || a === undefined || b === null || b === undefined) {
        return { text: '--', className: 'text-slate-400' };
    }

    if (Number(a) === Number(b)) {
        return { text: 'Empate', className: 'text-slate-400' };
    }

    const aWins = lowerIsBetter ? Number(a) < Number(b) : Number(a) > Number(b);

    return {
        text: aWins ? 'Atleta A' : 'Atleta B',
        className: aWins ? 'text-cyan-400' : 'text-green-400',
    };
}

function formatValue(value, key) {
    if (value === null || value === undefined) {
        return '--';
    }

    if (key === 'valid_hits') {
        return value;
    }

    return `${value}s`;
}

function renderAthleteRadarChart(data)
{
    const performance = data.comparison?.performance_average;

    const radarCanvas = document.getElementById('athleteRadarChart');

    if (!radarCanvas) {
        console.warn('Canvas athleteRadarChart no encontrado.');
        return;
    }

    if (!performance) {
        console.warn('No existe comparison.performance_average en la respuesta.');
        return;
    }

    const labels = [
        'Gate',
        'Primera Recta',
        'Curvas',
        'Sprint'
    ];

    const valuesA = [
        Number(performance.gate?.a ?? 0),
        Number(performance.first_straight?.a ?? 0),
        Number(performance.middle_track?.a ?? 0),
        Number(performance.final_straight?.a ?? 0)
    ];

    const valuesB = [
        Number(performance.gate?.b ?? 0),
        Number(performance.first_straight?.b ?? 0),
        Number(performance.middle_track?.b ?? 0),
        Number(performance.final_straight?.b ?? 0)
    ];

    console.log('Radar values A:', valuesA);
    console.log('Radar values B:', valuesB);

    const aName = data.athlete_a?.nombre ?? 'Atleta A';
    const bName = data.athlete_b?.nombre ?? 'Atleta B';

    const ctx = radarCanvas.getContext('2d');

    if (athleteRadarChart) {
        athleteRadarChart.destroy();
    }

    athleteRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: aName,
                    data: valuesA,
                    borderWidth: 3,
                    fill: true
                },
                {
                    label: bName,
                    data: valuesB,
                    borderWidth: 3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

loadAthleteComparison();