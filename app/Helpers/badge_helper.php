<?php

if (!function_exists('badge_operation')) {
    function badge_operation(string $libelle): string
    {
        $classes = [
            'DEPOT'     => 'badge-depot',
            'RETRAIT'   => 'badge-retrait',
            'TRANSFERT' => 'badge-transfert',
        ];

        return $classes[$libelle] ?? 'badge-neutral';
    }
}

if (!function_exists('montant_ar')) {
    function montant_ar($valeur): string
    {
        return number_format((float) $valeur, 0, ',', ' ') . ' Ar';
    }
}
