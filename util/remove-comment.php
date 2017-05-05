<?php

require __DIR__ .'/../core/init.php';

if (isset($_GET['comment_id'])) {

	$comment_id = $_GET['comment_id'];
	$user_id = $_SESSION['id'];

	if ($users->verify($user_id) === true) {

		$comments->remove_comment($comment_id);

		echo '<h5 class="deleted-comment"><i class="fi-comment-minus">&nbsp;</i>Comment Removed</h5>';
		exit();

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}