<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 17:51
 */

namespace Controller;


use Model\AppInfo;
use Model\Authenticate;
use Model\RegisterApplication;
use Model\CreateWebhook;
use Model\CreateCostumer;
use Model\WebhookListener;
use Model\GetCustomer;
use View\CustomerList;


class MainController
{
    private $regAppControl;
    private $appInfo;
    private $registerApplication;

    public function __construct()
    {
        $this->appInfo = new AppInfo();
    }

    public function entryPoint()
    {
        //Figyeljük, hogy jött e webhook kérés
        $listener = new WebhookListener();

        $listener->listen();


        $this->registerApplication = new RegisterApplication($_GET["shop"]);
        $auth = new Authenticate($_GET, $this->appInfo->getSecretKey(), $_GET["hmac"]);

        if ($this->registerApplication->isShopRegistered()) { // Ellenőrzi, hogy már elindult-e a installációs procedúra
            if ($this->registerApplication->getInstallProcess() == "installing") {
                if ($this->registerApplication->checkNonce($_GET['state']) && $auth->getResult()) { // Authentikáció

                    $this->regAppControl = new RegisterApplicationController($this->registerApplication, $this->appInfo);

                    $this->regAppControl->captureAccessCode(); // az accescode kinyerése az URL-ből

                    $this->regAppControl->exchangeAccessCode(); // a accesstoken megszerzése

                    $this->registerApplication->updateInstallProcess(); // minden megvan az installáció befejezve

                    // Az alkalmazáshoz szükséges dolgok telepítése

                    $this->installCredentials($this->registerApplication->getAccessToken(), $this->registerApplication->getShopName());

                    //Telepítés kész vissza a shopify oldalára
                    $this->regAppControl->redirectToShopifyAppPage();
                } else {
                    echo "Request not from shopify";
                }
            } elseif ($this->registerApplication->getInstallProcess() == "installed" && $auth->getResult()) {

                //Az applikáció megnyitásakor ha minden rendben van itt indul a program

                $getCustomer = new GetCustomer($this->registerApplication->getShopName(), $this->registerApplication->getAccessToken());

                $customerData = $getCustomer->getData();

                $customers = new CustomerList($customerData);

                $customers->render();

            }
        } else {
            $this->registerApplication->addShop();
            $this->regAppControl = new RegisterApplicationController($this->registerApplication, $this->appInfo);

            $this->registerApplication->addScopes("read_products,read_orders,read_customers,write_customers"); // Jogok hozzáadása

            $this->regAppControl->redirectForApproval();

        }


    }

    private function installCredentials($accessToken, $shopname){
        $webhook = new CreateWebhook("app/uninstalled");
        $costumer = new CreateCostumer("Dávid", "Kurucz", "david.kurucz@gmail.com");

        $webhook->createWebhook($accessToken, $shopname);
        $costumer->createCostumer($accessToken, $shopname);
    }

}

