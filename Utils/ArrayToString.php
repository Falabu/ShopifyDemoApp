<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 8:20
 */

namespace Utils;


class ArrayToString
{
    private $data;
    private $string;


    public function __construct($array)
    {
        $this->data = $array;
        $this->toString();
    }

    private function toString()
    {
        $separator = '';

        foreach ($this->data as $key => $value) {
            $this->string .= $separator . $key . "=" . trim(strip_tags(htmlspecialchars($value)));
            $separator = "&";
        }

    }

    public function __toString():string
    {
        return $this->string;
    }
}