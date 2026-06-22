<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'BTPS') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-950 text-white">

<div class="min-h-screen flex">

    <aside class="w-72 bg-slate-900 border-r border-slate-800 hidden lg:block">
        <div class="p-6 border-b border-slate-800">
            <p class="text-cyan-400 text-xs font-bold">BMXSV</p>
            <h1 class="text-xl font-bold">BTPS</h1>
            <p class="text-slate-400 text-xs mt-1">
                BMX Timing & Performance System
            </p>
        </div>

        <nav class="p-4 space-y-2 text-sm">
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Dashboard</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Atletas</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Sesiones</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Rankings</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Coach Live</a>
        </nav>
    </aside>

    <main class="flex-1 min-w-0">
        <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6">
            <h2 class="font-bold text-lg">
                <?= esc($pageTitle ?? 'BTPS') ?>
            </h2>

            <div class="text-sm text-slate-400">
                <?= date('d/m/Y') ?>
            </div>
        </header>

        <section class="p-6 max-w-7xl mx-auto">
            <?= $this->renderSection('content') ?>
        </section>
    </main>

</div>

</body>
</html>