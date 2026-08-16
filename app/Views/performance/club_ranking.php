<?= $this->extend('layouts/Performance') ?>

<?= $this->section('content') ?>

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BTPS Club Analytics</p>
        <h1 class="text-3xl font-bold">Ranking General del Club</h1>
        <p class="text-slate-400">Clasificación por mejor tiempo y métricas de rendimiento</p>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando ranking del club...
    </div>

    <div id="clubRankingDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Atletas con datos</p>
                <h2 id="athletesCount" class="text-3xl font-bold text-cyan-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Mejor tiempo del club</p>
                <h2 id="clubBestTime" class="text-3xl font-bold text-green-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Última actualización</p>
                <h2 id="generatedAt" class="text-xl font-bold text-yellow-400">--</h2>
            </div>
        </section>

        <section
                id="clubLeaderCard"
                class="bg-gradient-to-r from-yellow-500/10 to-amber-500/10 border border-yellow-500/20 rounded-xl p-6 hidden">
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Tabla de ranking</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="text-left py-2">Posición</th>
                        <th class="text-left py-2">Atleta</th>
                        <th class="text-left py-2">Hits</th>
                        <th class="text-left py-2">Mejor</th>
                        <th class="text-left py-2">Promedio</th>
                        <th class="text-left py-2">Gate</th>
                        <th class="text-left py-2">Curvas</th>
                        <th class="text-left py-2">Sprint</th>
                        <th class="text-left py-2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="clubRankingTable"></tbody>
                </table>
            </div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/Performance/club-ranking.js') ?>"></script>

<?= $this->endSection() ?>