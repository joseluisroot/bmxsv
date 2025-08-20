<?php

if (! function_exists('format_time_ms')) {
    function format_time_ms(?int $ms): string
    {
        if ($ms === null) return '-';
        // 38900 -> "38.9s"
        $seconds = $ms / 1000;
        // Ajusta a 1 decimal (o 2 si te gusta más fino)
        return number_format($seconds, 1) . 's';
    }
}
