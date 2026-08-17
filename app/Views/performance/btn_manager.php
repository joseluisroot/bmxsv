<?= $this->extend('layouts/performance') ?>
<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-cyan-400 font-semibold text-sm">BTPS Hardware</p>
            <h1 class="text-3xl font-bold">BTN / Device Manager</h1>
            <p class="text-slate-400 mt-1">Configura nodos TP, endpoint, red, firmware, reloj y estado operativo.</p>
        </div>
        <a href="<?= base_url('performance/hardware/aats') ?>" class="px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700">← Gestionar AAT</a>
    </div>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-xs text-slate-400">Nodos</p><p id="btnTotal" class="text-2xl font-bold">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-xs text-slate-400">Healthy</p><p id="btnHealthy" class="text-2xl font-bold text-green-400">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-xs text-slate-400">Clock Locked</p><p id="btnLocked" class="text-2xl font-bold text-cyan-400">0</p></div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4"><p class="text-xs text-slate-400">Warnings</p><p id="btnWarnings" class="text-2xl font-bold text-amber-400">0</p></div>
    </section>

    <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <h2 id="btnFormTitle" class="font-bold text-xl mb-4">Registrar BTN</h2>
        <form id="btnForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="hidden" id="btnId">
            <div><label class="text-sm text-slate-400">Código *</label><input id="deviceCode" required placeholder="BTPS-TP02" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Punto de control *</label><select id="timingPointId" required class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></select></div>
            <div><label class="text-sm text-slate-400">Firmware</label><input id="btnFirmware" placeholder="0.1.0" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Modo de red</label><select id="networkMode" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"><option value="local">Local</option><option value="cloud">Cloud</option><option value="auto">Auto / Failover</option></select></div>
            <div class="lg:col-span-2"><label class="text-sm text-slate-400">Endpoint</label><input id="endpointUrl" placeholder="http://192.168.50.10/api/timing/pass" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">IP del nodo</label><input id="ipAddress" placeholder="192.168.50.102" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Tipo sensor</label><input id="sensorType" value="AAT_LF_RF" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div><label class="text-sm text-slate-400">Notas</label><input id="btnNotes" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></div>
            <div class="lg:col-span-3 flex gap-3 items-center"><button class="px-5 py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg font-bold">Guardar BTN</button><button type="button" onclick="resetBtnForm()" class="px-4 py-2 bg-slate-700 rounded-lg">Nuevo</button><span id="btnMessage" class="text-sm text-slate-400"></span></div>
        </form>
    </section>

    <section class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="p-5 border-b border-slate-800"><h2 class="font-bold text-xl">Nodos de Timing</h2></div>
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-950/60 text-slate-400"><tr><th class="text-left p-3">Nodo / TP</th><th class="text-left p-3">Red</th><th class="text-left p-3">Reloj</th><th class="text-left p-3">Salud</th><th class="text-left p-3">Última conexión</th><th class="text-left p-3">Acción</th></tr></thead><tbody id="btnTable"></tbody></table></div>
    </section>
</div>

<script src="<?= base_url('assets/js/performance/btn-manager.js') ?>"></script>
<?= $this->endSection() ?>
