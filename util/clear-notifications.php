<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['user_id'])) {

	$user_id = $_POST['user_id'];

	if ($users->user_profile_exists($user_id) === true) {

		$notifications->delete_notifications($user_id);

		exit();

	} else {
		// must be logged in
		exit();
	}

} else {
	// something went wrong
}