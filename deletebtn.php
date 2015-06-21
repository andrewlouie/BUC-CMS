<?php
if ($_REQUEST["z"] == "x84bohs5") {
$username = "username";
$password = "password";
$q = $_REQUEST["q"];
try {
	$dbh = new PDO('mysql:host=127.0.0.1;dbname=events', $username, $password);
	$sql = "DELETE FROM events WHERE Item = " . $q;
	$sth = $dbh->prepare($sql);	
	$sth->execute();
	$dbh = null;
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
} }
?>