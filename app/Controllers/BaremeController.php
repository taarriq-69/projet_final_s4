<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\TypeOperationModel;

class BaremeController extends BaseController
{
    protected $baremeModel;
    protected $typeOperationModel;

    public function __construct()
    {
        $this->baremeModel       = new BaremeModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $baremes = $db->table('bareme b')
            ->select('b.id, b.valeur_min, b.valeur_max, b.frais, b.type_operation_id, o.libelle AS type_operation')
            ->join('type_operation o', 'o.id = b.type_operation_id')
            ->orderBy('o.libelle', 'ASC')
            ->orderBy('b.valeur_min', 'ASC')
            ->get()
            ->getResult();

        return view('operateur/baremes/index', [
            'baremes' => $baremes,
        ]);
    }

    public function ajouter()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'valeur_min'        => $this->request->getPost('valeur_min'),
                'valeur_max'        => $this->request->getPost('valeur_max'),
                'frais'             => $this->request->getPost('frais'),
                'type_operation_id' => $this->request->getPost('type_operation_id'),
            ];

            if (!$this->baremeModel->save($data)) {
                return redirect()->back()->withInput()
                    ->with('error', implode(' ', $this->baremeModel->errors()));
            }

            return redirect()->to('/operateur/baremes')->with('message', 'Barème ajouté avec succès.');
        }

        return view('operateur/baremes/form', [
            'bareme'         => null,
            'typesOperation' => $this->typeOperationModel->findAll(),
        ]);
    }

    public function modifier($id)
    {
        $bareme = $this->baremeModel->find($id);

        if (!$bareme) {
            return redirect()->to('/operateur/baremes')->with('error', 'Barème introuvable.');
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'id'                => $id,
                'valeur_min'        => $this->request->getPost('valeur_min'),
                'valeur_max'        => $this->request->getPost('valeur_max'),
                'frais'             => $this->request->getPost('frais'),
                'type_operation_id' => $this->request->getPost('type_operation_id'),
            ];

            if (!$this->baremeModel->save($data)) {
                return redirect()->back()->withInput()
                    ->with('error', implode(' ', $this->baremeModel->errors()));
            }

            return redirect()->to('/operateur/baremes')->with('message', 'Barème modifié avec succès.');
        }

        return view('operateur/baremes/form', [
            'bareme'         => $bareme,
            'typesOperation' => $this->typeOperationModel->findAll(),
        ]);
    }

    public function supprimer($id)
    {
        if (!$this->baremeModel->find($id)) {
            return redirect()->to('/operateur/baremes')->with('error', 'Barème introuvable.');
        }

        $this->baremeModel->delete($id);

        return redirect()->to('/operateur/baremes')->with('message', 'Barème supprimé avec succès.');
    }
}
