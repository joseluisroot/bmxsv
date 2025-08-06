<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'BMXSV') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
        h1, h2, h3, .font-display {
            font-family: 'Bebas Neue', cursive;
            letter-spacing: 1px;
        }

        .menu-link.active {
            color: #dc2626; /* red-600 */
            font-weight: bold;
            border-bottom: 2px solid #dc2626;
        }

        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out both;
        }

        .fc .fc-toolbar-title {
            @apply text-xl sm:text-2xl font-bold text-gray-800;
        }

        .fc .fc-daygrid-event {
            @apply text-xs sm:text-sm rounded px-1;
        }

        .fc .fc-button {
            @apply bg-red-600 text-white hover:bg-red-700 border-0 rounded shadow;
        }

        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            @apply bg-red-700;
        }

        .fc .fc-daygrid-day-number {
            @apply text-gray-700 font-medium;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.4s ease-out both;
        }

        /* animación suave para opacidad del fondo */
        .fade-out {
            opacity: 0 !important;
            transition: opacity 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
