<?php 
session_start();
require 'connect/database.php';
require 'classes/users.php';
require 'classes/comments.php';
require 'classes/items.php';
require 'classes/favorites.php';
require 'classes/friends.php';
require 'classes/general.php';
require 'classes/notifications.php';
require 'classes/pets.php';
require 'classes/bcrypt.php';
 
$users 		= new Users($db);
$comments 	= new Comments($db);
$items	 	= new Items($db);
$favorites	= new Favorites($db);
$friends	= new Friends($db);
$general 	= new General();
$notifications	= new Notifications($db);
$pets		= new Pets($db);
$bcrypt 	= new Bcrypt(12);

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 21600)) {
    // last request was more than 3 hours ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if(isset($_COOKIE['Petsta'])) {

    $str = $_COOKIE['Petsta'];
    $str = explode('&', $str);
    $usr = str_replace('usr=', '', $str[0]);
    $code = str_replace('code=', '', $str[1]);

    // Make a verification
	if($users->check_cookie($usr, $code) == true) {
        $usr_id        = $users->fetch_info('user_id', 'username', $usr);
	    $_SESSION['id'] = $usr_id;
    }
}
 
if ($general->logged_in() === true)  {
	$user_id 	= $_SESSION['id'];
	$user = $users->userdata($user_id);

	$_SESSION['username'] = $user['username'];
	$full_name = $user['first_name'] . ' ' . $user['last_name'];
	$_SESSION['name'] = $full_name;
} else {
	$_SESSION['username'] = "";
	$_SESSION['name'] = "";
}

$errors = array();

$domain = $_SERVER['SERVER_NAME'];

if ($domain == "localhost") {
    ini_set('display_errors', 1);
	error_reporting(E_ALL);
} elseif ($domain == "petstapost.com") {
	ini_set('display_errors', 0);
	error_reporting(0);
}
 
ob_start();

if (isset($_SESSION['tz'])) {
	date_default_timezone_set($_SESSION['tz']);
} else {
	date_default_timezone_set('America/Chicago');
}

?>