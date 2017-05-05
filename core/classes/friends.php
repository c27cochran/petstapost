<?php 
class Friends{

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}


	public function add_friend($user1_id, $user2_id, $user1_name, $user2_name) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `friends` (`user1_id`, `user2_id`, `user1_name`, `user2_name`, `time`) VALUES (?,?,?,?,?) ");

		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);
		$query->bindValue(3, $user1_name);
		$query->bindValue(4, $user2_name);
		$query->bindValue(5, $time);

		$query_2 = $this->db->prepare("INSERT INTO `notifications` (`user_id`, `friend_request_user`, `friend_request_name`, `time`) VALUES (?,?,?,?) ");

		$query_2->bindValue(1, $user2_id);
		$query_2->bindValue(2, $user1_id);
		$query_2->bindValue(3, $user1_name);
		$query_2->bindValue(4, $time);

		try{
			$query->execute();
			$query_2->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function accept_friend($user1_id, $user2_id, $user1_name, $user2_name) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `friends` (`user1_id`, `user2_id`, `user1_name`, `user2_name`, `confirmed`, `time`) VALUES (?,?,?,?,1,?) ");

		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);
		$query->bindValue(3, $user1_name);
		$query->bindValue(4, $user2_name);
		$query->bindValue(5, $time);

		$query_2 = $this->db->prepare("UPDATE `friends` SET `confirmed`=1 WHERE `user1_id` = ? and `user2_id` = ?");

		$query_2->bindValue(1, $user2_id);
		$query_2->bindValue(2, $user1_id);

		$query_3 = $this->db->prepare("INSERT INTO `notifications` (`user_id`, `accepted_user`, `accepted_name`, `time`) VALUES (?,?,?,?) ");

		$query_3->bindValue(1, $user2_id);
		$query_3->bindValue(2, $user1_id);
		$query_3->bindValue(3, $user1_name);
		$query_3->bindValue(4, $time);

		$query_4 = $this->db->prepare("DELETE FROM `notifications` WHERE `user_id` = ? and `friend_request_user` = ?");
		
		$query_4->bindValue(1, $user1_id);
		$query_4->bindValue(2, $user2_id);

		try{
			$query->execute();
			$query_2->execute();
			$query_3->execute();
			$query_4->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_friends($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`friendship_id`) FROM `friends` WHERE `user1_id`= ? and `confirmed` = 1");
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

	public function get_friends($user_id) {
	
		$query = $this->db->prepare("SELECT * FROM `friends` WHERE `user1_id`= ? and `confirmed` = 1 ORDER BY `user2_name` ASC");
		$query->bindValue(1, $user_id);
	
		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	// Check if user has accepted friend request
	public function check_friends($user1_id, $user2_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`friendship_id`) FROM `friends` WHERE `user1_id`= ? and `user2_id` = ? and `confirmed` = 1");
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);

		$query_2 = $this->db->prepare("SELECT COUNT(`friendship_id`) FROM `friends` WHERE `user1_id`= ? and `user2_id` = ? and `confirmed` = 1");
		$query_2->bindValue(1, $user2_id);
		$query_2->bindValue(2, $user1_id);
	
		try{

			$query->execute();
			$query_2->execute();
			$q1_rows = $query->fetchColumn();
			$q2_rows = $query_2->fetchColumn();

			$rows = ($q1_rows + $q2_rows);

			if($rows == 2){
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function check_friend_request($user1_id, $user2_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`friendship_id`) FROM `friends` WHERE `user1_id`= ? and user2_id = ?");
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);
	
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

	public function has_requested_friend($user1_id, $user2_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`friendship_id`) FROM `friends` WHERE `user2_id`= ? and user1_id = ?");
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);
	
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

	public function remove_friend($user1_id, $user2_id) {
	
		$query = $this->db->prepare("DELETE FROM `friends` WHERE `user1_id` = ? and `user2_id` = ?");
		
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);

		$query_2 = $this->db->prepare("DELETE FROM `friends` WHERE `user2_id` = ? and `user1_id` = ?");
		
		$query_2->bindValue(1, $user1_id);
		$query_2->bindValue(2, $user2_id);

		$query_3 = $this->db->prepare("DELETE FROM `notifications` WHERE `user_id` = ? and `accepted_user` = ?");
		
		$query_3->bindValue(1, $user2_id);
		$query_3->bindValue(2, $user1_id);
	
		try{
			$query->execute();
			$query_2->execute();
			$query_3->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function remove_friend_request($user1_id, $user2_id) {
	
		$query = $this->db->prepare("DELETE FROM `friends` WHERE `user1_id` = ? and `user2_id` = ?");
		
		$query->bindValue(1, $user1_id);
		$query->bindValue(2, $user2_id);

		$query_2 = $this->db->prepare("DELETE FROM `notifications` WHERE `friend_request_user` = ? and `user_id` = ?");
		
		$query_2->bindValue(1, $user1_id);
		$query_2->bindValue(2, $user2_id);
	
		try{
			$query->execute();
			$query_2->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function you_may_know($user1_id) {

		$query = $this->db->prepare("SELECT u.user_id FROM users u JOIN friends f on f.user1_id = u.user_id WHERE f.user2_id = ? 
			and f.confirmed = 1 ORDER BY RAND() LIMIT 1");
		$query->bindValue(1, $user1_id);
	
		try{

			$query->execute();
			$new_user_id = $query->fetchColumn();

		} catch (PDOException $e){
			die($e->getMessage());
		}

		// User ID 34 is Petstapost user
		$query2 = $this->db->prepare("SELECT u.username, u.first_name, u.last_name, u.profile_picture, u.profile_picture_filter FROM users u 
			JOIN friends f on f.user1_id = u.user_id WHERE u.user_id NOT IN (SELECT user2_id FROM friends WHERE user1_id = ? and confirmed = 1) 
			and f.user2_id = ? and f.user1_id <> ? and f.user1_id <> 34 ORDER BY RAND() LIMIT 3");
		$query2->bindValue(1, $user1_id);
		$query2->bindValue(2, $new_user_id);
		$query2->bindValue(3, $user1_id);

		try{
			$query2->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query2->fetchAll();

	}

}
