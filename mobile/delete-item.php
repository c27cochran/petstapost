<?php

require __DIR__ .'/../core/init.php';
require __DIR__.'/../util/aws/aws-autoloader.php';

use Aws\S3\S3Client;

if (isset($_POST['username']) && isset($_POST['delete_item']) && isset($_POST['id'])) {

	$username = $_POST['username']; 
	$item_id = $_POST['delete_item'];
	$cdn_id = $_POST['id'];

    $user_id = $users->fetch_info('user_id', 'username', $username);

	if ($users->user_exists($username) === true) {

		// Delete image from AWS S3
		$item_data = $items->get_one_item($item_id);

		$url = explode('/', $item_data['url']);

		$bucket = 'petstapost';
		$keyname1 = $url[3].'/'.$url[4];

		$s3 = S3Client::factory(array(
			'key' => 'confidential',
			'secret' => 'confidential'
	      ));

		// Delete objects from S3 bucket
		if ($item_data['filter'] == 'video') {
			$video_url = explode('/', $item_data['video_url']);
			$keyname2 = $video_url[3].'/'.$video_url[4];

			$result = $s3->deleteObjects(array(
			    'Bucket'  => $bucket,
			    'Objects' => array(
			        array('Key' => $keyname1),
			        array('Key' => $keyname2)
			    )
			));

		} else {
			$result = $s3->deleteObjects(array(
			    'Bucket'  => $bucket,
			    'Objects' => array(
			        array('Key' => $keyname1),
			    )
			));
		}

		$items->delete_item($item_id, $cdn_id, $user_id);

		// Delete associated comments
		$comments->delete_comments_when_item_deleted($item_id);

		// Delete associated favorites
		$favorites->delete_favorites_when_item_deleted($item_id);

		echo 'Deleted';

	} else {
		echo 'Not';
	}

} else {
	echo 'Error';
}