<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GainModel;
use App\Models\ClientsModel;
class TransactionController extends BaseController
{
    public function index()
    {
        return $this->gains();
    }

    public function gains()
    {
        $gainModel   = new GainModel();
        $tousLesGains = $gainModel->findAll();

        $operation = $this->request->getGet('operation');
        $recherche = trim((string) $this->request->getGet('recherche'));

        $gains = array_filter($tousLesGains, function ($gain) use ($operation, $recherche) {
            if ($operation && $gain['type_operation'] !== $operation) {
                return false;
            }
            if ($recherche !== '' && stripos($gain['type_operation'], $recherche) === false) {
                return false;
            }
            return true;
        });

        return view('operateur/gains', [
            'gains'      => $gains,
            'gainGlobal' => array_sum(array_column($tousLesGains, 'gain_total')),
            'operations' => array_column($tousLesGains, 'type_operation'),
            'operation'  => $operation,
            'recherche'  => $recherche,
        ]);
    }

    public function listeClient()
    {
        $clientModel = new ClientsModel();

        return view('operateur/clients', [
            'clients' => $clientModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function voirTransactionClient($id)
    {
        $clientModel = new ClientsModel();
        $client = $clientModel->find($id);

        if (!$client) {
            return redirect()->to('/operateur/clients')->with('error', 'Client introuvable.');
        }

        $db = \Config\Database::connect();

        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        $builder = $db->table('vue_historique_transaction')
            ->where('numero', $client['numero']);

        if ($dateDebut) {
            $builder->where('date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builder->where('date_transaction <=', $dateFin);
        }

        return view('operateur/client_detail', [
            'client'       => $client,
            'transactions' => $builder->get()->getResult(),
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
        ]);
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
