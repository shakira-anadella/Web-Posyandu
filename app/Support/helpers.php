<?php

if (!function_exists('gizi_label')) {
    function gizi_label(?string $label): string
    {
        if ($label === null || $label === '') {
            return '-';
        }

        $map = [
            'N' => 'Normal',
        ];

        return $map[$label] ?? $label;
    }
}
