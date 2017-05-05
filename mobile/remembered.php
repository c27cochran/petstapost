<?php

	require __DIR__ .'/../core/init.php';

	if(isset($_POST['usr']) && isset($_POST['code'])) {

	    $usr = $_POST['usr'];
	    $code = $_POST['code'];

	    // Make a verification
		if($users->check_cookie($usr, $code) == true) {
	        echo 'kibble.html?user='.$usr;
	    } else {
	    	echo '';
	    }
	}