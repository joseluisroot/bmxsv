<?= $this->extend('layouts/performance') ?>

<?= $this->section('content') ?>

<input type="hidden" id="sessionId" value="<?= esc($sessionId) ?>">

<div class="mb-6">
    <h1 class="text-3xl font-bold">Simulador de Sensores</h1>
    <p class="text-slate-400">Simula lecturas y escenarios multi atleta para BTPS.</p>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
    <label class="block mb-2">Hit de entrenamiento</label>
    <select id="hitSelector" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2"></select>
</div>

<div class="mt-6 bg-slate-900 border border-slate-800 rounded-xl p-5">
    <h3 class="text-xl font-bold mb-4">Simulación multi atleta</h3>
    <div id="multiHitList" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
    <button id="multiRunBtn" type="button" class="mt-4 bg-yellow-600 hover:bg-yellow-500 px-5 py-2 rounded-lg font-bold">🏁 Simular múltiples atletas</button>
</div>

<div class="mt-4 flex flex-wrap gap-3">
    <button id="autoRunBtn" type="button" class="bg-green-600 hover:bg-green-500 px-5 py-2 rounded-lg font-bold">▶ Simular carrera completa</button>
    <button id="createNextHitBtn" type="button" class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2 rounded-lg font-bold">➕ Nuevo hit para atleta seleccionado</button>
    <button id="clearLogBtn" type="button" class="bg-slate-700 hover:bg-slate-600 px-5 py-2 rounded-lg font-bold">Limpiar log</button>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6">
    <button data-point="TP01" class="sensor-btn bg-cyan-600 p-4 rounded-lg">TP01 Gate</button>
    <button data-point="TP02" class="sensor-btn bg-blue-600 p-4 rounded-lg">TP02</button>
    <button data-point="TP03" class="sensor-btn bg-purple-600 p-4 rounded-lg">TP03</button>
    <button data-point="TP04" class="sensor-btn bg-pink-600 p-4 rounded-lg">TP04</button>
    <button data-point="TP05" class="sensor-btn bg-orange-600 p-4 rounded-lg">TP05</button>
    <button data-point="TP06" class="sensor-btn bg-green-600 p-4 rounded-lg">TP06 Meta</button>
</div>

<div id="simulatorLog" class="mt-6 bg-slate-900 border border-slate-800 rounded-xl p-5"></div>

<script src="<?= base_url('assets/js/performance/session-simulator.js') ?>"></script>

<?= $this->endSection() ?>
