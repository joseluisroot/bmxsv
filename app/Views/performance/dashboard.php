<?= $this->extend('layouts/performance') ?>

<?= $this->section('content') ?>

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BMXSV Timing & Performance System</p>
        <h1 class="text-3xl font-bold">Dashboard del Atleta</h1>

        <div class="flex items-center gap-3 text-slate-400">
            <p>Análisis de rendimiento en tiempo real</p>
            <span class="inline-flex items-center gap-2 text-xs bg-green-500/10 text-green-400 px-3 py-1 rounded-full border border-green-500/20">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            Live refresh
        </span>
            <span id="lastUpdated" class="text-xs text-slate-500"></span>
        </div>
    </div>

    <input type="hidden" id="atletaId" value="<?= esc($atletaId) ?>">

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando información del atleta...
    </div>

    <div id="dashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Mejor tiempo</p>
                <h2 id="bestTime" class="text-3xl font-bold text-green-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Promedio</p>
                <h2 id="averageTime" class="text-3xl font-bold text-cyan-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Último hit</p>
                <h2 id="lastTime" class="text-3xl font-bold text-yellow-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hits válidos</p>
                <h2 id="validHits" class="text-3xl font-bold">--</h2>
            </div>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Evolución del rendimiento</h3>
                <div class="relative h-72 md:h-80">
                    <canvas id="performanceChart"></canvas>
                </div>
            </section>

            <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Comparación por configuración</h3>
                <div class="relative h-72 md:h-80">
                    <canvas id="setupChart"></canvas>
                </div>
            </section>
        </div>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Rendimiento por sectores</h3>

                <div class="space-y-4">
                    <?php
                    $metrics = [
                            ['Gate / Salida', 'metricGate', 'barGate', 'bg-cyan-400'],
                            ['Primera recta', 'metricFirst', 'barFirst', 'bg-blue-400'],
                            ['Curvas / Parte media', 'metricMiddle', 'barMiddle', 'bg-purple-400'],
                            ['Sprint final', 'metricFinal', 'barFinal', 'bg-green-400'],
                    ];
                    ?>

                    <?php foreach ($metrics as $metric): ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span><?= esc($metric[0]) ?></span>
                                <span id="<?= esc($metric[1]) ?>">--</span>
                            </div>
                            <div class="h-3 bg-slate-800 rounded-full">
                                <div id="<?= esc($metric[2]) ?>" class="h-3 <?= esc($metric[3]) ?> rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Mejor configuración</h3>
                <div id="bestSetup" class="text-slate-300">
                    Sin datos todavía.
                </div>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Mejores hits</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="text-left py-2">Hit</th>
                        <th class="text-left py-2">Fecha</th>
                        <th class="text-left py-2">Plato</th>
                        <th class="text-left py-2">Piñón</th>
                        <th class="text-left py-2">Tiempo</th>
                    </tr>
                    </thead>
                    <tbody id="bestHitsTable"></tbody>
                </table>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Historial de rendimiento</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="text-left py-2">Fecha</th>
                        <th class="text-left py-2">Sesión</th>
                        <th class="text-left py-2">Hit</th>
                        <th class="text-left py-2">Plato</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2">Gate</th>
                        <th class="text-left py-2">1ra Recta</th>
                        <th class="text-left py-2">Curvas</th>
                        <th class="text-left py-2">Sprint</th>
                    </tr>
                    </thead>
                    <tbody id="historyTable"></tbody>
                </table>
            </div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/performance/athlete-dashboard.js') ?>"></script>

<?= $this->endSection() ?>