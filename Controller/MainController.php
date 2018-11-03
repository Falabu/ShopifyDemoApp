<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 17:51
 */

namespace Controller;


use Model\AppInfo;
use Model\RegisterApplication;
use Model\ShopifyRequest;
use Model\CreateWebhook;
use Model\CreateCostumer;
use Model\WebhookListener;
use View\CostumerList;


class MainController
{
    private $regAppControll;
    private $appInfo;
    private $registerApplication;

    public function __construct()
    {
        $this->appInfo = new AppInfo();
    }

    public function entryPoint()
    {
        //Ha a webhook köld egy requestet végrehajtjuk a belépési pont legelején majd kilépünk az programból
        $listener = new WebhookListener();

        $listener->listen();

        if ($listener->checkIfRequestHappend()) {
            if ($listener->getTopic() == "app/uninstalled") {
                $this->registerApplication = new RegisterApplication($listener->getName());
                $this->registerApplication->uninstallApplication();
                die();
            }
        }

        $this->registerApplication = new RegisterApplication($_GET["shop"]);


        if ($this->registerApplication->isShopRegistered()) { // Ellenőrzi, hogy már elindult-e a installációs procedura
            if ($this->registerApplication->getInstallProcess() == "installing") {
                if ($this->registerApplication->checkNonce($_GET['state'])) { // Nonce ellenőrzése
                    file_put_contents("log.txt", $this->registerApplication->checkNonce($_GET['state']));

                    $this->regAppControll = new RegisterApplicationController($this->registerApplication, $this->appInfo);

                    $this->regAppControll->captureAccessCode(); // az accescode kinyerése az URL-ből

                    $this->regAppControll->exchangeAccessCode(); // a accesstoken megszerzése

                    $this->registerApplication->updateInstallProcess(); // minden megvan az installáció befejezve

                    // Az alkalmazáshoz szükséges dolgok telepítése

                    $webhook = new CreateWebhook("app/uninstalled");
                    $costumer = new CreateCostumer("Dávid", "Kurucz", "david.kurucz@gmail.com");

                    $webhook->createWebhook($this->registerApplication->getAccessToken(), $this->registerApplication->getShopName());
                    $costumer->createCostumer($this->registerApplication->getAccessToken(), $this->registerApplication->getShopName());

                    //Telepítés kész vissza a shopify oldalára
                    $this->regAppControll->redirectToShopifyAppPage();
                } else {
                    echo "Request not from shopify";
                }
            } elseif ($this->registerApplication->getInstallProcess() == "installed") {
                //Az applikáció megnyitásakor ha minden rednben van itt indul a program
                $data = array();

                $curl = new ShopifyRequest($this->registerApplication->getShopName(), $this->registerApplication->getAccessToken(), $data, "/admin/customers.json", "GET");

                $call = $curl->shopify_call();

                $products = json_decode($call['response'], TRUE);

                $customers = new CostumerList($products);

                $customers->render();

            }
        } else {
            $this->registerApplication->addShop();
            $this->regAppControll = new RegisterApplicationController($this->registerApplication, $this->appInfo);

            $this->registerApplication->addScopes("read_products,read_orders,read_customers,write_customers"); // Jogok hozzáadása

            $this->regAppControll->redirectForApproval();

        }


    }

}

