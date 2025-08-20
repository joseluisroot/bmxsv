<?php
if (empty($periodo)): ?>
    <p class="text-gray-600 text-center">Aún no hay rankings publicados.</p>
<?php else: ?>
    <?php
    $label  = $periodo['nombre_publico'] ?: (sprintf('%02d', $periodo['mes']).'/'.$periodo['anio']);
    $metric = $periodo['metric'];
    ?>
    <div class="text-center text-sm text-gray-600 mb-4">
    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 border">
      <?= esc($label) ?> · Métrica: <?= esc($metric === 'time_ms' ? 'Tiempo' : 'Puntos') ?>
    </span>
    </div>

    <?php if (empty($agrupado)): ?>
        <p class="text-gray-600 text-center">No hay rankings registrados para este período.</p>
    <?php else: ?>
        <?php foreach ($agrupado as $categoria => $items): ?>
            <div class="mb-6">
                <h3 class="font-semibold text-lg mb-2"><?= esc($categoria) ?></h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-2 w-16">#</th>
                            <th class="p-2">Atleta</th>
                            <th class="p-2 text-right"><?= esc($metric === 'time_ms' ? 'Tiempo' : 'Puntos') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $row): ?>
                            <tr class="border-t">
                                <td class="p-2"><?= esc($row['posicion'] ?? '') ?></td>
                                <td class="p-2">
                                    <?php $url = route_to('atleta_perfil', $row['slug']); ?>
                                    <a href="<?= esc($url) ?>" class="font-semibold hover:underline hover:text-red-600">
                                        <?= esc($row['nombres'].' '.$row['apellidos']) ?>
                                    </a>
                                </td>
                                <td class="p-2 text-right">
                                    <?php if ($metric === 'time_ms'): ?>
                                        <?= esc(format_time_ms($row['valor_ms'])) ?>
                                    <?php else: ?>
                                        <?= number_format((float)$row['valor_num'], 1) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
