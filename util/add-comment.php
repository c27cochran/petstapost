<?php

require __DIR__ .'/../core/init.php';

function getHashtags($msg) {

	preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $msg, $matchedHashtags);
	$hashtag = '';

	if(!empty($matchedHashtags[0])) {
		foreach($matchedHashtags[0] as $match) {
			$hashtag .= preg_replace("/[^a-z0-9]+/i", "", $match).', ';
		}
	}
	return rtrim($hashtag, ', ');
}

function getMentions($msg) {

	preg_match_all('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', $msg, $matchedMentions);
	$mention = '';

	if(!empty($matchedMentions[0])) {
		foreach($matchedMentions[0] as $match) {
			$mention .= preg_replace("/[^a-z0-9]+/i", "", $match).', ';
		}
	}
	return rtrim($mention, ', ');
}

if (isset($_POST['item_id']) && isset($_POST['to_user']) && isset($_POST['user_id']) && isset($_POST['comment'])) {

	$item_id = $_POST['item_id'];
	$user_id = $_POST['user_id'];
	$comment = strip_tags(trim($_POST['comment']));
	$to_user = $_POST['to_user'];

  	//get hashtag from message
  	$hashtag = getHashtags($comment);
  	//get mention from message
  	$mention = getMentions($comment);

  	if (!empty($mention) && $mention != '') {
	  	$mentions = explode(', ', $mention);

		foreach ($mentions as $usrn) {
			$uid = $users->fetch_info('user_id', 'username', $usrn);
	        $notifications->insert_mention_comment_notification($uid, $user_id, $item_id, $comment, $usrn);
		}
	}


	if ($users->user_profile_exists($user_id) === true) {

		$comments->post_comment($user_id, $to_user, $item_id, $comment, $hashtag, $mention);

		echo '<i class="fa fa-check">&nbsp;</i>"'.$comment.'"';
		exit();

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}