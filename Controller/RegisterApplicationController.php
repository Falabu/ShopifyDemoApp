<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 16:52
 */

namespace Controller;


use Model\RegisterApplication;
use Model\AppInfo;


class RegisterApplicationController
{
    private $registerApplication;
    private $AppInfo;
    private $url;


    public function __construct(RegisterApplication $app, AppInfo $appInfo)
    {
        $this->AppInfo = $appInfo;
        $this->registerApplication = $app;
    }

    public function redirectForApproval(){
        $this->url = "https://" . $this->registerApplication->getShopName() . ".myshopify.com/admin/oauth/authorize?client_id=" . $this->AppInfo->getApiKey() . "&scope=" . $this->registerApplication->getScopes() . "&redirect_uri=". MAIN_URL . "index.php&state=". $this->registerApplication->getNonce();

        header("Location: " . $this->url);
        die();

    }

    public function captureAccessCode(){
        if(isset($_GET) && isset($_GET["code"])){
            $this->registerApplication->addAccessCode($_GET["code"]);
        }
    }

    public function exchangeAccessCode(){
        $query = array(
            "client_id" => $this->AppInfo->getApiKey(),
            "client_secret" => $this->AppInfo->getSecretKey(),
            "code" => $this->registerApplication->getAccessCode()
        );

        $access_token_url = "https://" . $this->registerApplication->getShopName() . ".myshopify.com/admin/oauth/access_token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $access_token_url);
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));

        $result = curl_exec($ch);

        echo var_dump($result);

        curl_close($ch);

        $result = json_decode($result, true);
        $access_token = $result['access_token'];

        $this->registerApplication->addAccessToken($access_token);
    }


    function  redirectToShopifyAppPage(){
        $url = "https://" . $this->registerApplication->getShopName() . ".myshopify.com/admin/apps/";
        header('Location:'. $url);
        die();
    }


}