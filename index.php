<?php
require_once "config.php";

use Controller\MainController;

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopify App</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>

<body>
<div class="maincontainer">

    <?php
    $main = new MainController();
    $main->entryPoint();
    ?>

</div>

</body>
</html>




