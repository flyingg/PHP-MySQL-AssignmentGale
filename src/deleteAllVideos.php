<?php
ob_start(); 
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

/***Connect to personal DB***/
$nameset=1;
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "galeg-db", $password, "galeg-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!($stmt = $mysqli->prepare("DELETE FROM videoStore WHERE ?"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/***Bind***/
if (!$stmt->bind_param("i", $nameset)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();
$_SESSION["sort"]="All";
header("Location: videoStore.php", true);
?>