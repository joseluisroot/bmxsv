<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metas base -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Título y descripción dinámicos -->
    <title><?= esc($title ?? 'BMXSV | Bicicross El Salvador – Noticias, Agenda, Ranking y Atletas') ?></title>
    <meta name="description" content="<?= esc($descripcion ?? 'BMXSV: agenda de carreras, resultados, ranking mensual y perfiles de atletas de BMX Race en El Salvador. Únete a la comunidad y entrena con nosotros.') ?>" />
    <meta name="robots" content="index,follow,max-image-preview:large" />

    <!-- Canonical dinámico (fallback al home) -->
    <?php
    $canonical = $canonical ?? (function_exists('base_url') ? base_url() : '/');
    $og_image  = $og_image  ?? (function_exists('base_url') ? base_url('images/og/home.jpg') : '/images/og/home.jpg');
    ?>
    <link rel="canonical" href="<?= esc($canonical) ?>" />

    <!-- Hreflang (puedes duplicar si tienes más idiomas) -->
    <link rel="alternate" href="<?= esc($canonical) ?>" hreflang="es-SV" />
    <link rel="alternate" href="<?= esc($canonical) ?>" hreflang="x-default" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="BMXSV" />
    <meta property="og:title" content="<?= esc($title ?? 'BMXSV | Bicicross El Salvador') ?>" />
    <meta property="og:description" content="<?= esc($descripcion ?? 'Agenda, resultados, ranking y atletas del BMX salvadoreño.') ?>" />
    <meta property="og:url" content="<?= esc($canonical) ?>" />
    <meta property="og:image" content="<?= esc($og_image) ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= esc($title ?? 'BMXSV | Bicicross El Salvador') ?>" />
    <meta name="twitter:description" content="<?= esc($descripcion ?? 'Agenda, resultados, ranking y atletas del BMX salvadoreño.') ?>" />
    <meta name="twitter:image" content="<?= esc($og_image) ?>" />

    <!-- Icons / PWA (opcional pero recomendado) -->
    <link rel="icon" href="<?= base_url('icons/favicon.ico') ?>" sizes="any" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('icons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('icons/icon-192.png') ?>" sizes="192x192" />
    <link rel="apple-touch-icon" href="<?= base_url('icons/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">
    <meta name="theme-color" content="#dc2626" />

    <!-- Preconnects -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />

    <!-- Fuentes y CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <!-- Tailwind (si no haces build local) -->
    <script src="https://cdn.tailwindcss.com" defer></script>

    <!-- Preload de imágenes críticas -->
    <link rel="preload" as="image" href="<?= base_url('images/hero.jpg') ?>" fetchpriority="high" />
    <link rel="preload" as="image" href="<?= base_url('images/Logo.PNG') ?>" />

    <!-- Estilos mínimos -->
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Open Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Bebas Neue', cursive; letter-spacing: .5px; }
        .menu-link.active { color: #dc2626; font-weight: bold; border-bottom: 2px solid #dc2626; }
        @keyframes fade-in-up { 0%{opacity:0;transform:translateY(20px)} 100%{opacity:1;transform:translateY(0)} }
        .animate-fade-in-up{ animation: fade-in-up .8s ease-out both; }
        .fade-out { opacity: 0 !important; transition: opacity .3s ease-out; }
        /* FullCalendar utilidades con Tailwind (si las usas) */
        .fc .fc-toolbar-title { font-weight:700; color:#1f2937; }
    </style>

    <!-- JSON-LD: Organization + Website -->
    <script type="application/ld+json">
        {
          "@context":"https://schema.org",
          "@type":"SportsOrganization",
          "name":"BMXSV",
          "url":"<?= esc((function_exists('base_url') ? base_url() : '/')) ?>",
      "logo":"<?= esc(base_url('images/Logo.PNG')) ?>",
      "sameAs":[
        "https://www.facebook.com/ESAbicicross",
        "https://www.instagram.com/esabicicross"
      ]
    }
    </script>
    <script type="application/ld+json">
        {
          "@context":"https://schema.org",
          "@type":"WebSite",
          "name":"BMXSV",
          "url":"<?= esc((function_exists('base_url') ? base_url() : '/')) ?>",
      "potentialAction":{
        "@type":"SearchAction",
        "target":"<?= esc((function_exists('base_url') ? base_url('buscar') : '/buscar')) ?>?q={search_term_string}",
        "query-input":"required name=search_term_string"
      }
    }
    </script>

    <!-- JSON-LD extra dinámico (ItemList de noticias, Events, etc.) -->
    <?= $structuredDataNews ?? '' ?>
    <?= $structuredDataEvents ?? '' ?>
</head>
<body class="bg-gray-100 text-gray-800">
<!-- Skip link accesibilidad -->
<a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-black text-white px-3 py-2 rounded">Saltar al contenido</a>
