<?php

require __DIR__ .'/../core/init.php';
require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;

if (isset($_GET['username']) && isset($_GET['delete_item']) && isset($_GET['id'])) {

	$username = $_GET['username']; 
	$item_id = $_GET['delete_item'];
	$cdn_id = $_GET['id'];

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

		if ($domain == "localhost") {
			header('Location: /petstapost/deleted.php');
			exit();
		} elseif ($domain == "petstapost.com") {
			header('Location: /deleted.php');
			exit();
		}


	} else {
		if ($domain == "localhost") {
			header('Location: /petstapost/notdeleted.php');
			exit();
		} elseif ($domain == "petstapost.com") {
			header('Location: /notdeleted.php');
			exit();
		}
	}

} else {
	if ($domain == "localhost") {
		header('Location: /petstapost/notdeleted.php');
		exit();
	} elseif ($domain == "petstapost.com") {
		header('Location: /notdeleted.php');
		exit();
	}
}