<?= $this->extend('layouts/Performance') ?>

<?= $this->section('content') ?>

    <input type="hidden" id="hitAId" value="<?= esc($hitAId) ?>">
    <input type="hidden" id="hitBId" value="<?= esc($hitBId) ?>">

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BTPS Analytics</p>
        <h1 class="text-3xl font-bold">Comparación de Hits</h1>
        <p class="text-slate-400">Análisis lado a lado entre dos pasadas</p>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando comparación...
    </div>

    <div id="compareDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hit A</p>
                <h2 id="hitATime" class="text-3xl font-bold text-cyan-400">--</h2>
                <p id="hitAInfo" class="text-xs text-slate-500 mt-1">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hit B</p>
                <h2 id="hitBTime" class="text-3xl font-bold text-yellow-400">--</h2>
                <p id="hitBInfo" class="text-xs text-slate-500 mt-1">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Diferencia total</p>
                <h2 id="totalDifference" class="text-3xl font-bold">--</h2>
                <p id="differenceStatus" class="text-xs text-slate-500 mt-1">--</p>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Comparación por sectores</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="text-left py-2">Métrica</th>
                        <th class="text-left py-2">Hit A</th>
                        <th class="text-left py-2">Hit B</th>
                        <th class="text-left py-2">Diferencia</th>
                        <th class="text-left py-2">Estado</th>
                    </tr>
                    </thead>
                    <tbody id="comparisonTable"></tbody>
                </table>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Mayor mejora</h3>
                <div id="largestGain" class="text-slate-300">--</div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Mayor pérdida</h3>
                <div id="largestLoss" class="text-slate-300">--</div>
            </div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/Performance/compare-hits.js') ?>"></script>

<?= $this->endSection() ?>