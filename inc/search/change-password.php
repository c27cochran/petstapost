<?php

    require __DIR__ .'/../core/init.php';

           
    if (empty($_GET['change-current']) || empty($_GET['change-new']) || empty($_GET['change-new-again'])) {

        $errors[] = 'All fields are required';

    } else if ($bcrypt->verify($_GET['change-current'], $user['password']) === true) {

        if (trim($_GET['change-new']) != trim($_GET['change-new-again'])) {
            $errors[] = 'Your new passwords do not match';
        } else if (strlen($_GET['change-new']) < 6) { 
            $errors[] = 'Your password must be at least 6 characters';
        } else if (strlen($_GET['change-new']) >18){
            $errors[] = 'Your password cannot be more than 18 characters long';
        } 

    } else {

        $errors[] = 'Your current password is incorrect';

    }

    if (empty($errors)) {
        
        $users->change_password($user['user_id'], $_GET['change-new']);
        echo "<div class='response-success'>
                <p class='modal-alert'><i class='fa fa-thumbs-o-up'></i>
                    &nbsp;We've changed your password.
                </p>
              </div>";

    } else {

        echo '<div class="response-error">
                <p class="modal-alert">
                    <i class="fa fa-exclamation-circle"></i>
                    &nbsp;' . implode('</p><p class="modal-alert">', $errors) . 
                '</p>
              </div>';
    }