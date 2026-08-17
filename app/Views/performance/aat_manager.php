<?= $this->extend('layouts/performance') ?>
<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-cyan-400 font-semibold text-sm">BTPS Hardware</p>
            <h1 class="text-3xl font-bold">AAT Manager</h1>
            <p class="text-slate-400 mt-1">Inventario, propiedad, préstamo, alquiler y asignación de Athlete Active Transponders.</p>
        </div>
        <a href="<?= base_url('performance/hardware/btns') ?>" class="px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700">Gestionar BTN →</a>
    </div>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-slate-400 text-xs">Total</p><p id="kpiTotal" class="text-2xl font-bold">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-slate-400 text-xs">Disponibles</p><p id="kpiAvailable" class="text-2xl font-bold text-green-400">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-slate-400 text-xs">Asignados</p><p id="kpiAssigned" class="text-2xl font-bold text-cyan-400">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-slate-400 text-xs">Mantenimiento</p><p id="kpiMaintenance" class="text-2xl font-bold text-amber-400">0</p></div>
    </section>

    <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h2 class="font-bold text-xl mb-4">Registrar nuevo AAT</h2>
        <form id="aatForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div><label class="text-sm text-slate-400">UID *</label><input id="uid" required placeholder="BTPS-AAT-000001" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Serial</label><input id="serialNumber" placeholder="AAT-000001" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Firmware</label><input id="firmwareVersion" placeholder="0.1.0" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Propiedad</label><select id="ownershipType" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><option value="club">Club / BTPS</option><option value="athlete">Atleta</option></select></div>
            <div><label class="text-sm text-slate-400">Atleta propietario</label><select id="ownerAthleteId" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><option value="">No aplica</option></select></div>
            <div><label class="text-sm text-slate-400">Notas</label><input id="aatNotes" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div class="lg:col-span-3 flex gap-3 items-center"><button class="px-5 py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg font-bold">Registrar AAT</button><span id="aatMessage" class="text-sm text-slate-400"></span></div>
        </form>
    </section>

    <section class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-800"><h2 class="font-bold text-xl">Inventario AAT</h2></div>
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-950/60 text-slate-400"><tr><th class="text-left p-3">UID</th><th class="text-left p-3">Propiedad</th><th class="text-left p-3">Estado</th><th class="text-left p-3">Atleta actual</th><th class="text-left p-3">Firmware/Batería</th><th class="text-left p-3">Acciones</th></tr></thead><tbody id="aatTable"></tbody></table></div>
    </section>
</div>

<div id="assignModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-700 rounded-xl p-6 w-full max-w-lg">
        <h3 class="text-xl font-bold mb-4">Asignar <span id="assignUid"></span></h3>
        <input type="hidden" id="assignAatId">
        <div class="space-y-4">
            <div><label class="text-sm text-slate-400">Atleta</label><select id="assignAthlete" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></select></div>
            <div><label class="text-sm text-slate-400">Tipo</label><select id="assignmentType" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><option value="loan">Préstamo</option><option value="rental">Alquiler</option><option value="permanent">Permanente</option></select></div>
            <div><label class="text-sm text-slate-400">Sesión (opcional)</label><select id="assignmentSession" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><option value="">Sin sesión específica</option></select></div>
            <div class="flex justify-end gap-2"><button type="button" onclick="closeAssignModal()" class="px-4 py-2 bg-slate-700 rounded-lg">Cancelar</button><button type="button" onclick="submitAssignment()" class="px-4 py-2 bg-cyan-600 rounded-lg font-bold">Asignar</button></div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/performance/aat-manager.js') ?>"></script>
<?= $this->endSection() ?>
