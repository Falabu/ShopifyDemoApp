<?php

require "Model\AppInfo.php";
require "Utils\DbConnect.php";
require "Model\RegisterApplication.php";
require "Model\Authenticate.php";
require "Model\ShopifyRequest.php";
require "Model\CreateWebhook.php";
require "Model\CreateCostumer.php";
require "Model\WebhookListener.php";
require "Controller\MainController.php";
require "Controller\RegisterApplicationController.php";
require "View\CostumerList.php";



define("DB_NAME", "shopify");
define("DB_UNAME", "root");
define("DB_PASSWORD", "1986berciKe");
define("DB_HOST", "localhost");
define("MAIN_URL","https://349beb59.ngrok.io/");