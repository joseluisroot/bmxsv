<?= $this->extend('layouts/Performance') ?>

<?= $this->section('content') ?>

    <input type="hidden" id="sessionId" value="<?= esc($sessionId) ?>">

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BMXSV Timing & Performance System</p>
        <h1 class="text-3xl font-bold">Sesión en Vivo</h1>

        <div class="flex items-center gap-3 text-slate-400 mt-1">
            <p>Monitoreo de hits y puntos de control</p>

            <span class="inline-flex items-center gap-2 text-xs bg-green-500/10 text-green-400 px-3 py-1 rounded-full border border-green-500/20">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            Live refresh
        </span>

            <span id="lastUpdated" class="text-xs text-slate-500"></span>
        </div>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando sesión en vivo...
    </div>

    <div id="liveDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Sesión</p>
                <h2 id="sessionName" class="text-xl font-bold">--</h2>
                <p id="sessionDate" class="text-xs text-slate-500 mt-1">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hits</p>
                <h2 id="hitsCount" class="text-3xl font-bold text-cyan-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Mejor tiempo</p>
                <h2 id="bestSessionTime" class="text-3xl font-bold text-green-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Último registro</p>
                <h2 id="lastSessionTime" class="text-3xl font-bold text-yellow-400">--</h2>
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Ranking total</h3>
                <div id="rankingTotal" class="space-y-3"></div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-4">Ranking por sectores</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h4 class="font-bold text-cyan-400 mb-2">Gate</h4>
                        <div id="rankingGate" class="space-y-2"></div>
                    </div>

                    <div>
                        <h4 class="font-bold text-blue-400 mb-2">Primera recta</h4>
                        <div id="rankingFirst" class="space-y-2"></div>
                    </div>

                    <div>
                        <h4 class="font-bold text-purple-400 mb-2">Curvas</h4>
                        <div id="rankingMiddle" class="space-y-2"></div>
                    </div>

                    <div>
                        <h4 class="font-bold text-green-400 mb-2">Sprint final</h4>
                        <div id="rankingFinal" class="space-y-2"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Hits de la sesión</h3>
            <div id="hitsGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-4"></div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/Performance/session-live.js') ?>"></script>

<?= $this->endSection() ?>