<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['my_id']) && isset($_POST['their_id']) && isset($_POST['my_name']) && isset($_POST['their_name'])) {

	$user1_id = $_POST['my_id'];
	$user2_id = $_POST['their_id'];
	$user1_name = $_POST['my_name'];
	$user2_name = $_POST['their_name'];

	if ($users->user_profile_exists($user1_id) === true) {

		if ($friends->check_friends($user1_id, $user2_id) === false) {
			$friends->add_friend($user1_id, $user2_id, $user1_name, $user2_name);
			echo 'Friend request sent!';
			exit();
		} else {
			$friends->remove_friend_request($user1_id, $user2_id);
			echo 'Friend request is off the table.';
			exit();
		}

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}