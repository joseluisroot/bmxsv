<?php
$pager->setSurroundCount(1);
?>
<?php if ($pager->hasPrevious() || $pager->hasNext()) : ?>
    <nav class="inline-flex gap-2" aria-label="Paginación">
        <?php if ($pager->hasPrevious()) : ?>
            <a class="px-3 py-2 rounded border bg-white hover:bg-gray-50"
               href="<?= $pager->getFirst() ?>">« Primero</a>
            <a class="px-3 py-2 rounded border bg-white hover:bg-gray-50"
               href="<?= $pager->getPrevious() ?>">‹ Anterior</a>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <a href="<?= $link['uri'] ?>"
               class="px-3 py-2 rounded border <?= $link['active'] ? 'bg-red-600 text-white border-red-600' : 'bg-white hover:bg-gray-50' ?>">
                <?= $link['title'] ?>
            </a>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <a class="px-3 py-2 rounded border bg-white hover:bg-gray-50"
               href="<?= $pager->getNext() ?>">Siguiente ›</a>
            <a class="px-3 py-2 rounded border bg-white hover:bg-gray-50"
               href="<?= $pager->getLast() ?>">Último »</a>
        <?php endif ?>
    </nav>
<?php endif ?>
