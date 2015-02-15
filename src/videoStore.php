<?php
/***Need ouput buffering for all php files when communicating to DB***/
ob_start(); 
include 'password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
/***Sunday 2/15/15 -There is a bug I can't figure out. Individual videos won't delete when selecting just 1 ***/



/***Connect to personal DB***/
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "galeg-db", $password, "galeg-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Video Store - Gale</title>
	</head>
<body>

<?php

/***Print input forms ***/
echo "
<form action='addVideo.php' method='post'>
	<fieldset>
		<legend>Add Movie - Data Input</legend>
		<p>name: <input type='text' name='moviename' /></p>
		<p>category: <input type='text' name='category' /></p>
		<p>length: <input type='number' name='length' min='1' max='500' /></p>	
		<br><br>
		<input type='submit' value='Submit'>
	<fieldset>
</form> <br><br>
";

/***Query DB data***/
if (!isset($_SESSION['sort'])||($_SESSION['sort']=="All")) {
	if (!$stmt = $mysqli->query("SELECT name, category, length, rented FROM videoStore")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
}
else {
	$sorted = $_SESSION['sort'];
	if (!$stmt = $mysqli->query("SELECT name, category, length, rented FROM videoStore WHERE category='$sorted'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
	echo "Only showing $sorted films";
	$_SESSION['sort']="All";
}
?>

<table border="2">
<thead> 
<tr>
    <th>Title</th> 
    <th>Category</th> 
    <th>Length</th> 
    <th>Rented</th> 
    <th>Change Status</th> 
    <th>Delete</th>
</tr> 
</thead>
<tbody>

<?php
/***Display queried data***/
$video = array();
while($row = mysqli_fetch_array($stmt))	{
	echo "<tr>" ;
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>" . $row['category'] . "</td>";
	if ($row['length']==0) {
		echo "<td> </td>";
	}
	else {
		echo "<td>" . $row['length'] . "</td>";
	}

	echo "<td>";

	if (!$row['rented']) {
		echo "avalible </td>
		<td><form method=\"POST\" action=\"checkOutVideo.php\">
		<input type=\"hidden\" name=\"nameid\" value=\"".$row['name']."\">
		<input type=\"submit\" value=\"checkout\">
		</form> </td>";
	}
	else {
		echo "checked out </td>
		<td><form method=\"POST\" action=\"checkInVideo.php\">
		<input type=\"hidden\" name=\"nameid\" value=\"".$row['name']."\">
		<input type=\"submit\" value=\"returned\">
		</form> </td>";
	}

	echo "<td><form method=\"POST\" action=\"deleteAllVideos.php\">
	<input type=\"hidden\" name=\"nameid\" value=\"".$row['name']."\">
	<input type=\"submit\" value=\"delete\">
	</form> </td>
	</tr>";
}

/***Display categories***/
if (!$stmt = $mysqli->query("SELECT category FROM videoStore")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	{
	if ((!(in_array($row['category'], $video)))&&($row['category']!=null)) {
		array_push($video,$row['category']);
	}
}
	
?>


</tbody>
</table>
<form action="filterVideos.php" method="POST">
	<div align="center">
		<select name="sort">
			<option value="All">All Movies</option>
			<?php
			$j = count($video);
			for ($i = 0; $i < $j; $i++) {
				echo "<option value=$video[$i]>$video[$i]</option>";
			}
			?>
		</select>
	</div>
	<input type="submit" value="Filter">
</form>
<br>
<form method="POST" action="deleteAllVideos.php">
	<input type="hidden" name="deletekey" value="xjy">
	<input type="submit" value="delete all">
</form>
</body>
</html>	