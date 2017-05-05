<?php

require __DIR__ .'/../core/init.php';

if (isset($_GET['item_id']) && isset($_GET['user_id']) && isset($_GET['to_user'])) {

	$item_id = $_GET['item_id'];
	$user_id = $_GET['user_id'];
	$to_user = $_GET['to_user'];

	if ($users->user_profile_exists($user_id) === true) {

		if ($favorites->already_favorited($item_id, $user_id) === false) {
			$favorites->add_favorite($item_id, $user_id, $to_user);
			echo 'Favorite added!';
			exit();
		} else {
			$favorites->remove_favorite($item_id, $user_id);
			echo 'Favorite removed.';
			exit();
		}

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}