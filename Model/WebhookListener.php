<?php
/**
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

    function listen()
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

    }

    function checkIfRequestHappend(){
        if(isset($this->name) && isset($this->topic)){
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }


}