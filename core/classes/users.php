<?php 

require_once 'mail/PHPMailerAutoload.php';
require_once 'RegisterEmail.php';
require_once 'RecoverEmail.php';

class Users{
 	
	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}	

	public function update_cover_photo($cover_picture, $filter_class, $cover_color, $id) {

		$query = $this->db->prepare("UPDATE `users` SET `cover_picture` = ?, `cover_picture_filter` = ?, `cover_color` = ? WHERE `user_id`  = ?");

		$query->bindValue(1, $cover_picture);
		$query->bindValue(2, $filter_class);
		$query->bindValue(3, $cover_color);
		$query->bindValue(4, $id);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function update_profile_photo($profile_picture, $filter_class, $id) {

		$query = $this->db->prepare("UPDATE `users` SET `profile_picture` = ?, `profile_picture_filter` = ? WHERE `user_id`  = ?");

		$query->bindValue(1, $profile_picture);
		$query->bindValue(2, $filter_class);
		$query->bindValue(3, $id);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function get_cover_photo($username) {

		$query = $this->db->prepare("SELECT `cover_picture` FROM `users` WHERE `username`  = ?");

		$query->bindValue(1, $username);
		
		try{
			$query->execute();
			$photo_url = $query->fetchColumn();

			return $photo_url;

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function get_profile_photo($username) {

		$query = $this->db->prepare("SELECT `profile_picture` FROM `users` WHERE `username`  = ?");

		$query->bindValue(1, $username);
		
		try{
			$query->execute();
			$photo_url = $query->fetchColumn();

			return $photo_url;

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function change_password($user_id, $password) {

		global $bcrypt;

		/* Two create a Hash you do */
		$password_hash = $bcrypt->genHash($password);

		$query = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `user_id` = ?");

		$query->bindValue(1, $password_hash);
		$query->bindValue(2, $user_id);				

		try{
			$query->execute();
			return true;
		} catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function recover($email, $generated_string) {

		if($generated_string == 0){
			return false;
		}else{
	
			$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = ? AND `generated_string` = ?");

			$query->bindValue(1, $email);
			$query->bindValue(2, $generated_string);

			try{

				$query->execute();
				$rows = $query->fetchColumn();

				if($rows == 1){
					
					global $bcrypt;

					$first_name = $this->fetch_info('first_name', 'email', $email);
					$last_name = $this->fetch_info('last_name', 'email', $email);
					$user_id  = $this->fetch_info('user_id', 'email', $email);// We want to keep things standard and use the user's id for most of the operations. Therefore, we use id instead of email.
			
					$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
					$generated_password = substr(str_shuffle($charset),0, 10);

					$this->change_password($user_id, $generated_password);

					$query = $this->db->prepare("UPDATE `users` SET `generated_string` = 0 WHERE `user_id` = ?");

					$query->bindValue(1, $user_id);
	
					$query->execute();

					// Code for actually sending the emails

					$sendEmail = new RecoverEmail();

				    $full_name = $first_name . ' ' . $last_name;

					$html_message = $sendEmail->sendRecoverEmail($generated_password);

					$mail = new PHPMailer();
					$mail->IsSendmail();
					$mail->setFrom('donotreply@petstapost.com', 'Petstapost');
					$mail->addReplyTo('donotreply@petstapost.com', 'Petstapost');
					$mail->addAddress($email, $full_name);
					$mail->Subject = 'Your Petstapost Password';
					$mail->Body = $html_message;
					$mail->IsHTML(true);
					$mail->AltBody = 'Your Petstapost Password';

					//send the message, check for errors
					if (!$mail->send()) {
						echo '<div class="alert alert-danger" role="alert">';
					    echo '<p><i class="fa fa-exclamation-circle"></i>&nbsp;There was an Error.';
					    echo '<br><br>Please try again';
					    echo '</p></div>';
					    $to      = 'support@petstapost.com';
						$subject = 'Petstapost Error';
						$message = "Change password Email. <br><br> Username: " . $username . "\r\n\r\nError: " . $mail->ErrorInfo;
						$headers = 'From: Petstapost' . "\r\n" .
			   			'Reply-To: donotreply@petstapost.com' . "\r\n" .
			   			'X-Mailer: PHP/' . phpversion();
						mail($to, $subject, $message, $headers);
					}

				}else{
					return false;
				}

			} catch(PDOException $e){
				die($e->getMessage());
			}
		}
	}

    public function fetch_info($what, $field, $value){

		$allowed = array('user_id', 'username', 'first_name', 'last_name', 'secured', 'email'); // I have only added few, but you can add more. However do not add 'password' eventhough the parameters will only be given by you and not the user, in our system.
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
		    throw new InvalidArgumentException;
		}else{
		
			$query = $this->db->prepare("SELECT $what FROM `users` WHERE $field = ?");

			$query->bindValue(1, $value);

			try{

				$query->execute();
				
			} catch(PDOException $e){

				die($e->getMessage());
			}

			return $query->fetchColumn();
		}
	}

	public function confirm_recover($email){
	
		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = ?");

		$query->bindValue(1, $email);

		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				
				global $bcrypt;

				// We want to keep things standard and use the user's id for most of the operations. Therefore, we use id instead of email.
				$user_id  = $this->fetch_info('user_id', 'email', $email);
				$first_name  = $this->fetch_info('first_name', 'email', $email);
				$last_name = $this->fetch_info('last_name', 'email', $email);

				$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				$generated_password = substr(str_shuffle($charset),0, 10);

				$this->change_password($user_id, $generated_password);

				// Code for actually sending the emails

				$sendEmail = new RecoverEmail();

			    $full_name = $first_name . ' ' . $last_name;

				$html_message = $sendEmail->sendRecoverEmail($generated_password);

				$mail = new PHPMailer();
				$mail->IsSendmail();
				$mail->setFrom('donotreply@petstapost.com', 'Petstapost');
				$mail->addReplyTo('donotreply@petstapost.com', 'Petstapost');
				$mail->addAddress($email, $full_name);
				$mail->Subject = 'Your Petstapost Password';
				$mail->Body = $html_message;
				$mail->IsHTML(true);
				$mail->AltBody = 'Your Petstapost Password';

				//send the message, check for errors
				if (!$mail->send()) {
					echo '<div class="alert alert-danger" role="alert">';
				    echo '<p><i class="fa fa-exclamation-circle"></i>&nbsp;There was an Error.';
				    echo '<br><br>Please try again';
				    echo '</p></div>';
				    $to      = 'support@petstapost.com';
					$subject = 'Petstapost Error';
					$message = "Change password Email. <br><br> Username: " . $username . "\r\n\r\nError: " . $mail->ErrorInfo;
					$headers = 'From: Petstapost' . "\r\n" .
		   			'Reply-To: donotreply@petstapost.com' . "\r\n" .
		   			'X-Mailer: PHP/' . phpversion();
					mail($to, $subject, $message, $headers);
				}

			}else{
				return false;
			}

		} catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function user_exists($username) {
	
		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `username`= ?");
		$query->bindValue(1, $username);
	
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

	public function user_profile_exists($user_id) {
	
		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id`= ?");
		$query->bindValue(1, $user_id);
	
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
	 
	public function email_exists($email) {

		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `email`= ?");
		$query->bindValue(1, $email);
	
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

	public function register($first_name, $last_name, $password, $email, $username) {

		global $bcrypt; // making the $bcrypt variable global so we can use here

		$time 		= time();
		$ip 		= $_SERVER['REMOTE_ADDR']; // getting the users IP address
		$email_code = $email_code = uniqid('code_',true); // Creating a unique string.
		
		$password   = $bcrypt->genHash($password);

		$query 	= $this->db->prepare("INSERT INTO `users` (`first_name`, `last_name`, `password`, `email`, `ip`, `time`, `email_code`, `username`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");

		$query->bindValue(1, $first_name);
		$query->bindValue(2, $last_name);
		$query->bindValue(3, $password);
		$query->bindValue(4, $email);
		$query->bindValue(5, $ip);
		$query->bindValue(6, $time);
		$query->bindValue(7, $email_code);
		$query->bindValue(8, $username);

		try{
			$query->execute();

			// Code for actually sending the emails

			$sendEmail = new RegisterEmail();

		    $full_name = $first_name . ' ' . $last_name;

			$html_message = $sendEmail->sendRegisterEmail($username, $email_code);

			$mail = new PHPMailer();
			$mail->IsSendmail();
			$mail->setFrom('donotreply@petstapost.com', 'Petstapost');
			$mail->addReplyTo('donotreply@petstapost.com', 'Petstapost');
			$mail->addAddress($email, $full_name);
			$mail->Subject = 'Activate your Petstapost account!';
			$mail->Body = $html_message;
			$mail->IsHTML(true);
			$mail->AltBody = 'Activate your Petstapost account!';

			//send the message, check for errors
			if (!$mail->send()) {
				echo '<div class="alert alert-danger" role="alert">';
			    echo '<p><i class="fa fa-exclamation-circle"></i>&nbsp;There was an Error.';
			    echo '<br><br>Please try again';
			    echo '</p></div>';
			    $to      = 'support@petstapost.com';
				$subject = 'Petstapost Error';
				$message = "Username: " . $username . "\r\n\r\nError: " . $mail->ErrorInfo;
				$headers = 'From: Petstapost' . "\r\n" .
	   			'Reply-To: donotreply@petstapost.com' . "\r\n" .
	   			'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
			}

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function resend_confirmation($first_name, $email, $username) {

		$query = $this->db->prepare("SELECT `email_code` FROM `users` WHERE `username` = ?");

			$query->bindValue(1, $username);

			try{

				$query->execute();
				
			} catch(PDOException $e){

				die($e->getMessage());
			}

			$email_code = $query->fetchColumn();

		try{
			$query->execute();

			// Code for actually sending the emails

			$sendEmail = new RegisterEmail();

		    $full_name = $first_name . ' ' . $last_name;

			$html_message = $sendEmail->sendRegisterEmail($username, $email_code);

			$mail = new PHPMailer();
			$mail->IsSendmail();
			$mail->setFrom('donotreply@petstapost.com', 'Petstapost');
			$mail->addReplyTo('donotreply@petstapost.com', 'Petstapost');
			$mail->addAddress($email, $full_name);
			$mail->Subject = 'Activate your Petstapost account!';
			$mail->Body = $html_message;
			$mail->IsHTML(true);
			$mail->AltBody = 'Activate your Petstapost account!';

			//send the message, check for errors
			if (!$mail->send()) {
				echo '<div class="alert alert-danger" role="alert">';
			    echo '<p><i class="fa fa-exclamation-circle"></i>&nbsp;There was an Error.';
			    echo '<br><br>Please try again';
			    echo '</p></div>';
			    $to      = 'support@petstapost.com';
				$subject = 'Petstapost Error';
				$message = "Username: " . $username . "\r\n\r\nError: " . $mail->ErrorInfo;
				$headers = 'From: Petstapost' . "\r\n" .
	   			'Reply-To: donotreply@petstapost.com' . "\r\n" .
	   			'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
			}

			if (!empty($email_code)) {
				return true;
			} else {
				return false;
			}
			
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function activate($username, $email_code) {
		
		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = ? AND `email_code` = ? AND `confirmed` = ?");

		$query->bindValue(1, $username);
		$query->bindValue(2, $email_code);
		$query->bindValue(3, 0);

		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				
				$query_2 = $this->db->prepare("UPDATE `users` SET `confirmed` = ? WHERE `username` = ?");
				$query_2->bindValue(1, 1);
				$query_2->bindValue(2, $username);
				$query_2->execute();

				$query_3 = $this->db->prepare("SELECT `user_id` FROM `users` WHERE `username` = ?");
				$query_3->bindValue(1, $username);
				$query_3->execute();
				$user2_id = $query_3->fetchColumn();

				$query_4 = $this->db->prepare("SELECT `first_name` FROM `users` WHERE `username` = ?");
				$query_4->bindValue(1, $username);
				$query_4->execute();
				$first_name = $query_4->fetchColumn();

				$query_5 = $this->db->prepare("SELECT `last_name` FROM `users` WHERE `username` = ?");
				$query_5->bindValue(1, $username);
				$query_5->execute();
				$last_name = $query_5->fetchColumn();


				$time = time();
                $query_6 = $this->db->prepare("INSERT INTO `friends` (`user1_id`, `user2_id`, `user1_name`, `user2_name`, `confirmed`, `time`) VALUES (34,?,'Petstapost',?,1,?) ");
                $query_6->bindValue(1, $user2_id);
                $query_6->bindValue(2, $first_name.' '.$last_name);
                $query_6->bindValue(3, $time);
                $query_6->execute();

                $query_7 = $this->db->prepare("INSERT INTO `friends` (`user1_id`, `user2_id`, `user1_name`, `user2_name`, `confirmed`, `time`) VALUES (?,34,?,'Petstapost',1,?) ");
                $query_7->bindValue(1, $user2_id);
                $query_7->bindValue(2, $first_name.' '.$last_name);
                $query_7->bindValue(3, $time);
                $query_7->execute();

				return true;

			}else{
				return false;
			}

		} catch(PDOException $e){
			die($e->getMessage());
		}

	}


	public function email_confirmed($username) {

		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `username`= ? AND `confirmed` = ?");
		$query->bindValue(1, $username);
		$query->bindValue(2, 1);
		
		try{
			
			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				return true;
			}else{
				return false;
			}

		} catch(PDOException $e){
			die($e->getMessage());
		}

	}

	public function login($username, $password) {

		global $bcrypt;  // Again make get the bcrypt variable, which is defined in init.php, which is included in login.php where this function is called

		$query = $this->db->prepare("SELECT `password`, `user_id` FROM `users` WHERE `username` = ?");
		$query->bindValue(1, $username);

		try{
			
			$query->execute();
			$data 				= $query->fetch();
			$stored_password 	= $data['password']; // stored hashed password
			$id   				= $data['user_id']; // id of the user to be returned if the password is verified, below.
			
			if($bcrypt->verify($password, $stored_password) === true){ // using the verify method to compare the password with the stored hashed password.
				return $id;	// returning the user's id.
			}else{
				return false;	
			}

		}catch(PDOException $e){
			die($e->getMessage());
		}
	
	}

	public function email_login($email, $password) {

		global $bcrypt;  // Again make get the bcrypt variable, which is defined in init.php, which is included in login.php where this function is called

		$query = $this->db->prepare("SELECT `password`, `user_id` FROM `users` WHERE `email` = ?");
		$query->bindValue(1, $email);

		try{
			
			$query->execute();
			$data 				= $query->fetch();
			$stored_password 	= $data['password']; // stored hashed password
			$id   				= $data['user_id']; // id of the user to be returned if the password is verified, below.
			
			if($bcrypt->verify($password, $stored_password) === true){ // using the verify method to compare the password with the stored hashed password.
				return $id;	// returning the user's id.
			}else{
				return false;	
			}

		}catch(PDOException $e){
			die($e->getMessage());
		}
	
	}

	public function verify($id) {

		$query = $this->db->prepare("SELECT `username` FROM `users` WHERE `user_id`= ?");
		$query->bindValue(1, $id);
	
		try{

			$query->execute();
			$row = $query->fetchColumn();

			if($row == $_SESSION['username']) {
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function check_cookie($username, $code) {

		$query = $this->db->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `username`= ? and `email_code` = ?");
		$query->bindValue(1, $username);
		$query->bindValue(2, $code);
	
		try{

			$query->execute();
			$row = $query->fetchColumn();

			if($row == 1) {
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function userdata($id) {

		$query = $this->db->prepare("SELECT * FROM `users` WHERE `user_id`= ?");
		$query->bindValue(1, $id);

		try{

			$query->execute();

			return $query->fetch();

		} catch(PDOException $e){

			die($e->getMessage());
		}

	}
	  	  	 
	public function get_users() {

		$query = $this->db->prepare("SELECT * FROM `users` ORDER BY `time` DESC");
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		return $query->fetchAll();

	}

	public function secure_profile($user_id) {

		$query = $this->db->prepare("UPDATE `users` SET `secured` = 1 WHERE `user_id` = ?");

		$query->bindValue(1, $user_id);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function remove_security($user_id) {

		$query = $this->db->prepare("UPDATE `users` SET `secured` = 0 WHERE `user_id` = ?");

		$query->bindValue(1, $user_id);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

	public function check_secure_profile($user_id) {

		$query = $this->db->prepare("SELECT `secured` FROM `users` WHERE `user_id` = ?");

		$query->bindValue(1, $user_id);
		
		try{
			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				return true;
			}else{
				return false;
			}
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}

}