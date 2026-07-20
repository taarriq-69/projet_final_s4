<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['prefixe'];



    protected $validationRules      = [
        'prefixe' => 'min_length(3)|max_length(3)|formValide|is_unique'
    ];
    protected $validationMessages   = [];
   
}
