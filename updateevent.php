<?php
if ($_POST['z'] == "x84bohs5") {
$username = "username";
$password = "password";
$date = $_POST['a'];
$itemno = $_POST['q'];
try {
	$dbh = new PDO('mysql:host=localhost;dbname=events', $username, $password);
	$html = $dbh->quote($_POST['d']);
	$title = $dbh->quote($_POST['b']);
	$image = $dbh->quote($_POST['c']);
	if ($itemno == 0) {
		$sql="INSERT INTO aaphp2015.events (Item, DateFrom, DateTo, Title, ImageFile, HTML) VALUES (NULL, CURDATE(), '$date', $title, $image, $html)";
	}
	else {
		$sql="UPDATE aaphp2015.events SET DateTo = '$date', Title = $title, ImageFile = $image, HTML = $html WHERE Item = '$itemno'";
	}
	$sth = $dbh->prepare($sql);	
	$sth->execute();
	echo $dbh->lastInsertId();
	$dbh = null;
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
} }
?>