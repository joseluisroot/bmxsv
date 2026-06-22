const sessionId = document.getElementById('sessionId').value;

async function loadLiveSession() {
    try {
        const [summaryResponse, rankingResponse] = await Promise.all([
            fetch(`/api/timing/session/${sessionId}/summary`),
            fetch(`/api/performance/session/${sessionId}/ranking`)
        ]);

        const summaryData = await summaryResponse.json();
        const rankingData = await rankingResponse.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('liveDashboard').classList.remove('hidden');
        document.getElementById('lastUpdated').innerText =
            'Actualizado: ' + new Date().toLocaleTimeString();

        if (summaryData.success) {
            renderSessionSummary(summaryData);
            renderHits(summaryData.hits ?? []);
        }

        if (rankingData.success) {
            renderRankings(rankingData.ranking ?? {});
        }

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando sesión en vivo.';
    }
}

function renderSessionSummary(data) {
    document.getElementById('sessionName').innerText = data.session?.nombre ?? '--';
    document.getElementById('sessionDate').innerText = data.session?.fecha ?? '--';
    document.getElementById('hitsCount').innerText = data.hits_count ?? 0;

    const hits = data.hits ?? [];
    const validHits = hits.filter(hit => hit.total_seconds !== null);

    if (validHits.length > 0) {
        const best = [...validHits].sort((a, b) => a.total_seconds - b.total_seconds)[0];
        const last = validHits[validHits.length - 1];

        document.getElementById('bestSessionTime').innerText = best.total_seconds + 's';
        document.getElementById('lastSessionTime').innerText = last.total_seconds + 's';
    }
}

function renderRankings(ranking) {
    renderRankingList('rankingTotal', ranking.total ?? []);
    renderRankingList('rankingGate', ranking.gate ?? [], true);
    renderRankingList('rankingFirst', ranking.first_straight ?? [], true);
    renderRankingList('rankingMiddle', ranking.middle_track ?? [], true);
    renderRankingList('rankingFinal', ranking.final_straight ?? [], true);
}

function renderRankingList(containerId, items, compact = false) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (!items.length) {
        container.innerHTML = `<p class="text-slate-500 text-sm">Sin datos.</p>`;
        return;
    }

    items.slice(0, 5).forEach(item => {
        container.innerHTML += `
            <div class="flex items-center justify-between bg-slate-800/60 rounded-lg px-3 py-2">
                <div>
                    <span class="font-bold text-yellow-400">#${item.position}</span>
                    <span class="ml-2">${item.athlete?.nombre ?? '--'}</span>
                    ${!compact ? `<span class="text-xs text-slate-500 ml-2">Hit #${item.numero_hit}</span>` : ''}
                </div>
                <div class="font-bold text-green-400">${item.seconds}s</div>
            </div>
        `;
    });
}

function renderHits(hits) {
    const container = document.getElementById('hitsGrid');
    container.innerHTML = '';

    if (!hits.length) {
        container.innerHTML = `<p class="text-slate-500">No hay hits registrados.</p>`;
        return;
    }

    hits.forEach(hit => {
        const p = hit.performance ?? {};

        container.innerHTML += `
            <article class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold">${hit.athlete?.nombre ?? '--'}</h4>
                        <p class="text-xs text-slate-400">Hit #${hit.numero_hit} · Plato ${hit.bike_setup?.plato ?? '--'} · Piñón ${hit.bike_setup?.pinon ?? '--'}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Total</p>
                        <p class="text-2xl font-bold text-green-400">${hit.total_seconds ?? '--'}s</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div class="bg-slate-900 rounded-lg p-3">
                        <p class="text-slate-400">Gate</p>
                        <p class="font-bold text-cyan-400">${p.gate ?? '--'}s</p>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-3">
                        <p class="text-slate-400">1ra recta</p>
                        <p class="font-bold text-blue-400">${p.first_straight ?? '--'}s</p>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-3">
                        <p class="text-slate-400">Curvas</p>
                        <p class="font-bold text-purple-400">${p.middle_track ?? '--'}s</p>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-3">
                        <p class="text-slate-400">Sprint</p>
                        <p class="font-bold text-green-400">${p.final_straight ?? '--'}s</p>
                    </div>
                </div>
            </article>
        `;
    });
}

loadLiveSession();
setInterval(loadLiveSession, 3000);