<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | BMXSV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2c1b6c15e.js" crossorigin="anonymous"></script> <!-- FontAwesome -->
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header -->
<header class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <img src="<?= base_url('images/logo.png') ?>" alt="Logo BMX" class="h-10">
        <span class="font-display font-bold text-lg text-red-600">Dashboard BMXSV</span>
    </div>
    <a href="<?= base_url('logout') ?>" class="text-sm bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">
        <i class="fas fa-sign-out-alt mr-2"></i>Salir
    </a>
</header>

<!-- Layout -->
<div class="flex flex-col md:flex-row min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-white w-full md:w-64 p-4 shadow-md">
        <nav class="space-y-4">
            <a href="<?= base_url('dashboard') ?>" class="block text-red-600 font-semibold hover:underline">
                <i class="fas fa-home mr-2"></i>Inicio
            </a>
            <a href="<?= base_url('padre/atletas') ?>" class="block hover:text-red-600">
                <i class="fas fa-users mr-2"></i>Mis Atletas
            </a>
            <a href="#" class="block hover:text-red-600">
                <i class="fas fa-chart-line mr-2"></i>Progreso
            </a>
            <a href="#" class="block hover:text-red-600">
                <i class="fas fa-calendar mr-2"></i>Eventos
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <?= $this->renderSection('contenido') ?>
    </main>
</div>

</body>
</html>
