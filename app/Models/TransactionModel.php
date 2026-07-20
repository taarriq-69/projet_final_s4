<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['client_id', 'type_operation_id', 'valeur', 'frais', 'date_transaction'];
    protected $useTimestamps = false;
}