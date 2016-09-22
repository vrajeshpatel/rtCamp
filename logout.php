<?php
session_start();

session_unset();
$_SESSION['facebook_access_token'] = NULL;
$_SESSION = array();
session_destroy();
header('location:http://vrajesh.xyz/index.php');
?>