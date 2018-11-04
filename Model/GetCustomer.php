<?php
/**
 * Lekéri a Shopify-tól az összes vásárlót
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 04.
 * Time: 9:50
 */

namespace Model;


class GetCustomer
{
    private $data;
    private $shopName;
    private $accessToken;

    /***
     * GetCustomer constructor.
     * @param $shopName string Shopname
     * @param $accessToken string Accesstoken
     */
    public function __construct($shopName, $accessToken)
    {
        $this->shopName = $shopName;
        $this->accessToken = $accessToken;
    }

    public function getData(){
        $this->getAllCustomers();
        return $this->data;
    }

    private function getAllCustomers(){
        $curl = new ShopifyRequest($this->shopName, $this->accessToken, array(), "/admin/customers.json", "GET");

        $call = $curl->shopify_call();

        $this->data = json_decode($call['response'], TRUE);
    }
}