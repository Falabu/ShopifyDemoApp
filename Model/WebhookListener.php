<?php
/**
 * Az Shopify álltal küldött webhook kéréseket regisztrálja és annak megfelelően elindítja a webhookhoz tartozó algoritmust
 *
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 12:16
 */

namespace Model;


class WebhookListener
{
    private $topic;
    private $name;
    private $headers;

    /***
     * A webhook fejlécének beállítása
     *
     *
     */
    public function listen()
    {
        $this->headers = apache_request_headers();

        foreach ($this->headers as $header => $value) {
            if ($header == "X-Shopify-Topic") {
                $this->topic = $value;
            }
            if ($header == "X-Shopify-Shop-Domain") {
                $this->name = $value;
            }
        }

        $this->runWebHook();

    }

    /***
     * Ellenőrzi hogy a fejléc beállításra került, ha nem nem történt webooh kérés
     *
     * @return bool
     *
     */
    private function checkIfRequestHappend(){
        if(isset($this->name) && isset($this->topic)){
            return true;
        }
        return false;
    }

    /***
     * Elindítja a webhookhoz tartozó algoritmust
     *
     */
    private function runWebHook(){
        if ($this->checkIfRequestHappend()) {
            if ($this->topic == "app/uninstalled") {
                $registerApplication = new RegisterApplication($this->name);
                $registerApplication->uninstallApplication();
                die();
            }
        }

    }
}