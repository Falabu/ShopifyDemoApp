<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 16:09
 */

namespace Model;

use Utils\ArrayToString;

class Authenticate
{
    private $hmac;
    private $key;
    private $data;
    private $generatedHmac;
    private $query;


    public function __construct($query,$sharedSecret,$hmac)
    {
        $this->hmac = $hmac;
        $this->query = $query;
        $this->key = $sharedSecret;
        $this->extractHmac();
    }

    public function echoAll(){
        $arrayToString = new ArrayToString($this->data);
       echo  $this->generatedHmac = hash_hmac('sha256', $arrayToString, $this->key) . "<br>";
       echo  $this->hmac;
    }

    public function getResult():bool {
        $this->Validate();

        if(hash_equals($this->hmac,$this->generatedHmac)){
            return true;
        }

        return false;
    }

    private function Validate(){
        $arrayToString = new ArrayToString($this->data);

        $this->generatedHmac = hash_hmac('sha256', $arrayToString, $this->key);
    }

    private function extractHmac(){
        $this->data = array_diff_key($this->query, array('hmac' => ''));
        ksort($this->data);

    }

}