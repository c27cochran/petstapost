<?php 
class Comments{

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}


	public function post_comment($user_id, $to_user, $item_id, $comment, $hashtag, $mention) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `comments` (`user_id`, `item_id`, `comment`, `hashtag`, `mention`, `time`) 
			VALUES (?,?,?,?,?,?)");

		$query->bindValue(1, $user_id);
		$query->bindValue(2, $item_id);
		$query->bindValue(3, $comment);
		$query->bindValue(4, $hashtag);
		$query->bindValue(5, $mention);
		$query->bindValue(6, $time);

		$query_2 = $this->db->prepare("INSERT INTO `notifications` (`user_id`, `commented_user`, `commented_item`, `comment`, `time`) VALUES (?,?,?,?,?) ");

		$query_2->bindValue(1, $to_user);
		$query_2->bindValue(2, $user_id);
		$query_2->bindValue(3, $item_id);
		$query_2->bindValue(4, $comment);
		$query_2->bindValue(5, $time);

		try{
			$query->execute();
			$query_2->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_comments($item_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`comment_id`) FROM `comments` WHERE `item_id`= ?");
		$query->bindValue(1, $item_id);
	
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

	public function get_comments($item_id) {
	
		$query = $this->db->prepare("SELECT * FROM `comments` WHERE `item_id`= ? ORDER BY `time` ASC");
		$query->bindValue(1, $item_id);
	
		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_last_three_comments($item_id) {
	
		$query = $this->db->prepare("SELECT * FROM `comments` WHERE `item_id`= ? ORDER BY `time` ASC LIMIT 3 ");
		$query->bindValue(1, $item_id);
	
		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function already_commented($comment_id, $user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`comment_id`) FROM `comments` WHERE `comment_id`= ? and `user_id` = ?");
		
		$query->bindValue(1, $comment_id);
		$query->bindValue(2, $user_id);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows >= 1){
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function remove_comment($comment_id) {
	
		$query = $this->db->prepare("DELETE FROM `comments` WHERE `comment_id`= ?");
		
		$query->bindValue(1, $comment_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function delete_comments_when_item_deleted($item_id) {
	
		$query = $this->db->prepare("DELETE FROM `comments` WHERE `item_id`= ?");
		
		$query->bindValue(1, $item_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function get_item_comment_hashtag($hashtag) {
		$query = $this->db->prepare("SELECT c.user_id, i.item_id, i.filter, i.url, i.name, i.caption, i.time, c.comment, u.username, 
			u.first_name, u.last_name, u.profile_picture, u.profile_picture_filter, u.secured, u.user_id as poster_id FROM comments c JOIN items i on i.item_id = c.item_id 
			JOIN users u on u.user_id = i.user_id WHERE c.hashtag LIKE ? ORDER BY i.time DESC");
		$query->bindValue(1, $hashtag);

		try {
			$query->execute();
		} catch(PDOException $e) {
			die($e->getMessage());
		}

		return $query->fetchAll();
	}

}
