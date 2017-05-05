<?php
    
    require __DIR__ .'/../core/init.php';

    $register_errors = array();

    if(empty($_POST['username_register']) || empty($_POST['firstname_register']) || 
        empty($_POST['lastname_register']) || empty($_POST['password_register']) || empty($_POST['email_register'])) {

        $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;All fields are required.';

    } else {
        
        if ($users->user_exists($_POST['username_register']) === true) {
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;That username already exists.';
        }
        if(!preg_match('/^[a-zA-Z0-9_\.]+$/',$_POST['username_register'])){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Please enter a username with only letters, numbers and/or underscores.';  
        }
        if (strlen($_POST['firstname_register']) < 2){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Please enter your first name.';
        } else if (strlen($_POST['firstname_register']) > 50){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Your first name cannot be more than 50 characters long.';
        }
        if (strlen($_POST['lastname_register']) < 2){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Please enter your last name.';
        } else if (strlen($_POST['lastname_register']) > 50){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Your last name cannot be more than 50 characters long.';
        }
        if (strlen($_POST['password_register']) < 6){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Your password must be at least 6 characters.';
        } else if (strlen($_POST['password_register']) > 20){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Your password cannot be more than 20 characters long.';
        }
        if (filter_var($_POST['email_register'], FILTER_VALIDATE_EMAIL) === false) {
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Please enter a valid email address.';
        }else if ($users->email_exists($_POST['email_register']) === true) {
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;That email already exists.';
        }
    }

    if(empty($register_errors) === true){
        
        $first_name = strip_tags($_POST['firstname_register']);
        $last_name = strip_tags($_POST['lastname_register']);
        $username = strip_tags($_POST['username_register']);
        $password   = $_POST['password_register'];
        $email      = htmlentities($_POST['email_register']);

        $users->register($first_name, $last_name, $password, $email, $username);
        $login = $users->login($username, $password);

        $profile_data   = array();
        $user_id        = $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
        $profile_data   = $users->userdata($user_id);

            
        echo 'kibble.html?user='.$username;
        exit();
    }

    if(empty($register_errors) === false){
        echo '<div class="response-error">
                <p class="modal-alert">' . implode('</p><p class="modal-alert">', $register_errors) . '</p>
              </div>';    
    }