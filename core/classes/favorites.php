<?php 
class Favorites {

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}

	public function add_favorite($item_id, $user_id, $to_user) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `favorites` (`item_id`, `user_id`, `time`) VALUES (?,?,?) ");

		$query->bindValue(1, $item_id);
		$query->bindValue(2, $user_id);
		$query->bindValue(3, $time);

		$query_2 = $this->db->prepare("INSERT INTO `notifications` (`liked_item`, `liked_user`, `user_id`, `time`) VALUES (?,?,?,?) ");

		$query_2->bindValue(1, $item_id);
		$query_2->bindValue(2, $user_id);
		$query_2->bindValue(3, $to_user);
		$query_2->bindValue(4, $time);

		try{
			$query->execute();
			$query_2->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_favorites($item_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`favorite_id`) FROM `favorites` WHERE `item_id`= ?");
		
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

	public function already_favorited($item_id, $user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`favorite_id`) FROM `favorites` WHERE `item_id`= ? and `user_id` = ?");
		
		$query->bindValue(1, $item_id);
		$query->bindValue(2, $user_id);
	
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

	public function remove_favorite($item_id, $user_id) {
	
		$query = $this->db->prepare("DELETE FROM `favorites` WHERE `item_id`= ? and `user_id` = ?");
		
		$query->bindValue(1, $item_id);
		$query->bindValue(2, $user_id);

		$query_2 = $this->db->prepare("DELETE FROM `notifications` WHERE `liked_item`= ? and `liked_user` = ?");
		
		$query_2->bindValue(1, $item_id);
		$query_2->bindValue(2, $user_id);
	
		try{
			$query->execute();
			$query_2->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function delete_favorites_when_item_deleted($item_id) {
	
		$query = $this->db->prepare("DELETE FROM `favorites` WHERE `item_id`= ?");
		
		$query->bindValue(1, $item_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function get_favorites($item_id) {

		$query = $this->db->prepare("SELECT u.first_name, u.last_name, u.username, f.time FROM users u JOIN favorites f on u.user_id = f.user_id 
			where f.item_id =  ? ORDER BY f.time DESC");

		$query->bindValue(1, $item_id);

		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

}