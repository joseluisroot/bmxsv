<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= esc($title ?? 'Focus Numbers | BMXSV') ?></title>
    <meta name="description" content="<?= esc($descripcion ?? 'Juego de concentración BMXSV.') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --cell-gap:.4rem; --cell-size:42px; }
        @media (min-width:768px){ :root { --cell-size:56px; } }
        .grid-wrap{ display:grid; gap:var(--cell-gap); justify-content:center; align-content:start; }
        .cell{
            width:var(--cell-size); height:var(--cell-size);
            display:flex; align-items:center; justify-content:center;
            border-radius:.65rem; background:#f1f5f9; /* slate-100 */
            border:1px solid rgba(0,0,0,.06);
            font-weight:600; user-select:none; transition:transform .08s ease, background-color .1s ease;
        }
        .cell:hover{ transform:translateY(-1px); }
        .cell.disabled{ opacity:.45; pointer-events:none; }
        .cell.correct{ background:#d1fae5; outline:2px solid rgba(16,185,129,.35); }
        .cell.wrong{ background:#fee2e2; outline:2px solid rgba(239,68,68,.25); }
        .hidden-number{ filter: blur(6px); opacity:.35; }
        .revealed{ filter:none; opacity:1; }
        .blink{ animation: blink .8s linear infinite; }
        @keyframes blink{ 50%{ opacity:.4 } }
    </style>
</head>
<body class="bg-white text-slate-800">
<header class="sticky top-0 z-10 bg-white/90 backdrop-blur border-b">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <h1 class="font-display text-2xl sm:text-3xl tracking-wide" style="font-family:'Bebas Neue',sans-serif">BMXSV • Focus Numbers</h1>
        <a href="<?= base_url('/') ?>" class="text-sm underline">Inicio</a>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 py-6">
    <!-- Indicaciones -->
    <section class="bg-blue-50 border border-blue-200 rounded-2xl p-4 sm:p-6 mb-6">
        <h2 class="text-lg font-semibold mb-2">Indicaciones</h2>
        <p class="text-sm sm:text-base">
            <strong>ENCUENTRA Y MARCA LOS NÚMEROS EN ORDEN DEL 00 AL 49 LO MÁS RÁPIDO POSIBLE EN 1 MINUTO.</strong><br>
            (Si la grilla no tiene celdas suficientes, se ajusta automáticamente al máximo rango posible, ej. 7×7 → 00–48).
        </p>
    </section>

    <!-- Configuración -->
    <section class="bg-slate-50 border rounded-2xl p-4 sm:p-6 mb-6">
        <h3 class="text-xl font-semibold mb-3">Configurar jugador</h3>
        <form id="setupForm" class="grid sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha de nacimiento</label>
                <input id="dob" type="date" class="w-full border rounded-lg px-3 py-2">
                <p id="ageOut" class="text-xs text-slate-500 mt-1">Edad: —</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Grilla (auto)</label>
                <input id="gridSize" type="text" class="w-full border rounded-lg px-3 py-2 bg-slate-100" readonly>
                <p class="text-xs text-slate-500 mt-1">Se ajusta según edad.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Rango objetivo</label>
                <input id="rangeOut" type="text" class="w-full border rounded-lg px-3 py-2 bg-slate-100" readonly>
                <p class="text-xs text-slate-500 mt-1">Ej: 00–49 (si cabe).</p>
            </div>

            <div class="flex gap-2">
                <button type="button" id="btnSet" class="grow bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700">Establecer</button>
                <button type="button" id="btnStart" class="grow bg-green-600 text-white rounded-lg px-4 py-2 hover:bg-green-700" disabled>Iniciar</button>
            </div>
        </form>
    </section>

    <!-- HUD -->
    <section class="grid sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-slate-50 border rounded-2xl p-4">
            <div class="text-xs text-slate-500">Siguiente número</div>
            <div id="hudNext" class="text-2xl font-bold">—</div>
        </div>
        <div class="bg-slate-50 border rounded-2xl p-4">
            <div class="text-xs text-slate-500">Progreso</div>
            <div id="hudProgress" class="text-2xl font-bold">0</div>
        </div>
        <div class="bg-slate-50 border rounded-2xl p-4">
            <div class="text-xs text-slate-500">Tiempo restante</div>
            <div id="hudTime" class="text-2xl font-bold">60.0s</div>
        </div>
        <div class="bg-slate-50 border rounded-2xl p-4">
            <div class="text-xs text-slate-500">Clicks válidos</div>
            <div id="hudClicks" class="text-2xl font-bold">0</div>
        </div>
    </section>

    <!-- Grilla -->
    <section class="bg-white border rounded-2xl p-4 sm:p-6">
        <div id="grid" class="grid-wrap"></div>

        <div class="mt-4 flex items-center justify-center gap-2">
            <button id="btnRetry" class="px-4 py-2 rounded-lg border hover:bg-slate-50 hidden">Reiniciar</button>
            <button id="btnShuffleStart" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Reordenar y comenzar</button>
            <button id="btnReveal" class="px-4 py-2 rounded-lg border hover:bg-slate-50 hidden">Mostrar números</button>
        </div>

        <div id="messages" class="mt-4 text-center text-sm text-slate-600"></div>
    </section>

    <!-- Registro de tiempos -->
    <section class="mt-6 bg-slate-50 border rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Tiempos entre clics (s)</h3>
            <button id="btnExportCsv" class="px-3 py-2 rounded-lg border hover:bg-slate-50">Exportar CSV</button>
        </div>
        <div id="logTimes" class="text-xs sm:text-sm whitespace-pre-wrap font-mono text-slate-700">—</div>
    </section>

    <!-- Resultado -->
    <section class="mt-6 bg-white border rounded-2xl p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-3">Resultado de concentración</h3>
        <div id="resultCard" class="hidden">
            <div class="grid sm:grid-cols-3 gap-3">
                <div class="bg-slate-50 border rounded-xl p-4">
                    <div class="text-xs text-slate-500">Números marcados</div>
                    <div id="resNumbers" class="text-2xl font-bold">0</div>
                </div>
                <div class="bg-slate-50 border rounded-xl p-4">
                    <div class="text-xs text-slate-500">Porcentaje (tabla)</div>
                    <div id="resPercent" class="text-2xl font-bold">—</div>
                </div>
                <div class="bg-slate-50 border rounded-xl p-4">
                    <div class="text-xs text-slate-500">Nivel</div>
                    <div id="resLevel" class="text-2xl font-bold">—</div>
                </div>
            </div>

            <!-- Leyenda de la tabla -->
            <div class="mt-4">
                <h4 class="font-semibold mb-2 text-sm">Tabla de referencia</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border">
                        <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2 border">Números marcados</th>
                            <th class="p-2 border">Porcentaje de concentración</th>
                            <th class="p-2 border">Nivel</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td class="p-2 border">0–4</td><td class="p-2 border">0–16%</td><td class="p-2 border">Débil</td></tr>
                        <tr><td class="p-2 border">5–9</td><td class="p-2 border">17–32%</td><td class="p-2 border">Necesita mejorar</td></tr>
                        <tr><td class="p-2 border">10–15</td><td class="p-2 border">33–52%</td><td class="p-2 border">Promedio</td></tr>
                        <tr><td class="p-2 border">16–21</td><td class="p-2 border">53–72%</td><td class="p-2 border">Bueno</td></tr>
                        <tr><td class="p-2 border">22–30</td><td class="p-2 border">73–100%</td><td class="p-2 border">Excelente</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</main>

<script src="<?= base_url('assets/juegos/focus-numbers.js') ?>"></script>
</body>
</html>
