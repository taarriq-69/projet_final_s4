<?php

namespace App\Validation;

use App\Models\PrefixeModel;

class CustumRules
{
    public function valide_prefixe(string $str, ?string &$error = null): bool
    {
        if (!preg_match('/^\d{9}$/', $str)) {
            $error = 'Le numéro doit faire exactement 9 chiffres.';
            return false;
        }

        $debut = substr($str, 0, 2);

        $prefixeModel = new PrefixeModel();
        $prefixes = $prefixeModel->findAll();

        foreach ($prefixes as $p) {
            if ($debut == (string)$p['prefixe']) {
                return true;
            }
        }

        $error = 'Le numéro doit commencer par un préfixe valide.';
        return false;
    }
}