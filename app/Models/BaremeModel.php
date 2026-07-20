<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'bareme';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['valeur_min', 'valeur_max', 'frais', 'type_operation_id'];

    protected $useTimestamps = false;

    protected $validationRules      = [
        'valeur_min'        => 'required|is_natural',
        'valeur_max'        => 'required|is_natural|greater_than_equal_to[valeur_min]',
        'frais'             => 'required|is_natural',
        'type_operation_id' => 'required|is_natural_no_zero',
    ];

    protected $validationMessages   = [
        'valeur_min' => [
            'required'   => 'La valeur minimale est obligatoire.',
            'is_natural' => 'La valeur minimale doit être un nombre positif.',
        ],
        'valeur_max' => [
            'required'              => 'La valeur maximale est obligatoire.',
            'is_natural'            => 'La valeur maximale doit être un nombre positif.',
            'greater_than_equal_to' => 'La valeur maximale doit être supérieure ou égale à la valeur minimale.',
        ],
        'frais' => [
            'required'   => 'Le frais est obligatoire.',
            'is_natural' => 'Le frais doit être un nombre positif.',
        ],
        'type_operation_id' => [
            'required' => 'Le type d\'opération est obligatoire.',
        ],
    ];
}