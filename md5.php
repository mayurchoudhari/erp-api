<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
// print_r($_POST['hash']);
    if(isset($_POST['hash'])) {
        echo md5($_POST['hash']);
    }
    die();
?>