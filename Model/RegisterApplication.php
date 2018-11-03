<?php
/***
 * Az applikáció installációjához szükséges adatbázisműveletek végrehajtó osztály.
 *
 *
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 16:09
 */

namespace Model;

use Utils\dbConnect;

class RegisterApplication
{
    private $shopName;
    private $nonce;
    private $accessCode;
    private $accessToken;
    private $scopes;
    private $dbConnect;

    public function __construct($shopname, $accesscode = "", $accesstoken = "")
    {
        $this->dbConnect = dbConnect::dbCon();
        $this->shopName = strstr($shopname, '.', true);
        $this->accessCode = $accesscode;
        $this->accessToken = $accesstoken;

    }

    /**
     * Ellenőrzi, hogy a bolt már van e regisztrálva az adatbázisban.
     *
     * @return bool
     *
     *
     */
    public function isShopRegistered(): bool
    {
        $sql = $this->dbConnect->prepare("SELECT * FROM shopinfo WHERE shopname = ?");
        $sql->execute([$this->shopName]);

        $result = $sql->fetch();

        if ($result) {
            return true;
        }

        return false;
    }

    /***
     * Az bolt nevének feltöltése az adatbázisba
     * Beállítja installációs állapotot /az installáció folyamatban van/ - installing
     *
     */
    public function addShop()
    {
        $this->generateNonce();

        $sql = $this->dbConnect->prepare("INSERT INTO shopinfo(shopName, nonce, process) VALUES (?,?,?)");
        $sql->execute([$this->shopName, $this->nonce, "installing"]);
    }

    /***
     * Visszaadja az installáció állapotát
     *
     * @return string Az installáció állapota
     */
    public function getInstallProcess(): string
    {
        $sql = $this->dbConnect->prepare("SELECT process FROM shopinfo WHERE shopname = ? ");
        $sql->execute([$this->shopName]);

        $result = $sql->fetch();

        return $result["process"];
    }

    /***
     * Beállítja az installációs állapotot véglegesre
     * Az installáció befejeződött - installed
     *
     */
    public function updateInstallProcess()
    {
        $sql = $this->dbConnect->prepare("UPDATE shopinfo SET process = 'installed' WHERE shopname = ?");
        $sql->execute([$this->shopName]);
    }

    /***
     * Generál egy random stringet későbbi ellenőrzésre
     *
     */
    private function generateNonce()
    {
        $key = date("Y.m.d H:i:s");

        $this->nonce = md5($key . $this->shopName);
    }

    /**
     * Ellenőrzi az adatbázisban és a paraméternek adott értéket a bolthoz.
     *
     * @param $nonce string Paraméter az ellenőrzéshez
     * @return bool
     *
     *
     */
    public function checkNonce($nonce): bool
    {
        $sql = $this->dbConnect->prepare("SELECT id FROM shopinfo WHERE nonce = ? AND shopname = ? LIMIT 1");
        $sql->execute([$nonce, $this->shopName]);

        $result = $sql->fetch();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Legkérdezi az adabázisból az autentikációhoz szükséges értéket
     *
     * @return string Nonce
     *
     */
    public function getNonce(): string
    {
        $sql = $this->dbConnect->prepare("SELECT nonce FROM shopinfo WHERE shopname = ? LIMIT 1");
        $sql->execute([$this->shopName]);

        $result = $sql->fetch();

        return $result["nonce"];
    }

    /***
     * Feltölti az adatbázisba a bolthoz tartozó accesstokent
     *
     * @param $accessToken string AccessToken
     *
     */
    public function addAccessToken($accessToken)
    {
        $sql = $this->dbConnect->prepare("UPDATE shopinfo SET accestoken = ? WHERE shopname = ?");
        $sql->execute([$accessToken, $this->shopName]);
    }

    /***
     * Lekérdezi az adatbázisból a bolthoz tartzozó accesstokent
     *
     * @return string AccessToken
     *
     *
     */
    public function getAccessToken(): string
    {
        $sql = $this->dbConnect->prepare("SELECT accestoken FROM shopinfo WHERE shopname = ? LIMIT 1");
        $sql->execute([$this->shopName]);

        $result = $sql->fetch();

        return $result["accestoken"];
    }

    public function uninstallApplication(){
        $sql = $this->dbConnect->prepare("DELETE FROM shopinfo WHERE shopname = ?");
        $sql->execute([$this->shopName]);
    }
    /**
     * Bolt jogosultságainak beállítása
     *
     * @param $scopes string Scopes
     *
     */
    public function addScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getShopName()
    {
        return $this->shopName;
    }

    public function addAccessCode($access)
    {
        $this->accessCode = $access;
    }

    public function getAccessCode(): string
    {
        return $this->accessCode;
    }
}
