<?php
ob_start(); 
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

/***Connect to personal DB***/
$error=0;
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "galeg-db", $password, "galeg-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (($_POST['moviename']==null)) {
	echo "Video MUST have a name! <a href=\"videoStore.php\">Click here</a>";
}
else {
	/***inserting, updating, and deleting are all similar SQL commands. The PHP proccess 
	also shares these similarities across adding, checking IN/OUT, and Deleting .php files:
	1) Match parameters for the SLQ command with POST data from the user
	2) Bind the SQL statement and prepare for execution to database
	3) Execute
	***/
	$name=$_POST['moviename'];
	$category=$_POST['category'];
	$length=$_POST['length'];
	if (!($stmt = $mysqli->prepare("INSERT INTO videoStore(name, category, length) VALUES (?,?,?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}

	/***Bind***/
	if (!$stmt->bind_param("ssi", $name, $category, $length)) {
		echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}

	if (!$stmt->execute()) {
		$error=1;
	}

	$stmt->close();
	if ($error==0) {
		header("Location: videoStore.php", true);
	}
	else {
		echo "Name already exists - it must be unique! <a href=\"videoStore.php\"> Click here</a>";
	}
}

?>