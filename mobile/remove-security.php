<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['user'])) {

	$user_id = $_POST['user'];

	if ($users->user_profile_exists($user_id) === true) {

		$users->remove_security($user_id);
		echo '<span class="menu-item"><i class="fa fa-fw fa fa-unlock"></i>&nbsp;Profile is Open</span>';
		exit();

	} else {
		// must be logged in
		exit();
	}

} else {
	// something went wrong
}