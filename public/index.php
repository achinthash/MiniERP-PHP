<?php

$basePath = '/practical/MiniERP/public';
$url = str_replace($basePath,'', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$page = "views/$url.php";

    if(file_exists($page)){
        include $page;
    } else{
        echo "Page not found.";
    }

?> 


