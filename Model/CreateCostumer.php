<?php
/**
 *
 * Létrehoz egy új vásárlót, csak név és emaillel
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 9:40
 */

namespace Model;


class CreateCostumer
{
    private $firstName;
    private $lastName;
    private $email;
    private $request;
    private $endPoint;
    private $data;

    /**
     * CreateCostumer constructor.
     *
     * @param $firstName string Firstname
     * @param $lastName string Lastname
     * @param $email string Email address
     */
    public function __construct($firstName, $lastName, $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->endPoint = "/admin/customers.json";

    }

    /***
     * Létrehozza a vásárlót egy API hívással
     *
     * @param $accessToken string Accesstoken
     * @param $shopName string Shopname
     */
    public function createCostumer($accessToken, $shopName)
    {
        $this->data = array(
            'customer' => array(
                'first_name'     => $this->firstName,
                'last_name'   => $this->lastName,
                'email'    => $this->email
            )
        );

        $this->request = new ShopifyRequest($shopName, $accessToken, $this->data, $this->endPoint, "POST");
        $this->request->shopify_call();
    }
}