<?php 

$domain = $_SERVER['SERVER_NAME'];

if ($domain == "localhost") {
	// Stg
	DEFINE ('DB_USER', 'root');
	DEFINE ('DB_PASSWORD', 'root');
	DEFINE ('DB_HOST', 'localhost');
	DEFINE ('DB_NAME', 'petstapost');
}

if ($domain == "petstapost.com") {
	// Stg
	DEFINE ('DB_USER', '[confidential-username]');
	DEFINE ('DB_PASSWORD', '[confidential-password]');
	DEFINE ('DB_HOST', 'localhost');
	DEFINE ('DB_NAME', 'petstapost');
}

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$dbc) {
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
}

?>
