<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GainModel;
class GainController extends BaseController
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
}
