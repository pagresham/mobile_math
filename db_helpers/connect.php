<?PHP
// $db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if($db->connect_error) {
	die("Unable to establish DB connection: ".$db->connect_error);
}
else {
	// echo "DB Connection Successful";
}
?>