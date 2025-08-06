<footer class="bg-black text-white py-10 px-6">
    <div class="container mx-auto text-center space-y-4">
        <!-- Logo e identidad -->
        <div class="flex justify-center items-center space-x-3">
            <img src="<?= base_url('/images/logo.png') ?>" alt="BMXSV" class="h-10">
            <span class="text-xl font-display">BMXSV</span>
        </div>

        <!-- Enlaces secundarios -->
        <div class="space-x-4 text-sm">
            <a href="#agenda" class="hover:underline">Agenda</a>
            <a href="#ranking" class="hover:underline">Ranking</a>
            <a href="#galeria" class="hover:underline">Galería</a>
            <a href="#noticias" class="hover:underline">Noticias</a>
            <a href="#contacto" class="hover:underline">Contacto</a>
        </div>

        <!-- Redes sociales -->
        <div class="flex justify-center space-x-6 text-xl">
            <a href="https://www.facebook.com/BMXRaceElSalvador" target="_blank" class="hover:text-red-500"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/BMXRaceElSalvador" target="_blank" class="hover:text-red-500"><i class="fab fa-instagram"></i></a>
        </div>

        <!-- Derechos -->
        <p class="text-sm text-gray-400 mt-4">
            &copy; <?= date('Y') ?> BMXSV - Bicicross El Salvador. Todos los derechos reservados.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
<?= $calendarScript ?? '' ?>
</body>
</html>
