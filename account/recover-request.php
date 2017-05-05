<?php

require __DIR__ .'/../core/init.php';

if (!empty($_GET['recover-email'])) {
	if ($users->email_exists($_GET['recover-email']) === true) {

		$users->confirm_recover($_GET['recover-email']);
		echo "<div class='response-success'>
				<p class='modal-alert'><i class='fa fa-thumbs-o-up'></i>&nbsp;Thank you, we've sent you a new password.</p>
			  </div>";

	} else {
		echo '<div class="response-error">
        		<p class="modal-alert"><i class="fa fa-exclamation-circle"></i>&nbsp;Sorry, we can\'t find your email address.</p>
 		  	  </div>';
	}
} else {
	echo '<div class="response-error">
        	<p class="modal-alert"><i class="fa fa-exclamation-circle"></i>&nbsp;Please enter your email address.</p>
 		  </div>';
}