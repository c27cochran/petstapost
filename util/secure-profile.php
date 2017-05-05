<?php

require __DIR__ .'/../core/init.php';

if (isset($_SESSION['id'])) {

	$user_id = $_SESSION['id'];

	if ($users->user_profile_exists($user_id) === true) {

		$users->secure_profile($user_id);
		echo '<span class="menu-item"><i class="fa fa-fw fa-lock"></i>&nbsp;Profile Secured</span>';
		exit();

	} else {
		// must be logged in
		exit();
	}

} else {
	// something went wrong
}