<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
}
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id', 'client_id', 'type_operation_id', 'valeur', 'frais', 'date_transaction'];

  
    protected $validationRules      = [];
    protected $validationMessages   = [];

  
}
