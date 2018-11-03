<?php
/**
 * Létrehoz egy webhookit APIn keresztül
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 9:30
 */

namespace Model;

class CreateWebhook
{
    private $webhookName;
    private $data;
    private $request;
    private $endPoint;

    /***
     * CreateWebhook constructor.
     * @param $webhookName string Webhook name
     */
    public function __construct($webhookName)
    {
        $this->webhookName = $webhookName;
        $this->endPoint = "/admin/webhooks.json";

    }

    /***
     *
     * Létrehozza a webhookot
     *
     * @param $accessToken string Accesstoken
     * @param $shopName string Shopname
     *
     */
    public function createWebhook($accessToken, $shopName)
    {
        $this->data = array(
            'webhook' => array(
                'topic'     => $this->webhookName,
                'address'   => "https://349beb59.ngrok.io/index.php",
                'format'    => "json"
            )
        );
        $this->request = new ShopifyRequest($shopName, $accessToken, $this->data, $this->endPoint, "POST");
        $this->request->shopify_call();
    }
}