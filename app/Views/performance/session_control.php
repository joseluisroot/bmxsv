<?= $this->extend('layouts/Performance') ?>

<?= $this->section('content') ?>

    <input type="hidden" id="sessionId" value="<?= esc($sessionId) ?>">

    <div class="mb-6">
        <p class="text-sm text-cyan-400 font-semibold">BTPS Session Manager</p>
        <h1 class="text-3xl font-bold">Control de Sesión</h1>
        <p class="text-slate-400">Crear hits para atletas dentro de una sesión activa</p>
    </div>

    <div id="loading" class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        Cargando sesión...
    </div>

    <div id="sessionControlDashboard" class="hidden space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Sesión</p>
                <h2 id="sessionName" class="text-xl font-bold">--</h2>
                <p id="sessionDate" class="text-xs text-slate-500 mt-1">--</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Estado</p>
                <h2 id="sessionStatus" class="text-2xl font-bold text-green-400">--</h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-slate-400 text-sm">Hits registrados</p>
                <h2 id="hitsCount" class="text-3xl font-bold text-cyan-400">--</h2>
            </div>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Crear nuevo hit</h3>

            <form id="createHitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-slate-400 mb-1">Atleta ID</label>
                    <select
                            id="athleteId"
                            class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-400 mb-1">Configuración ID</label>
                    <select
                            id="configurationId"
                            class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-slate-400 mb-1">Notas del coach</label>
                    <textarea
                        id="notasCoach"
                        class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"
                        rows="3"
                        placeholder="Ej: Trabajar salida, cadencia o curvas..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-slate-400 mb-1">Sensación del atleta</label>
                    <textarea
                        id="sensacionAtleta"
                        class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"
                        rows="3"
                        placeholder="Ej: Se sintió rápido, cansado, estable..."></textarea>
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <button
                        type="submit"
                        class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2 rounded-lg font-bold">
                        Crear Hit
                    </button>

                    <span id="createHitMessage" class="text-sm text-slate-400"></span>
                </div>
            </form>
        </section>

        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 class="text-xl font-bold mb-4">Hits de la sesión</h3>
            <div id="sessionHitsList" class="space-y-3"></div>
        </section>

    </div>

    <script src="<?= base_url('assets/js/Performance/session-control.js') ?>"></script>

<?= $this->endSection() ?>