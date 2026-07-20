<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GainModel;
class TransactionController extends BaseController
{
    public function index()
    {
    }

    public function totalGainParOperation($operation){
        $gainModel = new GainModel();
        $data['gains'] = $gainModel->where('type_operation',$operation)->findAll(); 
        return redirect()->back()->with('gains',$data);
    }

    public function totalGain(){
        $gain = new GainModel();
        $data['gains'] = $gain->findAll();
        return redirect()->back()->with('gains',$data);
    }

    public function listeTransaction(){
        $transaction = new TransactionModel();
        $data['transactions'] = $transaction->findAll();
        return view('transaction/liste_transaction', $data);
    }

    public function recherheTransaction($id_operation,$recherche){
        $transaction = new TransactionModel();
        $data['transactions'] = $transaction->find($id_operation);
        return redirect()->back()->with('transactions',$data);
    }

    
}
