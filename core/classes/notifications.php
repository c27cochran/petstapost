<?php 
class Notifications {

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}

	public function insert_mention_notification($to_user, $user_id, $caption, $mention) {

		$time = time();

		$query = $this->db->prepare("INSERT INTO `notifications` (`user_id`, `mention_user`, `caption`, `mention`, `time`) VALUES (?,?,?,?,?) ");

		$query->bindValue(1, $to_user);
		$query->bindValue(2, $user_id);
		$query->bindValue(3, $caption);
		$query->bindValue(4, $mention);
		$query->bindValue(5, $time);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function insert_mention_comment_notification($to_user, $user_id, $item_id, $comment, $mention) {

		$time = time();

		$query = $this->db->prepare("INSERT INTO `notifications` (`user_id`, `mention_user`, `commented_item`, `comment`, `mention`, `time`) VALUES (?,?,?,?,?,?) ");

		$query->bindValue(1, $to_user);
		$query->bindValue(2, $user_id);
		$query->bindValue(3, $item_id);
		$query->bindValue(4, $comment);
		$query->bindValue(5, $mention);
		$query->bindValue(6, $time);

		try{
			$query->execute();

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_notifications($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`notify_id`) FROM `notifications` WHERE `user_id`= ? and `viewed` = 0");
		
		$query->bindValue(1, $user_id);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			return $rows;

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function remove_notifications($user_id) {
	
		$query = $this->db->prepare("DELETE FROM `notifications` WHERE `user_id` = ? and `viewed` = 1 and `friend_request_user` IS NULL");
		
		$query->bindValue(1, $user_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function delete_notifications($user_id) {
	
		$query = $this->db->prepare("DELETE FROM `notifications` WHERE `user_id` = ? and `friend_request_user` IS NULL");
		
		$query->bindValue(1, $user_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function get_friend_request_notifications($user_id) {

		$query = $this->db->prepare("SELECT `notify_id`, `friend_request_user`, `friend_request_name` FROM `notifications` 
			WHERE `user_id` = ? and `friend_request_user` IS NOT NULL ORDER BY `time` DESC");

		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_friend_accepted_notifications($user_id) {

		$query = $this->db->prepare("SELECT `accepted_user`, `accepted_name` FROM `notifications` 
			WHERE `user_id` = ? and `accepted_user` IS NOT NULL ORDER BY `time` DESC");

		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function count_favorite_notifications($user_id, $item_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`liked_user`) FROM `notifications` WHERE `user_id`= ? and `liked_item` = ?");
		
		$query->bindValue(1, $user_id);
		$query->bindValue(2, $item_id);
	
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

	public function get_favorite_notifications($user_id) {

		$query = $this->db->prepare("SELECT DISTINCT n.liked_item
			FROM notifications n  WHERE n.user_id = ? and n.liked_item is NOT NULL and n.viewed = 0 ORDER BY n.time DESC");

		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_favorite_item_notifications($user_id, $item_id) {

		$query = $this->db->prepare("SELECT n.liked_user, n.time, i.item_id, i.url, i.filter, u.first_name, u.last_name, u.username 
			FROM notifications n JOIN items i on i.item_id = n.liked_item JOIN users u on n.liked_user = u.user_id 
			WHERE n.user_id = ? and n.liked_item = ? ORDER BY n.time DESC");

		$query->bindValue(1, $user_id);
		$query->bindValue(2, $item_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function count_comment_notifications($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`commented_user`) FROM `notifications` WHERE `user_id`= ? and `viewed` = 0");
		
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

	public function get_comment_notifications($user_id) {

		$query = $this->db->prepare("SELECT n.commented_item, n.commented_user, n.comment, n.time, i.url, i.filter FROM notifications n 
			JOIN items i on i.item_id = n.commented_item where n.user_id = ? ORDER BY n.time DESC");

		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_mention_notifications($username) {
		$query = $this->db->prepare("SELECT n.user_id, n.caption, n.time, n.mention_user, u.username, u.first_name, u.last_name, i.url, i.filter, i.item_id FROM notifications n 
			JOIN users u on u.user_id = n.user_id JOIN items i on i.caption = n.caption WHERE n.mention = ? and n.viewed = 0 ORDER BY n.time ASC");
		$query->bindValue(1, $username);

		try {
			$query->execute();
		} catch(PDOException $e) {
			die($e->getMessage());
		}

		return $query->fetchAll();
	}

	public function get_comment_mention_notifications($username) {

		$query = $this->db->prepare("SELECT n.commented_item, n.mention_user, n.user_id, n.comment, n.time, i.url, i.filter FROM notifications n 
			JOIN items i on i.item_id = n.commented_item where n.mention = ? and n.viewed = 0 ORDER BY n.time DESC");

		$query->bindValue(1, $username);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function set_viewed_notifications($user_id) {

		$query = $this->db->prepare("UPDATE `notifications` SET `viewed`=1 WHERE `user_id` = ?");

		$query->bindValue(1, $user_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

	}

}