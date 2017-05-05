<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['my_id']) && isset($_POST['their_id'])) {

	$user1_id = $_POST['my_id'];
	$user2_id = $_POST['their_id'];

	if ($users->user_profile_exists($user1_id) === true) {

		if ($friends->check_friends($user1_id, $user2_id) === true) {
			$friends->remove_friend($user1_id, $user2_id);
			echo 'Friend removed';
			exit();
		}

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}