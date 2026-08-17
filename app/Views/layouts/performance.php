<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'BTPS') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btps-swal-popup {
            border: 1px solid #1e293b !important;
            border-radius: 1rem !important;
            box-shadow: 0 24px 80px rgba(2, 6, 23, .55) !important;
        }
        .btps-swal-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            letter-spacing: -.01em !important;
        }
        .btps-swal-text {
            color: #94a3b8 !important;
            font-size: .925rem !important;
        }
        .btps-swal-confirm,
        .btps-swal-cancel {
            border-radius: .65rem !important;
            padding: .65rem 1rem !important;
            font-weight: 700 !important;
            box-shadow: none !important;
        }
        .swal2-timer-progress-bar {
            background: #06b6d4 !important;
        }
    </style>
    <script src="<?= base_url('assets/js/performance/btps-alerts.js') ?>"></script>
</head>

<body class="bg-slate-950 text-white">

<div class="min-h-screen flex">

    <aside class="w-72 bg-slate-900 border-r border-slate-800 hidden lg:block">
        <div class="p-6 border-b border-slate-800">
            <p class="text-cyan-400 text-xs font-bold">BMXSV</p>
            <h1 class="text-xl font-bold">BTPS</h1>
            <p class="text-slate-400 text-xs mt-1">BMX Timing & Performance System</p>
        </div>

        <nav class="p-4 space-y-2 text-sm">
            <a href="<?= base_url('performance/sessions') ?>" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Sesiones</a>
            <a href="<?= base_url('performance/club/ranking') ?>" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Rankings</a>
            <div class="pt-3 mt-3 border-t border-slate-800">
                <p class="px-4 pb-2 text-[11px] uppercase tracking-wider text-slate-500">Hardware</p>
                <a href="<?= base_url('performance/hardware/aats') ?>" class="block px-4 py-3 rounded-lg hover:bg-slate-800">AAT Manager</a>
                <a href="<?= base_url('performance/hardware/btns') ?>" class="block px-4 py-3 rounded-lg hover:bg-slate-800">BTN / Device Manager</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 min-w-0">
        <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6">
            <h2 class="font-bold text-lg"><?= esc($pageTitle ?? 'BTPS') ?></h2>
            <div class="text-sm text-slate-400"><?= date('d/m/Y') ?></div>
        </header>

        <section class="p-6 max-w-7xl mx-auto">
            <?= $this->renderSection('content') ?>
        </section>
    </main>

</div>

</body>
</html>