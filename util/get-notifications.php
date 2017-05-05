<?php

require __DIR__ .'/../core/init.php';

if (isset($_SESSION['id'])) {

	$user_id = $_SESSION['id'];

	if ($users->user_profile_exists($user_id) === true) {

		$no_notifications = $notifications->count_notifications($user_id);
		echo $no_notifications;
		exit();

	} else {
		// must be logged in
		exit();
	}

} else {
	// something went wrong
}