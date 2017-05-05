<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['username'])) {

	$username = $_POST['username'];

	$userdata   = array();
    $my_user_id  = $users->fetch_info('user_id', 'username', $username);
    $userdata   = $users->userdata($my_user_id);

	if ($users->user_profile_exists($my_user_id) === true) {

		$no_notifications = $notifications->count_notifications($my_user_id);
		echo $no_notifications;
		exit();

	} else {
		// must be logged in
		exit();
	}

} else {
	// something went wrong
}