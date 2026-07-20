<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class ClientController extends BaseController
{
    public function faireUnRetrait()
    {
        $transactionModel = new TransactionModel();
        $db = \Config\Database::connect();

        if ($this->request->getMethod() === 'POST') {
            $clientId = $this->request->getPost('client_id');
            $valeur   = $this->request->getPost('valeur');

            // 1 - Vérifier le solde
            $soldeRow = $db->table('vue_solde_client')
                ->where('id', $clientId)
                ->get()
                ->getRow();
            $solde = $soldeRow ? $soldeRow->solde : 0;

            if ($solde < $valeur) {
                return redirect()->back()
                    ->with('error', 'Solde insuffisant. Solde actuel : ' . $solde);
            }

            // 2 - Récupérer les frais (type_operation_id = 2)
            $bareme = $db->table('bareme')
                ->where('type_operation_id', 2)
                ->where('valeur_min <=', $valeur)
                ->where('valeur_max >=', $valeur)
                ->get()
                ->getRow();

            $frais = $bareme ? $bareme->frais : 0;

            // 3 - Insérer la transaction
            $transactionModel->insert([
                'client_id'         => $clientId,
                'type_operation_id' => 2,
                'valeur'            => $valeur,
                'frais'             => $frais,
                'date_transaction'  => date('Y-m-d'),
            ]);

            return redirect()->to('/retrait')
                ->with('message', 'Retrait effectué avec succès !');
        }

        return view('clients/retrait_form');
    }

    public function faireUnDepot()
    {
        $transactionModel = new TransactionModel();

        if ($this->request->getMethod() === 'POST') {
            $clientId = $this->request->getPost('client_id');
            $valeur   = $this->request->getPost('valeur');

            $db = \Config\Database::connect();
            $bareme = $db->table('bareme')
                ->where('type_operation_id', 1)
                ->where('valeur_min <=', $valeur)
                ->where('valeur_max >=', $valeur)
                ->get()
                ->getRow();

            $frais = $bareme ? $bareme->frais : 0;

            $transactionModel->insert([
                'client_id'        => $clientId,
                'type_operation_id' => 1,
                'valeur'           => $valeur,
                'frais'            => $frais,
                'date_transaction' => date('Y-m-d'),
            ]);

            return redirect()->to('/depot')->with('message', 'Dépôt effectué avec succès !');
        }

        return view('clients/depot_form');
    }
}