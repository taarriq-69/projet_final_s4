<?php

namespace App\Controllers;

use App\Models\EpargneClientModel;
use App\Models\EpargneModel;
class EpargneController extends BaseController
{
    protected $epargneModel;


    public function __construct(){
        $this->epargneModel = new EpargneModel();
    }

    public function definir()
    {
        $client_id = session()->get('client_id');

        if(!$client_id)
        {
            return redirect()->to('/');
        }

        if($this->request->getMethod() == 'POST')
        {
            $pourcentage = $this->request->getPost('pourcentage');

            $existing = $this->epargneModel->where('client_id' , $client_id)->first();

            if($existing)
            {
                $this->epargneModel->update($existing['id'],['pourcentage' => $pourcentage,]);
            }
            else
            {
                $this->epargneModel->insert([
                    'client_id' => $client_id,
                    'pourcentage' => $pourcentage,
                ]);
            }
            return redirect()->to('/home')->with('message','pourcentage enregistrer');
        }
        $config = $this->epargneModel->where('client_id',$client_id)->first();
        
        return view('clients/definir_epargne',['config' => $config]);
    }
}
