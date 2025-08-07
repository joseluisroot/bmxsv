<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <div class="flex flex-col items-center mb-6">
        <img src="<?= base_url('images/Logo.PNG') ?>" alt="Logo Bicicross" class="w-24 h-24 mb-4">
        <h1 class="text-2xl font-bold text-center text-gray-800">Iniciar Sesión</h1>
    </div>

    <?php if (session('error')): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/login') ?>" method="post" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
            <input type="email" id="email" name="email" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="<?= old('email') ?>">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="password" name="password" required
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
            Entrar al perfil
        </button>

        <div class="text-center mt-4">
            <a href="<?= base_url('/recuperar-contrasena') ?>" class="text-sm text-blue-600 hover:underline">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
    </form>
</div>

</body>
</html>
