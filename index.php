<?php
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0,2);
    $path = $_GET["p"];
    if(!isset($path)) $path = "";

    if($lang == "de") {
        header("Location: ./de/$path");
    } else if($lang == "fr") {
        header("Location: ./fr/$path");
    } else {
        // Default language
        header("Location: ./en/$path");
    }
    exit;
?>