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
            $clientId   = session()->get('client_id');
            $numeroDest = $this->request->getPost('numero_destinataire');
            $valeur     = (int) $this->request->getPost('valeur');

            if (!$clientId) {
                return redirect()->to('/');
            }

            if (empty($numeroDest) || $valeur <= 0) {
                return redirect()->back()
                    ->with('error', 'Veuillez renseigner un numéro de destinataire et un montant valide.');
            }

            // Recherche du destinataire par son numéro
            $destinataire = $this->clientModel
                ->where('numero', $numeroDest)
                ->first();

            if (!$destinataire) {
                return redirect()->back()
                    ->with('error', "Ce numéro de destinataire n'est pas enregistré.");
            }

            if ((int) $destinataire['id'] === (int) $clientId) {
                return redirect()->back()
                    ->with('error', 'Vous ne pouvez pas effectuer un transfert vers votre propre compte.');
            }

            // Frais de transfert calculés sur la base du barème TRANSFERT (id 3)
            $frais        = $this->calculerFrais(3, $valeur);
            $montantTotal = $valeur + $frais;

            // Le donneur doit disposer de la valeur transférée + les frais
            if (!$this->verifierSolde($clientId, $montantTotal)) {
                return redirect()->back()
                    ->with('error', 'Solde insuffisant pour effectuer ce transfert.');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // Débit chez le donneur : valeur transférée + frais (enregistré comme un retrait)
            $this->transactionModel->insert([
                'client_id'         => $clientId,
                'type_operation_id' => 2, // RETRAIT
                'valeur'            => $montantTotal,
                'frais'             => $frais,
                'date_transaction'  => date('Y-m-d'),
            ]);

            // Crédit chez le receveur : seulement la valeur transférée, sans les frais
            $this->transactionModel->insert([
                'client_id'         => $destinataire['id'],
                'type_operation_id' => 1, // DEPOT
                'valeur'            => $valeur,
                'frais'             => 0,
                'date_transaction'  => date('Y-m-d'),
            ]);

            // Traçabilité du transfert
            $db->table('transfert')->insert([
                'client_source'      => $clientId,
                'client_destination' => $destinataire['id'],
                'valeur'             => $valeur,
                'date_transfert'     => date('Y-m-d'),
            ]);

            $db->transComplete();

            if (!$db->transStatus()) {
                return redirect()->back()
                    ->with('error', "Une erreur est survenue lors du transfert. Veuillez réessayer.");
            }

            return redirect()->to('/transfert')
                ->with('message', "Transfert de {$valeur} effectué avec succès vers {$destinataire['nom']} ({$frais} de frais).");
        }

        return view('clients/transfert_form');
    }

    public function voirSolde()
    {
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Solde total du client
        $soldeRow = $db->table('vue_solde_client')
            ->where('id', $clientId)
            ->get()
            ->getRow();

        $solde = $soldeRow ? $soldeRow->solde : 0;

        // Liste de toutes les transactions du client (dépôts, retraits, transferts)
        $transactions = $db->table('vue_historique_transaction')
            ->where('numero', $this->clientModel->find($clientId)['numero'])
            ->orderBy('date_transaction', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $client = $this->clientModel->find($clientId);

        return view('clients/solde', [
            'client'       => $client,
            'solde'        => $solde,
            'transactions' => $transactions,
        ]);
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