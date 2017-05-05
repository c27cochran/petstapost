<?php

require __DIR__ .'/../core/init.php';

if (!empty($_GET['email-resend']) && !empty($_GET['first-name-resend']) && !empty($_GET['username-resend'])) {

	$email = $_GET['email-resend'];
	$first_name = $_GET['first-name-resend'];
	$username = $_GET['username-resend'];

	$resend = $users->resend_confirmation($first_name, $email, $username);
		if ($resend === true) {
			echo '<br><br><div class="success-box success-activate-box">
	                <p class="alert alert-success" role="alert">
	                    <i class="fa fa-thumbs-o-up"></i>&nbsp
	                    Confirmation email has been sent!
	                </p>
	             </div>';
		} else {
			echo '<div class="warning-box">
                <p class="alert alert-warning" role="alert">
                    <i class="fa fa-exclamation-triangle"></i>&nbsp;
                    Sorry, there was an error sending the email. Please try again.
                </p>
              </div>';
		}

} else {
	echo '<div class="response-error">
			<p class="modal-alert"><i class="fa fa-exclamation-circle"></i>&nbsp;
				Sorry, we can\'t find your email address.
			</p>
	  	  </div>';
}