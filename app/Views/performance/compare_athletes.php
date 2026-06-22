<?= $this->extend('layouts/performance') ?>

<?= $this->section('content') ?>

    <input type="hidden" id="athleteAId" value="<?= esc($athleteAId) ?>">
    <input type="hidden" id="athleteBId" value="<?= esc($athleteBId) ?>">

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BTPS Analytics</p>
        <h1 class="text-3xl font-bold">Comparación de Atletas</h1>
        <p class="text-slate-400">Análisis comparativo de rendimiento</p>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando comparación...
    </div>

    <div id="compareAthletesDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Atleta A</p>
                <h2 id="athleteAName" class="text-2xl font-bold text-cyan-400">--</h2>
                <p id="athleteASummary" class="text-sm text-slate-400 mt-2">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 text-center">
                <p class="text-slate-400 text-sm">Comparación</p>
                <h2 class="text-3xl font-bold text-yellow-400">VS</h2>
                <p id="winnerSummary" class="text-sm text-slate-400 mt-2">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Atleta B</p>
                <h2 id="athleteBName" class="text-2xl font-bold text-green-400">--</h2>
                <p id="athleteBSummary" class="text-sm text-slate-400 mt-2">--</p>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Métricas principales</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="text-left py-2">Métrica</th>
                        <th class="text-left py-2">Atleta A</th>
                        <th class="text-left py-2">Atleta B</th>
                        <th class="text-left py-2">Diferencia</th>
                        <th class="text-left py-2">Ventaja</th>
                    </tr>
                    </thead>
                    <tbody id="athleteComparisonTable"></tbody>
                </table>
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 xl:col-span-2">
                <h3 class="text-xl font-bold mb-4">Comparación visual</h3>

                <div class="relative h-72 md:h-80">
                    <canvas id="athleteCompareChart"></canvas>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Radar de rendimiento</h3>

                <div class="relative h-80 w-full max-w-md mx-auto">
                    <canvas id="athleteRadarChart"></canvas>
                </div>
            </div>

        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Insights rápidos</h3>
            <div id="athleteInsights" class="space-y-3 text-sm"></div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/performance/compare-athletes.js') ?>"></script>

<?= $this->endSection() ?>