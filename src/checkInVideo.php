<?php
ob_start(); 
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

/***Connect to personal DB***/
$nameset=$_POST["nameid"];
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "galeg-db", $password, "galeg-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!($stmt = $mysqli->prepare("UPDATE videoStore SET rented=0 WHERE name=?"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/***Bind***/
if (!$stmt->bind_param("s", $nameset)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();
header("Location: videoStore.php", true);
?>