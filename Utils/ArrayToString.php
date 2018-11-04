<?php
/**
 * Mivel a http_build_query() függvény nem úgy kódolta a tömböt ahogy azt kéne, nem tudtam rájönni mi a baja.
 * Az authentikációhoz meg szükséges az átalakítás.  Ezért készítettem ezt a kis osztályt erre.
 *
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

    /***
     * Tömb stringé alakítása az értékek ellenőrzésével
     *
     */
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