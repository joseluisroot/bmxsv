<?= $this->extend('layouts/Performance') ?>

<?= $this->section('content') ?>

    <input type="hidden" id="sessionId" value="<?= esc($sessionId) ?>">

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BTPS Coach Mode</p>
        <h1 class="text-3xl font-bold">Dashboard del Coach</h1>

        <div class="flex items-center gap-3 text-slate-400">
            <p class="text-slate-400">Análisis general de la sesión activa</p>
            <span class="inline-flex items-center gap-2 text-xs bg-green-500/10 text-green-400 px-3 py-1 rounded-full border border-green-500/20">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            Live refresh
        </span>
            <span id="lastUpdated" class="text-xs text-slate-500"></span>
        </div>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando dashboard del coach...
    </div>

    <div id="coachDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Atletas</p>
                <h2 id="athletesCount" class="text-3xl font-bold text-cyan-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hits</p>
                <h2 id="hitsCount" class="text-3xl font-bold text-green-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Mejor tiempo</p>
                <h2 id="bestTime" class="text-3xl font-bold text-yellow-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Estado</p>
                <h2 class="text-xl font-bold text-green-400">Activa</h2>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold">🗺️ Mapa Visual de Pista</h3>
                    <p class="text-sm text-slate-400">Ubicación actual de atletas por punto de control</p>
                </div>

                <span class="text-xs text-slate-500">TP01 → TP06</span>
            </div>

            <div class="relative bg-slate-950 border border-slate-800 rounded-xl overflow-hidden h-[420px]">
                <svg viewBox="0 0 900 420" class="w-full h-full">

                    <path
                            d="M 80 70 C 220 50, 310 80, 380 120
                   C 470 170, 470 240, 390 280
                   C 300 325, 400 380, 540 350
                   C 680 320, 740 260, 680 210
                   C 620 160, 720 90, 820 80"
                            fill="none"
                            stroke="#334155"
                            stroke-width="38"
                            stroke-linecap="round"
                    />

                    <path
                            d="M 80 70 C 220 50, 310 80, 380 120
                   C 470 170, 470 240, 390 280
                   C 300 325, 400 380, 540 350
                   C 680 320, 740 260, 680 210
                   C 620 160, 720 90, 820 80"
                            fill="none"
                            stroke="#0f172a"
                            stroke-width="24"
                            stroke-linecap="round"
                    />

                    <?php
                    $points = [
                            ['TP01', 'Gate', 80, 70],
                            ['TP02', 'Fin Partidor', 260, 72],
                            ['TP03', 'Curva 1', 430, 170],
                            ['TP04', 'Curva 2', 370, 300],
                            ['TP05', 'Curva 3', 610, 320],
                            ['TP06', 'Meta', 820, 80],
                    ];
                    ?>

                    <?php foreach ($points as $point): ?>
                        <g>
                            <circle
                                    cx="<?= $point[2] ?>"
                                    cy="<?= $point[3] ?>"
                                    r="14"
                                    fill="#22d3ee"
                                    stroke="#ffffff"
                                    stroke-width="3"
                            />

                            <text
                                    x="<?= $point[2] ?>"
                                    y="<?= $point[3] - 24 ?>"
                                    text-anchor="middle"
                                    fill="#e2e8f0"
                                    font-size="14"
                                    font-weight="700"
                            >
                                <?= $point[0] ?>
                            </text>

                            <text
                                    x="<?= $point[2] ?>"
                                    y="<?= $point[3] + 38 ?>"
                                    text-anchor="middle"
                                    fill="#94a3b8"
                                    font-size="12"
                            >
                                <?= $point[1] ?>
                            </text>
                        </g>
                    <?php endforeach; ?>

                </svg>

                <div id="trackAthletesLayer" class="absolute inset-0 pointer-events-none"></div>
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Ranking General</h3>
                <div id="rankingTotal" class="space-y-3"></div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Atletas en sesión</h3>
                <div id="athletesList" class="space-y-3"></div>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">
                    🏁 Estado de Pista
                </h3>

                <span id="trackLastUpdate"
                      class="text-xs text-slate-500">
            --
        </span>
            </div>

            <div id="trackStatusContainer"
                 class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            </div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/Performance/track-map.js') ?>"></script>
    <script src="<?= base_url('assets/js/Performance/coach-dashboard.js') ?>"></script>

<?= $this->endSection() ?>