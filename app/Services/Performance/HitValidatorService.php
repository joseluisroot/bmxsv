<?php

namespace app\Services\Performance;

class HitValidatorService
{
    public function isValid(array $hit): bool
    {
        return empty($this->getErrors($hit));
    }

    public function getErrors(array $hit): array
    {
        $errors = [];

        $totalSeconds = $hit['total_seconds'] ?? null;

        if (
            $totalSeconds === null ||
            !is_numeric($totalSeconds) ||
            (float) $totalSeconds <= 0
        ) {
            $errors[] = 'El hit no tiene tiempo total válido.';
        }

        if (empty($hit['records']) || !is_array($hit['records'])) {
            $errors[] = 'El hit no tiene registros de tiempo.';
            return $errors;
        }

        $codes = array_column($hit['records'], 'codigo');

        if (!in_array('TP01', $codes, true)) {
            $errors[] = 'El hit no tiene TP01.';
        }

        if (!in_array('TP06', $codes, true)) {
            $errors[] = 'El hit no tiene TP06.';
        }

        $duplicates = array_diff_assoc($codes, array_unique($codes));

        if (!empty($duplicates)) {
            $errors[] = 'El hit tiene puntos de control duplicados.';
        }

        return $errors;
    }

    public function classify(array $hit): string
    {
        $errors = $this->getErrors($hit);

        if (empty($errors)) {
            return 'valid';
        }

        $codes = [];

        if (!empty($hit['records']) && is_array($hit['records'])) {
            $codes = array_column($hit['records'], 'codigo');
        }

        if (
            in_array('TP01', $codes, true) &&
            in_array('TP06', $codes, true)
        ) {
            return 'partial';
        }

        return 'invalid';
    }
}