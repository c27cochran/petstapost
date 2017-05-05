<?php 
class Pets{

	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}


	public function add_pet($user_id, $pet_name, $past_present, $type, $breed) {

		$time = time();

		$query 	= $this->db->prepare("INSERT INTO `pets` (`user_id`, `pet_name`, `past_present`, `type`, `breed`, `time`) 
			VALUES (?,?,?,?,?,?)");

		$query->bindValue(1, $user_id);
		$query->bindValue(2, $pet_name);
		$query->bindValue(3, $past_present);
		$query->bindValue(4, $type);
		$query->bindValue(5, $breed);
		$query->bindValue(6, $time);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function update_pet_photo($profile_picture, $filter_class, $pet_name, $user_id) {

		$query = $this->db->prepare("UPDATE `pets` SET `pet_avatar_url` = ?, `pet_filter` = ? WHERE `pet_name`  = ? and `user_id` = ?");

		$query->bindValue(1, $profile_picture);
		$query->bindValue(2, $filter_class);
		$query->bindValue(3, $pet_name);
		$query->bindValue(4, $user_id);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function count_pets($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`pet_id`) FROM `pets` WHERE `user_id`= ?");
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

	public function count_current_pets($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`pet_id`) FROM `pets` WHERE `user_id`= ? and `past_present` = 1");
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

	public function count_past_pets($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`pet_id`) FROM `pets` WHERE `user_id`= ? and `past_present` = 0");
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

	public function get_current_pets($user_id) {
	
		$query = $this->db->prepare("SELECT * FROM `pets` WHERE `user_id`= ? and `past_present` = 1 ORDER BY `pet_name` ASC");
		$query->bindValue(1, $user_id);
	
		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function get_past_pets($user_id) {
	
		$query = $this->db->prepare("SELECT * FROM `pets` WHERE `user_id`= ? and `past_present` = 0 ORDER BY `pet_name` ASC");
		$query->bindValue(1, $user_id);
	
		try{
			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function remove_pet($user_id, $pet_id) {
	
		$query = $this->db->prepare("DELETE FROM `pets` WHERE `user_id` = ? and `pet_id` = ?");
		
		$query->bindValue(1, $user_id);
		$query->bindValue(2, $pet_id);
	
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}


}
