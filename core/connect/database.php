<?php 
# We are storing the information in this config array that will be required to connect to the database.

$domain = $_SERVER['SERVER_NAME'];

if ($domain == "localhost" || $domain == "petstapost.dev") {
	//stg
	$config = array(
		'host'		=> 'localhost',
		'username'	=> 'root',
		'password'	=> 'root',
		'dbname' 	=> 'petstapost'
	);
}

if ($domain == "petstapost.com") {
	//stg
	$config = array(
		'host'		=> 'localhost',
		'username'	=> '[confidential-username]',
		'password'	=> '[confidential-password]',
		'dbname' 	=> 'petstapost'
	);
}


#connecting to the database by supplying required parameters
$db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
 
#Setting the error mode of our db object, which is very important for debugging.
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>