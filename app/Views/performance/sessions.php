<?= $this->extend('layouts/performance') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
    <div>
        <p class="text-sm text-cyan-400 font-semibold">BMXSV Timing & Performance System</p>
        <h1 class="text-3xl font-bold">BTPS Session Manager</h1>
        <p class="text-slate-400">Crea, configura y opera sesiones de entrenamiento y sus hits.</p>
    </div>
    <button id="newSessionBtn" type="button" class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2 rounded-lg font-bold">
        + Nueva sesión
    </button>
</div>

<div id="sessionMessage" class="hidden mb-4 rounded-lg border px-4 py-3 text-sm"></div>

<section class="bg-slate-900 border border-slate-800 rounded-xl p-5 mb-6">
    <div class="flex items-center justify-between gap-4 mb-4">
        <h2 class="text-xl font-bold">Sesiones</h2>
        <button id="refreshSessionsBtn" type="button" class="text-sm bg-slate-800 hover:bg-slate-700 px-3 py-2 rounded-lg">Actualizar</button>
    </div>
    <div id="sessionsLoading" class="text-slate-400">Cargando sesiones...</div>
    <div id="sessionsList" class="hidden space-y-4"></div>
</section>

<section id="sessionEditor" class="hidden bg-slate-900 border border-slate-800 rounded-xl p-5">
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-cyan-400 font-semibold">Configuración operativa</p>
            <h2 id="editorTitle" class="text-xl font-bold">Nueva sesión</h2>
        </div>
        <button id="closeEditorBtn" type="button" class="text-slate-400 hover:text-white">Cerrar</button>
    </div>

    <form id="sessionForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" id="sessionEditId">

        <div>
            <label class="block text-sm text-slate-400 mb-1">Nombre *</label>
            <input id="sessionName" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" placeholder="Ej: Entrenamiento técnico sábado">
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Fecha *</label>
            <input id="sessionDate" type="date" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Pista</label>
            <input id="sessionTrack" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" placeholder="Pista BMX">
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Coach</label>
            <input id="sessionCoach" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Nodo inicio *</label>
            <select id="startNodeId" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></select>
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Nodo fin *</label>
            <select id="endNodeId" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></select>
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Modo de hits</label>
            <select id="hitMode" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <option value="manual">Manual</option>
                <option value="automatico">Automático por chip</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Configuración bicicleta por defecto</label>
            <select id="defaultConfigurationId" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <option value="">Sin configuración por defecto</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-slate-400 mb-1">Estado</label>
            <select id="sessionStatus" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2">
                <option value="borrador">Borrador</option>
                <option value="abierta">Abierta</option>
                <option value="cerrada">Cerrada</option>
            </select>
        </div>
        <div class="flex items-center gap-3 pt-6">
            <input id="autoCloseHit" type="checkbox" class="h-4 w-4">
            <label for="autoCloseHit" class="text-sm text-slate-300">Cerrar hit automáticamente al llegar al nodo final</label>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-slate-400 mb-1">Objetivo</label>
            <input id="sessionObjective" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2" placeholder="Ej: salida, primera recta, pista completa...">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-slate-400 mb-1">Notas</label>
            <textarea id="sessionNotes" rows="3" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></textarea>
        </div>
        <div class="md:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2 rounded-lg font-bold">Guardar sesión</button>
            <button id="cancelSessionBtn" type="button" class="bg-slate-700 hover:bg-slate-600 px-5 py-2 rounded-lg font-bold">Cancelar</button>
        </div>
    </form>
</section>

<script src="<?= base_url('assets/js/performance/session-manager.js') ?>"></script>

<?= $this->endSection() ?>
