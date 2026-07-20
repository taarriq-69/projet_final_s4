<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientsModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'numero', 'date_creation'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'numero' => 'required|exact_length[9]|is_unique[clients.numero]',
    ];

    protected $validationMessages = [
        'numero' => [
            'required'     => 'Le numéro est obligatoire.',
            'exact_length' => 'Le numéro doit faire exactement 9 chiffres.',
            'is_unique'    => 'Ce numéro est déjà utilisé.',
        ],
    ];
}