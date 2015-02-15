<?php
ob_start(); 
include 'password.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$_SESSION['sort'] = $_POST['sort'];
header("Location: videoStore.php", true);

?>