async function loadClubRanking() {
    try {
        const response = await fetch('/api/performance/club/ranking');
        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('clubRankingDashboard').classList.remove('hidden');

        if (!data.success) {
            document.getElementById('clubRankingDashboard').innerHTML =
                `<div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-red-300">${data.message}</div>`;
            return;
        }

        renderClubSummary(data);
        renderClubLeader(data);
        renderClubRankingTable(data.ranking ?? []);

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando ranking del club.';
    }
}

function renderClubSummary(data) {
    const ranking = data.ranking ?? [];

    document.getElementById('athletesCount').innerText = data.athletes_count ?? 0;
    document.getElementById('generatedAt').innerText = data.generated_at ?? '--';

    if (ranking.length > 0) {
        document.getElementById('clubBestTime').innerText = ranking[0].best_time + 's';
    }
}

function renderClubRankingTable(ranking) {
    const tbody = document.getElementById('clubRankingTable');
    tbody.innerHTML = '';

    if (!ranking.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="py-4 text-slate-500">
                    No hay datos disponibles.
                </td>
            </tr>
        `;
        return;
    }

    ranking.forEach(item => {
        const p = item.performance_average ?? {};
        const rowClass = getRowClass(item.position);

        tbody.innerHTML += `
            <tr class="border-b border-slate-800 ${rowClass} hover:bg-slate-800/50">
                <td class="py-3 font-bold text-yellow-400 text-lg">${getPositionBadge(item.position)}</td>
                <td class="py-3 font-semibold">${item.athlete?.nombre ?? '--'}</td>
                <td class="py-3">${item.valid_hits ?? 0}</td>
                <td class="py-3 text-green-400 font-bold">${formatSeconds(item.best_time)}</td>
                <td class="py-3">${formatSeconds(item.average_time)}</td>
                <td class="py-3">${formatSeconds(p.gate)}</td>
                <td class="py-3">${formatSeconds(p.middle_track)}</td>
                <td class="py-3">${formatSeconds(p.final_straight)}</td>
                <td class="py-3">
                    <div class="flex gap-2">
                        <a
                            href="/performance/athlete/${item.athlete.id}/dashboard"
                            class="px-2 py-1 rounded bg-cyan-600 text-xs"
                        >
                            Dashboard
                        </a>
                
                        <a
                            href="/performance/athlete/${item.athlete.id}/history"
                            class="px-2 py-1 rounded bg-purple-600 text-xs"
                        >
                            Historial
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
}

function formatSeconds(value) {
    if (value === null || value === undefined) {
        return '--';
    }

    return `${value}s`;
}

function getPositionBadge(position) {
    switch (position) {
        case 1:
            return '🥇';
        case 2:
            return '🥈';
        case 3:
            return '🥉';
        default:
            return `#${position}`;
    }
}

function getRowClass(position) {
    if (position === 1) {
        return 'bg-yellow-500/10';
    }

    if (position === 2) {
        return 'bg-slate-300/5';
    }

    if (position === 3) {
        return 'bg-amber-700/10';
    }

    return '';
}

function renderClubLeader(data) {
    const card = document.getElementById('clubLeaderCard');
    const leader = data.ranking?.[0];

    if (!card || !leader) {
        return;
    }

    card.classList.remove('hidden');

    card.innerHTML = `
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-yellow-400 text-sm font-bold">🏆 Líder del Ranking</p>
                <h2 class="text-3xl font-bold mt-1">${leader.athlete?.nombre ?? '--'}</h2>
                <p class="text-slate-400 text-sm mt-1">Mejor rendimiento general del club</p>
            </div>

            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-slate-400 text-xs">Mejor</p>
                    <p class="text-2xl font-bold text-green-400">${formatSeconds(leader.best_time)}</p>
                </div>

                <div>
                    <p class="text-slate-400 text-xs">Promedio</p>
                    <p class="text-2xl font-bold text-cyan-400">${formatSeconds(leader.average_time)}</p>
                </div>

                <div>
                    <p class="text-slate-400 text-xs">Hits</p>
                    <p class="text-2xl font-bold text-yellow-400">${leader.valid_hits ?? 0}</p>
                </div>
            </div>
        </div>
    `;
}

loadClubRanking();