<?php
/***
 * Osztály az applikáció apikulcs és titkoskulcs eléréséhez ami az adatbázisban van.
 *
 *
 */
namespace Model;

use Utils\dbConnect;
use PDO;

class AppInfo
{
  private $apiKey;
  private $secretKey;
  private $dbConnect;


  public function __construct()
  {
      $this->dbConnect = dbConnect::dbCon();

      $this->getKeys();
  }

  private function getKeys(){
      $sql = $this->dbConnect->prepare("SELECT * FROM appinfo");
      $sql->execute();

      $result = $sql->fetch(PDO::FETCH_ASSOC);

      $this->apiKey = $result["apikey"];
      $this->secretKey = $result["secretkey"];

  }

  public function getSecretKey():string {
      return $this->secretKey;
  }

  public function getApiKey():string {
      return $this->apiKey;
  }



}