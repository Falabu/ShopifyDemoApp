<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 13:10
 */

namespace View;

class CostumerList
{
    private $data;


    public function __construct($costumerList)
    {
        $this->data = $costumerList;
    }

    public function render()
    {
        echo "<h2>Vásárlók listája</h2>";
        echo "<table class='table table-hover table-dark table-sm'>";
        echo "<tr>
        <th>Vezetéknév 5</th>
        <th>Keresztnév</th>
        <th>E-mail</th> 
        <th>Létrehozás dátuma</th>
        <th>Eddigi költés</th>
        <th>Eddigi rendelések</th>
        <th>Aktív</th>
        <th>E-mail megerősítve</th>
        <th>Reklámanyag</th>
             </tr>";

        for ($i = 0; $i < count($this->data["customers"]); $i++) {
            $date=date_create($this->data["customers"][$i]["created_at"]);
            $newDate = date_format($date,"Y/m/d H:i:s");

            echo "<tr>";
            echo "<td>" . $this->data["customers"][$i]["last_name"] . "</td>";
            echo "<td>" . $this->data["customers"][$i]["first_name"] . "</td>";
            echo "<td><a href='mailto:" . $this->data["customers"][$i]["email"] . "'>" . $this->data["customers"][$i]["email"] ."</a></td>";
            echo "<td>" . $newDate . "</td>";
            echo "<td>" . $this->data["customers"][$i]["total_spent"] . " HUF </td>";
            echo "<td>" . $this->data["customers"][$i]["orders_count"] . "</td>";
            echo "<td>";
            if($this->data["customers"][$i]["state"] == "disabled"){
                echo "<i class='fas fa-times-circle checkno'></i>";
            }
            else{
                echo "<i class='fas fa-check-circle checkyes'></i>";
            }
            echo "</td>";
            echo "<td>";
            if($this->data["customers"][$i]["verified_email"] == false){
                echo "<i class='fas fa-times-circle checkno'></i>";
            }
            else{
                echo "<i class='fas fa-check-circle checkyes'></i>";
            }
            echo "</td>";
            echo "<td>";
            if($this->data["customers"][$i]["accepts_marketing"] == false){
                echo "<i class='fas fa-times-circle checkno'></i>";
            }
            else{
                echo "<i class='fas fa-check-circle checkyes'></i>";
            }
            echo "</td>";
        }
        echo "</table>";

    }
}