const TRACK_POINTS = {
    TP01: { x: 80, y: 70 },
    TP02: { x: 260, y: 72 },
    TP03: { x: 430, y: 170 },
    TP04: { x: 370, y: 300 },
    TP05: { x: 610, y: 320 },
    TP06: { x: 820, y: 80 },
};

function renderTrackAthletes(trackStatus) {
    const layer = document.getElementById('trackAthletesLayer');

    if (!layer) {
        return;
    }

    layer.innerHTML = '';

    if (!trackStatus || !trackStatus.length) {
        return;
    }

    trackStatus.forEach((item, index) => {
        const pointCode = item.current_position?.point_code;

        if (!pointCode || !TRACK_POINTS[pointCode]) {
            return;
        }

        const point = TRACK_POINTS[pointCode];

        const offset = index * 18;

        const marker = document.createElement('div');

        marker.className = `
            absolute -translate-x-1/2 -translate-y-1/2
            bg-yellow-400 text-slate-950 text-xs font-bold
            px-3 py-1 rounded-full shadow-lg border border-white
            whitespace-nowrap
        `;

        marker.style.left = `calc(${(point.x / 900) * 100}% + ${offset}px)`;
        marker.style.top = `${(point.y / 420) * 100}%`;

        marker.innerHTML = `
            🚴 ${item.athlete?.nombre ?? '--'}
        `;

        layer.appendChild(marker);
    });
}