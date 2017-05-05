<?php 
class Items {

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}


	public function post_item($user_id, $cdn_id, $name, $mime, $url, $ssl_url, $width, $height, $date_recorded, 
		$date_file_created, $date_file_modified, $aspect_ratio, $city, $state, $country, $device_name, $latitude, $longitude, 
		$orientation, $colorspace, $average_color, $filter, $caption, $hashtag, $mention) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `items` (`user_id`, `cdn_id`, `name`, `mime`, `url`, `ssl_url`, `width`, `height`,
			`date_recorded`, `date_file_created`, `date_file_modified`, `aspect_ratio`, `city`, `state`, `country`, `device_name`, 
			`latitude`, `longitude`, `orientation`, `colorspace`, `average_color`, `time`, `filter`, `caption`, `hashtag`, `mention`) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");

		$query->bindValue(1, $user_id);
		$query->bindValue(2, $cdn_id);
		$query->bindValue(3, $name);
		$query->bindValue(4, $mime);
		$query->bindValue(5, $url);
		$query->bindValue(6, $ssl_url);
		$query->bindValue(7, $width);
		$query->bindValue(8, $height);
		$query->bindValue(9, $date_recorded);
		$query->bindValue(10, $date_file_created);
		$query->bindValue(11, $date_file_modified);
		$query->bindValue(12, $aspect_ratio);
		$query->bindValue(13, $city);
		$query->bindValue(14, $state);
		$query->bindValue(15, $country);
		$query->bindValue(16, $device_name);
		$query->bindValue(17, $latitude);
		$query->bindValue(18, $longitude);
		$query->bindValue(19, $orientation);
		$query->bindValue(20, $colorspace);
		$query->bindValue(21, $average_color);
		$query->bindValue(22, $time);
		$query->bindValue(23, $filter);
		$query->bindValue(24, $caption);
		$query->bindValue(25, $hashtag);
		$query->bindValue(26, $mention);

		try{
			$query->execute();

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function post_item_video($user_id, $p_cdn_id, $p_name, $p_mime, $p_url, $p_ssl_url, $p_width, $p_height, $p_date_recorded, 
        $p_date_file_created, $p_date_file_modified, $p_aspect_ratio, $p_city, $p_state, $p_country, $p_device_name, $p_latitude, $p_longitude, 
        $p_orientation, $p_colorspace, $p_average_color, $p_filter, $p_caption, $hashtag, $mention, $v_url, $v_ssl_url, $v_name, $v_mime, $v_width , $v_height, 
        $v_duration, $v_framerate, $v_video_bitrate, $v_video_codec, $v_audio_codec, $v_date_file_created) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `items` (`user_id`, `cdn_id`, `name`, `mime`, `url`, `ssl_url`, `width`, `height`,
			`date_recorded`, `date_file_created`, `date_file_modified`, `aspect_ratio`, `city`, `state`, `country`, `device_name`, 
			`latitude`, `longitude`, `orientation`, `colorspace`, `average_color`, `time`, `filter`, `caption`, `hashtag`, `mention`, 
			`video_url`, `video_ssl_url`, `video_name`, `video_mime`, `video_width`, `video_height`, `video_duration`, `video_framerate`, `video_bitrate`, 
			`video_codec`, `audio_codec`, `video_date_file_created`) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");

		$query->bindValue(1, $user_id);
		$query->bindValue(2, $p_cdn_id);
		$query->bindValue(3, $p_name);
		$query->bindValue(4, $p_mime);
		$query->bindValue(5, $p_url);
		$query->bindValue(6, $p_ssl_url);
		$query->bindValue(7, $p_width);
		$query->bindValue(8, $p_height);
		$query->bindValue(9, $p_date_recorded);
		$query->bindValue(10, $p_date_file_created);
		$query->bindValue(11, $p_date_file_modified);
		$query->bindValue(12, $p_aspect_ratio);
		$query->bindValue(13, $p_city);
		$query->bindValue(14, $p_state);
		$query->bindValue(15, $p_country);
		$query->bindValue(16, $p_device_name);
		$query->bindValue(17, $p_latitude);
		$query->bindValue(18, $p_longitude);
		$query->bindValue(19, $p_orientation);
		$query->bindValue(20, $p_colorspace);
		$query->bindValue(21, $p_average_color);
		$query->bindValue(22, $time);
		$query->bindValue(23, $p_filter);
		$query->bindValue(24, $p_caption);
		$query->bindValue(25, $hashtag);
		$query->bindValue(26, $mention);
		$query->bindValue(27, $v_url);
		$query->bindValue(28, $v_ssl_url);
		$query->bindValue(29, $v_name);
		$query->bindValue(30, $v_mime);
		$query->bindValue(31, $v_width);
		$query->bindValue(32, $v_height);
		$query->bindValue(33, $v_duration);
		$query->bindValue(34, $v_framerate);
		$query->bindValue(35, $v_video_bitrate);
		$query->bindValue(36, $v_video_codec);
		$query->bindValue(37, $v_audio_codec);
		$query->bindValue(38, $v_date_file_created);

		try{
			$query->execute();

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_items($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`item_id`) FROM `items` WHERE `user_id`= ?");
		$query->bindValue(1, $user_id);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows > 0){
				return $rows;
			}else{
				return '';
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function get_items($user_id, $position, $items_per_group) {

		$query = $this->db->prepare("SELECT * FROM `items` WHERE `user_id`= ? ORDER BY `item_id` DESC LIMIT $position, $items_per_group");
		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_item_group_count($user_id) {
		$query = $this->db->prepare("SELECT COUNT(DISTINCT item_id) as count FROM items WHERE user_id = ?");
		$query->bindValue(1, $user_id);

		try{

			$query->execute();
			$total_records = $query->fetchColumn();

		} catch (PDOException $e){
			die($e->getMessage());
		}

		$items_per_group = 5;
		$total_groups = ceil($total_records/$items_per_group);
		return $total_groups;
	}

	public function get_one_item($item_id) {

		$query = $this->db->prepare("SELECT * FROM `items` WHERE `item_id`= ?");
		$query->bindValue(1, $item_id);

		try{
			$query->execute();
			return $query->fetch();

		} catch(PDOException $e){

			die($e->getMessage());
		}

	}

	public function delete_item($item_id, $cdn_id, $user_id) {

		// TO DO: Delete from S3
		
		$query = $this->db->prepare("DELETE FROM items WHERE item_id = ? and cdn_id = ? and user_id = ?");

		$query->bindValue(1, $item_id);
		$query->bindValue(2, $cdn_id);
		$query->bindValue(3, $user_id);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function fetch_info($what, $field, $value) {

		$allowed = array('user_id', 'item_id', 'name', 'url', 'caption');
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
		    throw new InvalidArgumentException;
		}else{
		
			$query = $this->db->prepare("SELECT $what FROM `items` WHERE $field = ?");

			$query->bindValue(1, $value);

			try{

				$query->execute();
				
			} catch(PDOException $e){

				die($e->getMessage());
			}

			return $query->fetchColumn();
		}
	}

	public function item_exists($item_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`item_id`) FROM `items` WHERE `item_id`= ?");
		$query->bindValue(1, $item_id);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function get_kibble($user1_id, $position, $items_per_group) {

		$query = $this->db->prepare("SELECT DISTINCT i.user_id, i.item_id, i.filter, i.url, i.name, i.caption, i.time, u.username, u.first_name, u.last_name, u.profile_picture, u.profile_picture_filter FROM items i 
			JOIN friends f on f.user2_id = i.user_id JOIN users u on u.user_id = f.user2_id WHERE f.user1_id = ? and f.confirmed = 1 OR (u.user_id = ?)
			ORDER BY i.time DESC LIMIT $position, $items_per_group");
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user1_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_kibble_group_count($user1_id) {
		$query = $this->db->prepare("SELECT COUNT(DISTINCT i.item_id) as count FROM items i 
			JOIN friends f on f.user2_id = i.user_id JOIN users u on u.user_id = f.user2_id WHERE f.user1_id = ? and f.confirmed = 1 OR (u.user_id = ?)");
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user1_id);

		try{

			$query->execute();
			$total_records = $query->fetchColumn();

		} catch (PDOException $e){
			die($e->getMessage());
		}

		$items_per_group = 8;
		$total_groups = ceil($total_records/$items_per_group);
		return $total_groups;
	}

	public function get_item_hashtag($hashtag) {
		$query = $this->db->prepare("SELECT i.user_id, i.item_id, i.filter, i.url, i.name, i.caption, i.time, u.username, u.first_name, 
			u.last_name, u.profile_picture, u.profile_picture_filter, u.secured FROM items i 
			JOIN users u on u.user_id = i.user_id WHERE i.hashtag LIKE ? ORDER BY i.time DESC");
		$query->bindValue(1, $hashtag);

		try {
			$query->execute();
		} catch(PDOException $e) {
			die($e->getMessage());
		}

		return $query->fetchAll();
	}

	public function update_caption($user_id, $item_id, $caption, $hashtag, $mention) {
		$query = $this->db->prepare("UPDATE `items` SET `caption` = ?, `hashtag` = ?, `mention` = ? WHERE `user_id` = ? and `item_id` = ?");
		$query->bindValue(1, $caption);
		$query->bindValue(2, $hashtag);
		$query->bindValue(3, $mention);
		$query->bindValue(4, $user_id);
		$query->bindValue(5, $item_id);

		try {
			$query->execute();
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}

}