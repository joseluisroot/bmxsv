<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Padre - BMXSV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/YOUR_KIT.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<header class="bg-white shadow py-4 mb-6">
    <div class="container mx-auto flex justify-between items-center px-6">
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3">
            <img src="<?= base_url('images/logo.png') ?>" alt="BMXSV" class="w-10 h-10">
            <span class="font-display text-xl text-red-600 font-bold">BMXSV</span>
        </a>
        <a href="<?= base_url('logout') ?>" class="text-sm text-gray-700 hover:text-red-600">Cerrar sesión</a>
    </div>
</header>

<main>
    <?= $this->renderSection('content') ?>
</main>

</body>
</html>
