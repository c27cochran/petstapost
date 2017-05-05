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

if (isset($_POST['item_id']) && isset($_POST['user_id']) && isset($_POST['caption'])) {

	$item_id = $_POST['item_id'];
	$user_id = $_POST['user_id'];
	$caption = strip_tags(trim($_POST['caption']));

  	//get hashtag from message
  	$hashtag = getHashtags($caption);
  	//get mention from message
  	$mention = getMentions($caption);

  	if (!empty($mention) && $mention != '') {
	  	$mentions = explode(', ', $mention);

		foreach ($mentions as $usrn) {
			$uid = $users->fetch_info('user_id', 'username', $usrn);
	        $notifications->insert_mention_comment_notification($uid, $user_id, $item_id, $caption, $usrn);
		}
	}


	if ($users->user_profile_exists($user_id) === true) {

		$items->update_caption($user_id, $item_id, $caption, $hashtag, $mention);

		echo '<i class="fa fa-check">&nbsp;</i>"'.$caption.'"';
		exit();

	} else {
		echo 'You must be logged in.';
		exit();
	}

} else {
	echo 'Oops...something went wrong.';
}