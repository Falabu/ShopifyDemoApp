<?php
/**
 * A shopify API-val való kommunikációhoz szükséges osztály.
 *
 *
 *
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 02.
 * Time: 16:21
 */

namespace Model;

class ShopifyRequest
{
    private $curl;
    private $shopName;
    private $endPoint;
    private $method;
    private $url;
    private $data;
    private $accessToken;

    /***
     * ShopifyRequest constructor.
     *
     * @param $shopname string Shopname
     * @param $accessToken string AccesToken
     * @param $data array Data to send
     * @param $apiEndPoint string ApiEndpoint
     * @param $method string method
     */
    public function __construct($shopname, $accessToken, $data, $apiEndPoint, $method)
    {
        $this->shopName = $shopname;
        $this->accessToken = $accessToken;
        $this->data = $data;
        $this->endPoint = $apiEndPoint;
        $this->method = $method;

        $this->url =  "https://" . $this->shopName . ".myshopify.com" . $this->endPoint;
        if($this->method == "GET" && $this->method == "PUT"){
            $this->url = $this->url . "?" . http_build_query($this->data);
        }
    }

    /***
     * Futtatja a Shopify APi hívást
     *
     * @param array $request_headers
     * @return array|string Data
     *
     */

    function shopify_call($request_headers = array()) {
        $this->curl = curl_init($this->url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);

        $request_headers[] = "";
        if (!is_null($this->accessToken)) $request_headers[] = "X-Shopify-Access-Token: " . $this->accessToken;
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $request_headers);
        if ($this->method != 'GET' && in_array($this->method, array('POST', 'PUT'))) {
            if (is_array($this->data)) $this->data = http_build_query($this->data);
            curl_setopt ($this->curl, CURLOPT_POSTFIELDS, $this->data);
        }


        $response = curl_exec($this->curl);
        $error_number = curl_errno($this->curl);
        $error_message = curl_error($this->curl);

        curl_close($this->curl);

        if ($error_number) {
            return $error_message;
        } else {

            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

            $headers = array();
            $header_data = explode("\n",$response[0]);
            $headers['status'] = $header_data[0];
            array_shift($header_data);
            foreach($header_data as $part) {
                $h = explode(":", $part);
                $headers[trim($h[0])] = trim($h[1]);
            }

            return array('headers' => $headers, 'response' => $response[1]);
        }

    }

}