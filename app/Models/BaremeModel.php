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
}