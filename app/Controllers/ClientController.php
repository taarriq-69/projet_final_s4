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
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if ($this->request->getMethod() === 'POST') {
            $numeroDest       = trim((string) $this->request->getPost('numero_destinataire'));
            $valeur           = (int) $this->request->getPost('valeur');
            $avecFraisRetrait = $this->request->getPost('fraisRetrait') === "1";

            $resultat = $this->effectuerUnTransfert($clientId, $numeroDest, $valeur, $avecFraisRetrait);

            if (!$resultat['success']) {
                return redirect()->back()->with('error', $resultat['message']);
            }

            return redirect()->to('/transfert')
                ->with('message', $resultat['message'] . " ({$resultat['frais']} de frais)");
        }

        $client            = $this->clientModel->find($clientId);
        $operateurClient   = $this->getOperateurByNumero($client['numero']);
        $db                = \Config\Database::connect();
        $prefixes          = $db->table('prefixe')->get()->getResultArray();

        return view('clients/transfert_form', [
            'operateurClient' => $operateurClient,
            'prefixes'        => $prefixes,
        ]);
    }

    public function transfertMultiple()
    {
        $clientId = session()->get('client_id');

        if (!$clientId) {
            return redirect()->to('/');
        }

        if ($this->request->getMethod() === 'POST') {
            $numeros = (array) $this->request->getPost('numeros');
            $montant = (int) $this->request->getPost('montant');

            $numeros = array_values(array_filter(array_map('trim', $numeros), fn ($n) => $n !== ''));

            if (empty($numeros) || $montant <= 0) {
                return redirect()->back()
                    ->with('error', 'Veuillez renseigner au moins un numéro et un montant valide.');
            }

            // Vérifier que tous les numéros appartiennent au même opérateur
            $operateurs = [];
            foreach ($numeros as $numero) {
                $op = $this->getOperateurByNumero($numero);

                if ($op === null) {
                    return redirect()->back()
                        ->with('error', "Le numéro {$numero} n'appartient à aucun opérateur connu.");
                }

                $operateurs[] = $op;
            }

            if (count(array_unique($operateurs)) > 1) {
                return redirect()->back()
                    ->with('error', 'Tous les numéros doivent appartenir au même opérateur.');
            }

            $nombreNumeros     = count($numeros);
            $montantParNumero  = intdiv($montant, $nombreNumeros);

            if ($montantParNumero <= 0) {
                return redirect()->back()
                    ->with('error', "Le montant est trop faible pour être réparti entre {$nombreNumeros} numéros.");
            }

            $messages = $this->transfertMultipleVersNumeros($clientId, $numeros, $montantParNumero);

            return redirect()->to('/transfert-multiple')
                ->with('message', implode('<br>', $messages));
        }

        return view('clients/transfert_multiple_form');
    }

    /**
     * Exécute un transfert d'un même montant vers chaque numéro fourni.
     *
     * @param int   $clientId
     * @param array $numeros  tableau de numéros destinataires
     * @param int   $montant  montant envoyé à chaque numéro
     * @return array tableau de messages (un par numéro)
     */
    private function transfertMultipleVersNumeros($clientId, array $numeros, $montant)
    {
        $messages = [];

        foreach ($numeros as $numero) {
            $resultat = $this->effectuerUnTransfert($clientId, $numero, $montant, false);
            $prefixe  = $resultat['success'] ? '✔' : '✘';
            $messages[] = "{$prefixe} {$numero} : {$resultat['message']}";
        }

        return $messages;
    }

    /**
     * Logique commune à faireUnTransfert() et transfertMultiple().
     *
     * @return array{success:bool, message:string, frais:int}
     */
    private function effectuerUnTransfert($clientId, $numeroDest, $valeur, $avecFraisRetrait)
    {
        if (empty($numeroDest) || $valeur <= 0) {
            return ['success' => false, 'message' => 'Numéro ou montant invalide.', 'frais' => 0];
        }

        $expediteur = $this->clientModel->find($clientId);

        if ((string) $expediteur['numero'] === (string) $numeroDest) {
            return ['success' => false, 'message' => 'Vous ne pouvez pas transférer vers votre propre compte.', 'frais' => 0];
        }

        $operateurExpediteur   = $this->getOperateurByNumero($expediteur['numero']);
        $operateurDestinataire = $this->getOperateurByNumero($numeroDest);

        if ($operateurDestinataire === null) {
            return ['success' => false, 'message' => "Le numéro {$numeroDest} n'appartient à aucun opérateur connu.", 'frais' => 0];
        }

        $memeOperateur = ($operateurDestinataire === $operateurExpediteur);

        // Destinataire chez un autre opérateur : pas de frais de retrait,
        // mais une commission en % s'applique sur le montant envoyé.
        if (!$memeOperateur) {
            $avecFraisRetrait = false;
        }

        $destinataire = $this->clientModel->where('numero', $numeroDest)->first();

        $fraisTransfert     = $this->calculerFrais(3, $valeur);
        $fraisRetraitInclus = $avecFraisRetrait ? $this->prendreFraisRetrait($valeur) : 0;
        $commission         = $memeOperateur ? 0 : $this->getCommissionAutreOperateur($operateurDestinataire, $valeur);

        $totalFrais   = $fraisTransfert + $fraisRetraitInclus + $commission;
        $montantTotal = $valeur + $totalFrais;

        if (!$this->verifierSolde($clientId, $montantTotal)) {
            return ['success' => false, 'message' => "Solde insuffisant pour transférer vers {$numeroDest}.", 'frais' => 0];
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Débit chez l'expéditeur : valeur transférée + tous les frais (retrait/commission)
        $this->transactionModel->insert([
            'client_id'         => $clientId,
            'type_operation_id' => 2, // RETRAIT
            'valeur'            => $montantTotal,
            'frais'             => $totalFrais,
            'date_transaction'  => date('Y-m-d'),
        ]);

        if ($destinataire) {
            $this->transactionModel->insert([
                'client_id'         => $destinataire['id'],
                'type_operation_id' => 1, // DEPOT
                'valeur'            => $valeur,
                'frais'             => 0,
                'date_transaction'  => date('Y-m-d'),
            ]);

            $db->table('transfert')->insert([
                'client_source'      => $clientId,
                'client_destination' => $destinataire['id'],
                'valeur'             => $valeur,
                'date_transfert'     => date('Y-m-d'),
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return ['success' => false, 'message' => "Erreur technique lors du transfert vers {$numeroDest}.", 'frais' => 0];
        }

        return [
            'success' => true,
            'message' => "Transfert de {$valeur} effectué vers {$numeroDest}",
            'frais'   => $totalFrais,
        ];
    }


    private function getOperateurByNumero($numero)
    {
        $numero = (string) $numero;
        $db     = \Config\Database::connect();
        $prefixes = $db->table('prefixe')->get()->getResultArray();

        foreach ($prefixes as $p) {
            $prefixeStr = (string) $p['prefixe'];

            if (substr($numero, 0, strlen($prefixeStr)) === $prefixeStr) {
                return (int) $p['operateur'];
            }
        }

        return null;
    }

   
    private function getCommissionAutreOperateur($operateurId, $valeur)
    {
        $db  = \Config\Database::connect();
        $row = $db->table('frais_autre_operateur')
            ->where('operateur', $operateurId)
            ->get()
            ->getRow();

        if (!$row) {
            return 0;
        }

        return (int) round($valeur * ((float) $row->frais) / 100);
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

    public function prendreFraisRetrait($montant){
        $frais = $this->calculerFrais(2, $montant); // 2 correspond à l'opération de retrait
        return  $frais;
    }

}