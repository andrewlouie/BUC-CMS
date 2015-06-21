<?php
$q = $_REQUEST["q"];
$username = "username";
$password = "password";
try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=events', $username, $password);
    foreach($dbh->query("SELECT * from events WHERE Item = '$q'") as $row) {
        echo $row["Title"];
	echo "||";
	echo $row["DateTo"];
	echo "||";
	echo $row["ImageFile"];
	echo "||";
	echo $row["HTML"];
    }
    $dbh = null;
} catch (PDOException $e) {
    die();
}
?>

