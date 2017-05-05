<?php
    
    require __DIR__ .'/../core/init.php';

    $register_errors = array();
if (isset($_SESSION['id'])) {
    if(empty($_POST['name_pet'])) {

        $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Pet name is required.';

    } else {
        
        if(!preg_match('/^[a-zA-Z0-9_ \.]+$/',$_POST['name_pet'])){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Pet name must be only letters or numbers.';  
        }
        if (strlen($_POST['name_pet']) < 2){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Please enter your first name.';
        } else if (strlen($_POST['name_pet']) > 50){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Pet name cannot be more than 50 characters long.';
        }
        if (strlen($_POST['breed_pet']) > 75){
            $register_errors[] = '<i class="fa fa-exclamation-circle"></i>&nbsp;Breed cannot be more than 75 characters long.';
        }
    }

    if(empty($register_errors) === true){

        $user_id = $_SESSION['id'];
        $pet_name = strip_tags($_POST['name_pet']);
        $pet_name_quotes = "'".strip_tags($_POST['name_pet'])."'";

        if ($_POST['past_present'] != 'Unknown') {
            $past_present = strip_tags($_POST['past_present']);
        } else {
            $past_present = 1;
        }

        if ($_POST['type_pet'] != 'Unknown') {
            $type = strip_tags($_POST['type_pet']);
        } else {
            $type = "";
        }

        if (isset($_POST['breed_pet'])) {
            $breed = strip_tags($_POST['breed_pet']);
        } else {
            $breed = "";
        }

        $pets->add_pet($user_id, $pet_name, $past_present, $type, $breed);
            
        echo '<span class="comment-link">
                                <img src="http://petstapost.com/img/pet-avatar-placeholder.jpg" class="pet-img">
                              </span>
                              <div id="camera-pet-upload">
                                    <a id="pet-avatar-file" href="javascript:void(0);" data-toggle="modal" data-target="#pet-avatar-modal" onclick="getPetID('.$pet_name_quotes.')">
                                        <i class="fa fa-camera"></i>
                                    </a>
                              </div>
                              <br><br>
                                <h2 class="pet-name">'.$pet_name.'</h2>
                                <h4 class="pet-info">'.$breed.'</h4><br>';
        exit();
    }

    if(empty($register_errors) === false){
        echo '<div class="response-error">
                <p class="modal-alert pet-error">' . implode('</p><p class="modal-alert pet-error">', $register_errors) . '</p>
              </div>';    
    }
} else {
    echo 'You Must Be Logged In.';
}