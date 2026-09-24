<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$con = mysqli_connect('localhost', 'root', '', 'pkl'); 

if (!$con) {
    die('Connect Error: ' . mysqli_connect_error());
}

function base_url($url = null) {
    $base_url = "http://localhost/latihan";
    if ($url != null) {
        return $base_url . "/" . $url;
    } else {
        return $base_url;
    }
}
?>