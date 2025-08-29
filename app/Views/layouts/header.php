<header id="siteHeader" class="bg-white/80 backdrop-blur-md shadow sticky top-0 z-50 transition-all duration-300">
    <div id="headerInner" class="container mx-auto flex justify-between items-center py-4 px-6 transition-all duration-300">

        <!-- Logo -->
        <a href="<?= base_url() ?>" class="flex items-center space-x-2" aria-label="Ir al inicio">
            <img
                    src="<?= base_url('/images/Logo.PNG') ?>"
                    alt="BMXSV Logo"
                    id="logoImg"
                    class="h-14 w-auto transition-all duration-300"
                    width="160" height="56"
                    decoding="async"
                    fetchpriority="high"
            >
        </a>

        <!-- Botón hamburguesa -->
        <button id="menu-toggle" class="text-gray-700 text-2xl md:hidden focus:outline-none" aria-label="Abrir menú">
            <i id="menu-icon" class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <!-- Menú escritorio -->
        <nav class="hidden md:flex space-x-4 text-sm font-semibold text-gray-700" aria-label="Navegación principal">
            <a href="#agenda" class="hover:text-red-600 w-full">Agenda</a>
            <a href="#resultados" class="hover:text-red-600 w-full">Resultados</a>
            <a href="#ranking" class="hover:text-red-600 w-full">Ranking</a>
            <a href="#atletas" class="hover:text-red-600 w-full">Atletas</a>
            <a href="#galeria" class="hover:text-red-600 w-full">Galería</a>
            <a href="#noticias" class="hover:text-red-600 w-full">Noticias</a>
            <a href="<?= base_url('faq') ?>" class="hover:text-red-600 w-full">Preguntas Frecuentes</a>
            <a href="#contacto" class="hover:text-red-600 w-full">Contacto</a>
            <?php if (session()->has('id')){ ?>
                <a href="<?= base_url('logout') ?>" class="text-sm text-gray-700 hover:underline ml-4">Cerrar sesión</a>
            <?php } else { ?>
                <a href="<?= base_url('login') ?>" class="hover:text-red-600 w-full">Ingreso</a>
            <?php } ?>
        </nav>
    </div>

    <!-- Menú móvil -->
    <nav id="mobile-menu"
         class="md:hidden overflow-hidden max-h-0 transition-all duration-300 ease-in-out flex flex-col items-center text-center px-6 text-sm font-semibold text-gray-700 space-y-2"
         aria-label="Navegación móvil">
        <a href="#agenda" class="menu-link hover:text-red-600 w-full">Agenda</a>
        <a href="#resultados" class="menu-link hover:text-red-600 w-full">Resultados</a>
        <a href="#ranking" class="menu-link hover:text-red-600 w-full">Ranking</a>
        <a href="#atletas" class="menu-link hover:text-red-600 w-full">Atletas</a>
        <a href="#galeria" class="menu-link hover:text-red-600 w-full">Galería</a>
        <a href="#noticias" class="menu-link hover:text-red-600 w-full">Noticias</a>
        <a href="<?= base_url('faq') ?>" class="hover:text-red-600 w-full">Preguntas Frecuentes</a>
        <a href="#contacto" class="menu-link hover:text-red-600 w-full">Contacto</a>
        <?php if (session()->has('id')){ ?>
            <a href="<?= base_url('logout') ?>" class="text-sm text-gray-700 hover:underline ml-4">Cerrar sesión</a>
        <?php } else { ?>
            <a href="<?= base_url('login') ?>" class="hover:text-red-600 w-full">Ingreso</a>
        <?php } ?>
    </nav>
</header>
