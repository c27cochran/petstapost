<?php

	require __DIR__ .'/../core/init.php';
	
	$username = $_POST['username'];

	$profile_data 	= array();
	$user_id 		= $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
	$profile_data	= $users->userdata($user_id);

	if (isset($_POST['username'])) {
		echo '<script type="text/javascript">
				localStorage.setItem("pp_remember_me", 1);
				localStorage.setItem("pp_username", "'.$profile_data['username'].'");
				localStorage.setItem("pp_code", "'.$profile_data['email_code'].'");
			  </script>';
	}
?>