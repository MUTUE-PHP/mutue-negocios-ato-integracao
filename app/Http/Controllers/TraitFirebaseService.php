<?php

namespace App\Http\Controllers;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

trait TraitFirebaseService
{
    private $firebase;
    private $db;

    public function __construct()
    {

    }
    public function saveFirebase($pathReference = null){

        $this->firebase = (new Factory)->withServiceAccount(base_path().'/keyFirebase/key_firebase.json');
        $this->db = $this->firebase->createDatabase();
        $reference = $this->db->getReference('teste');
        $data = [
            'nome' => 'John Doe',
            'idade' => 30,
            'email' => 'john@example.com',
        ];
        $reference->push($data);
    }
}
