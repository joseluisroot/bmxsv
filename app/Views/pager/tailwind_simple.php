<?php
$pager->setSurroundCount(0); // solo prev / next
if (! $pager->hasPrevious() && ! $pager->hasNext()) return;
?>
<nav class="inline-flex w-full items-center justify-between gap-3" aria-label="Paginación móvil">
    <!-- Anterior -->
    <?php if ($pager->hasPrevious()): ?>
        <a href="<?= $pager->getPrevious() ?>"
           class="flex-1 text-center px-4 py-3 rounded-full border bg-white hover:bg-gray-50 active:scale-[.99] transition text-gray-700">
            ‹ Anterior
        </a>
    <?php else: ?>
        <span class="flex-1 text-center px-4 py-3 rounded-full border bg-gray-100 text-gray-400 cursor-not-allowed">‹ Anterior</span>
    <?php endif; ?>

    <!-- Siguiente -->
    <?php if ($pager->hasNext()): ?>
        <a href="<?= $pager->getNext() ?>"
           class="flex-1 text-center px-4 py-3 rounded-full border bg-red-600 text-white hover:bg-red-700 active:scale-[.99] transition">
            Siguiente ›
        </a>
    <?php else: ?>
        <span class="flex-1 text-center px-4 py-3 rounded-full border bg-gray-100 text-gray-400 cursor-not-allowed">Siguiente ›</span>
    <?php endif; ?>
</nav>
