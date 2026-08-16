const sessionId = document.getElementById('sessionId').value;

async function loadCoachDashboard() {
    try {
        const response = await fetch(`/api/performance/session/${sessionId}/coach-dashboard`);
        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');
        document.getElementById('coachDashboard').classList.remove('hidden');

        document.getElementById('athletesCount').innerText = data.summary?.athletes_count ?? 0;
        document.getElementById('hitsCount').innerText = data.summary?.hits_count ?? 0;

        document.getElementById('lastUpdated').innerText =
            'Actualizado: ' + new Date().toLocaleTimeString();

        const rankingTotal = data.ranking?.total ?? [];

        if (rankingTotal.length > 0) {
            document.getElementById('bestTime').innerText = rankingTotal[0].seconds + 's';
        }

        renderRanking(rankingTotal);
        renderAthletes(data.athletes ?? []);
        renderTrackStatus(data.track_status ?? []);
        renderTrackAthletes(data.track_status ?? []);

    } catch (error) {
        console.error(error);
        document.getElementById('loading').innerText = 'Error cargando dashboard del coach.';
    }
}

function renderRanking(items) {
    const container = document.getElementById('rankingTotal');
    container.innerHTML = '';

    if (!items.length) {
        container.innerHTML = '<p class="text-slate-500">Sin datos de ranking.</p>';
        return;
    }

    items.slice(0, 10).forEach(item => {
        container.innerHTML += `
            <div class="flex items-center justify-between bg-slate-800/60 rounded-lg px-4 py-3">
                <div>
                    <span class="font-bold text-yellow-400">#${item.position}</span>
                    <span class="ml-2 font-semibold">${item.athlete?.nombre ?? '--'}</span>
                    <span class="text-xs text-slate-500 ml-2">Hit #${item.numero_hit}</span>
                </div>
                <div class="text-green-400 font-bold">${item.seconds}s</div>
            </div>
        `;
    });
}

function renderAthletes(athletes) {
    const container = document.getElementById('athletesList');
    container.innerHTML = '';

    if (!athletes.length) {
        container.innerHTML = '<p class="text-slate-500">No hay atletas en esta sesión.</p>';
        return;
    }

    athletes.forEach(athlete => {
        container.innerHTML += `
            <div class="bg-slate-800/60 rounded-lg px-4 py-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold">${athlete.nombre}</p>
                        <p class="text-xs text-slate-400">${athlete.hits} hits registrados</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Mejor</p>
                        <p class="font-bold text-green-400">${athlete.best_time ?? '--'}s</p>
                    </div>
                </div>
            </div>
        `;
    });
}


function renderTrackStatus(trackStatus) {

    const container = document.getElementById('trackStatusContainer');

    if (!container) {
        return;
    }

    container.innerHTML = '';

    if (!trackStatus.length) {

        container.innerHTML = `
            <div class="text-slate-500">
                No hay información de pista.
            </div>
        `;

        return;
    }

    trackStatus.forEach(item => {

        const athlete = item.athlete?.nombre ?? '--';

        const pointCode =
            item.current_position?.point_code ?? 'SIN TP';

        const pointName =
            item.current_position?.point_name ?? '--';

        const total =
            item.total_seconds ?? '--';

        container.innerHTML += `
            <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-4">

                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-white">
                        ${athlete}
                    </h4>

                    <span class="text-green-400 font-bold">
                        ${pointCode}
                    </span>
                </div>

                <p class="text-sm text-slate-400">
                    ${pointName}
                </p>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Hit #${item.numero_hit}
                    </span>

                    <span class="font-bold text-cyan-400">
                        ${total}s
                    </span>
                </div>

            </div>
        `;
    });

    document.getElementById('trackLastUpdate').innerText =
        new Date().toLocaleTimeString();
}

loadCoachDashboard();
setInterval(loadCoachDashboard, 3000);