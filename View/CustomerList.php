<?php
/**
 * Created by PhpStorm.
 * User: DaweTheDrummer
 * Date: 2018. 11. 03.
 * Time: 13:10
 */

namespace View;

class CustomerList
{
    private $data;


    public function __construct($costumerList)
    {
        $this->data = $costumerList;
    }

    public function render()
    {
        echo "<h1> Vásárlók listája</h1>";
        for ($i = 0; $i < count($this->data["customers"]); $i++) {
            $date=date_create($this->data["customers"][$i]["created_at"]);
            $newDate = date_format($date,"Y/m/d H:i:s");
            echo "
        <div class='card'>
        <div class='cardContainer'>
            <img class='userImage' src='../images/image.jpg'>
            <div class='userInfo'>";
            echo "<h3>" . $this->data["customers"][$i]["last_name"] . " " . $this->data["customers"][$i]["first_name"] . "</h3>";
            echo "<p><b>Email:</b><a href='mailto:" . $this->data["customers"][$i]["email"] . "'> " . $this->data["customers"][$i]["email"] . "</a> <br>";
            echo "<b>Telefon:</b> " . $this->data["customers"][$i]["phone"] . "<br>";
            echo "<b>Cím:</b> ";
            if (isset($this->data["customers"][$i]["default_address"])) {
                echo $this->data["customers"][$i]["default_address"]["country_name"] . ", " .
                    $this->data["customers"][$i]["default_address"]["zip"] . ", " .
                    $this->data["customers"][$i]["default_address"]["address1"];
            } else {
                echo "Nincs cím megadva!";
            }
            echo "</p>";
            echo "</div>";
            echo "<div class='icons'>";
            echo "<i class='fas fa-envelope icon ";
            if ($this->data["customers"][$i]["verified_email"] = true) {
                echo "yes";
            } else {
                echo "no";
            }
            echo " '><span class='tooltiptext'>";
            if ($this->data["customers"][$i]["verified_email"] = true) {
                echo "Email verified";
            } else {
                echo "Email not verified";
            }
            echo "</span></i>";
            echo "<i class='fas fa-times-circle icon ";
            if ($this->data["customers"][$i]["state"] = "disabled") {
                echo "no";
            } else {
                echo "yes";
            }
            echo " '><span class='tooltiptext'>";
            if ($this->data["customers"][$i]["state"] = "disabled") {
                echo "Account disabled";
            } else {
                echo "Account active";
            }
            echo "</span></i>";
            echo "<i class='fas fa-arrow-circle-down downButton_" . $i . " downIcon'></i>";
            echo "</div>";
            echo "<div class='clearfix'></div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='cardExtend_" . $i . " extendedInfo'>";
            echo "<table class='table table-sm table-dark'>";
            echo "<tr>";
            echo "<th>Eddigi költés</th>";
            echo "<th>Eddigi vásárlás</th>";
            echo "<th>Marketing</th>";
            echo "<th>Regisztáció dátuma</th>";
            echo "<th>Pénznem</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>" . $this->data["customers"][$i]["total_spent"] . " HUF</td>";
            echo "<td>" . $this->data["customers"][$i]["orders_count"] . "</td>";
            echo "<td>" . $this->data["customers"][$i]["accepts_marketing"] . "</td>";
            echo "<td>" . $newDate . "</td>";
            echo "<td>" . $this->data["customers"][$i]["currency"] . "</td>";
            echo "</tr>";
            echo "</table>";
            echo "</div>";
        }

    }
}