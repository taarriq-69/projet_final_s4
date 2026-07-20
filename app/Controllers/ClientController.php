<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ClientsModel;
use App\Models\BaremeModel;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $transactionModel;
    protected $baremeModel;

    public function __construct()
    {
        $this->clientModel = new ClientsModel();
        $this->transactionModel = new TransactionModel();
        $this->baremeModel = new BaremeModel();
    }

    public function accueil()
    {
        if ($this->request->getMethod() === 'POST') {
            $numero = $this->request->getPost('numero');

            // Validation
            $validation = \Config\Services::validation();
            $validation->setRule('numero', 'Numéro', 'valide_prefixe');

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->with('error', $validation->getError('numero'));
            }

            $client = $this->clientModel
                ->where('numero', $numero)
                ->first();

            if (!$client) {
                return redirect()->back()
                    ->with('error', 'Ce numéro n\'est pas enregistré.');
            }

            session()->set([
                'client_id' => $client['id'],
                'client_nom' => $client['nom']
            ]);

            return redirect()->to('/home');
        }

        return view('accueil');
    }

    public function home()
    {
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        $client = $this->clientModel->find($clientId);

        return view('client_home', ['client' => $client]);
    }

    public function faireUnTransfert()
    {
        if ($this->request->getMethod() === 'POST') {
            return redirect()->to('/transfert')
                ->with('message', 'Fonctionnalité à venir.');
        }

        return view('clients/transfert_form');
    }

    public function voirHistorique()
    {
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        $client = $this->clientModel->find($clientId);

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

        $transactions = $builder->get()->getResult();

        return view('clients/historique', [
            'transactions' => $transactions,
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
        ]);
    }

    public function faireUnRetrait()
    {
        if ($this->request->getMethod() === 'POST') {
            $clientId = session()->get('client_id');
            $valeur   = $this->request->getPost('valeur');

            if (!$this->verifierSolde($clientId, $valeur)) {
                return redirect()->back()
                    ->with('error', 'Solde insuffisant.');
            }

            $this->enregistrerTransaction($clientId, 2, $valeur);

            return redirect()->to('/retrait')
                ->with('message', 'Retrait effectué avec succès !');
        }

        return view('clients/retrait_form');
    }

    public function faireUnDepot()
    {
        if ($this->request->getMethod() === 'POST') {
            $clientId = session()->get('client_id');
            $valeur   = $this->request->getPost('valeur');

            $this->enregistrerTransaction($clientId, 1, $valeur);

            return redirect()->to('/depot')
                ->with('message', 'Dépôt effectué avec succès !');
        }

        return view('clients/depot_form');
    }

    private function enregistrerTransaction($clientId, $typeOperation, $valeur)
    {
        $frais = $this->calculerFrais($typeOperation, $valeur);

        return $this->transactionModel->insert([
            'client_id'         => $clientId,
            'type_operation_id' => $typeOperation,
            'valeur'            => $valeur,
            'frais'             => $frais,
            'date_transaction'  => date('Y-m-d'),
        ]);
    }

    private function calculerFrais($typeOperation, $valeur)
    {
        $bareme = $this->baremeModel
            ->where('type_operation_id', $typeOperation)
            ->where('valeur_min <=', $valeur)
            ->where('valeur_max >=', $valeur)
            ->first();

        return $bareme ? $bareme['frais'] : 0;
    }

    private function verifierSolde($clientId, $valeur)
    {
        $db = \Config\Database::connect();

        $soldeRow = $db->table('vue_solde_client')
            ->where('id', $clientId)
            ->get()
            ->getRow();

        $solde = $soldeRow ? $soldeRow->solde : 0;

        return $solde >= $valeur;
    }
}