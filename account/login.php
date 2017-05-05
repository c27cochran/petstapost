<?php

require __DIR__ .'/../core/init.php';

if (!empty($_POST['username-login']) && !empty($_POST['password-login'])) {

	$username = trim($_POST['username-login']);
	$password = trim($_POST['password-login']);

	// if username is actually an email address
	if (strpos($username, '@') !== FALSE) {
	    if (empty($username) === true || empty($password) === true) {
			$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, but we need your username or email and password.';
		} else if ($users->email_exists($username) === false) {
			$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry that email doesn\'t exist.';
		} else {
			if (strlen($password) > 20) {
				$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;The password should be less than 20 characters, without spacing.';
			}
			$login = $users->email_login($username, $password);
			if ($login === false) {
				$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, that password is invalid.';
			}else {
				$profile_data 	= array();
				$user_id 		= $users->fetch_info('user_id', 'email', $username); // Getting the user's id from the username in the Url.
				$profile_data	= $users->userdata($user_id);

				// Set the session to be read on the kibble/feed.php page 
				if (isset($_POST['autologin'])) {
					$_SESSION['remember_me'] = $_POST['autologin'];
				} else {
					$_SESSION['remember_me'] = '0';
				}
				
				session_regenerate_id(true);// destroying the old session id and creating a new one
				$_SESSION['id'] =  $login;
				echo 'kibble/'.$profile_data['username'];
				exit();
			}
		}
	} else {
	    if (empty($username) === true || empty($password) === true) {
			$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, but we need your username and password.';
		} else if ($users->user_exists($username) === false) {
			$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry that username doesn\'t exist.';
		} else {
			if (strlen($password) > 20) {
				$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;The password should be less than 20 characters, without spacing.';
			}
			$login = $users->login($username, $password);
			if ($login === false) {
				$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, that password is invalid.';
			}else {
				$profile_data 	= array();
				$user_id 		= $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
				$profile_data	= $users->userdata($user_id);

				// Set the session to be read on the kibble/feed.php page 
				if (isset($_POST['autologin'])) {
					$_SESSION['remember_me'] = $_POST['autologin'];
				} else {
					$_SESSION['remember_me'] = '0';
				}

		    	session_regenerate_id(true); // destroying the old session id and creating a new one
				$_SESSION['id'] =  $login;
				echo 'kibble/'.$profile_data['username'];
				exit();

			}
		}
	}

} else {
	$errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, but we need your username and password.';
}

if(empty($errors) === false){
	echo '<div class="response-error">
			<p class="modal-alert">' . implode('</p><p class="modal-alert">', $errors) . '</p>
		  </div>';	
} 