<?php
$username = "username";
$password = "password";
try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=events', $username, $password);
    foreach($dbh->query('SELECT Item, Title from events ORDER BY DateTo DESC') as $row) {
	echo $row["Item"];
	echo "||";
	echo $row["Title"];
	echo "||";
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
