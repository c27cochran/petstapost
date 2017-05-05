<?php

require __DIR__ .'/../core/init.php';

if (isset($_POST['my_username']) && isset($_POST['their_username']) && isset($_POST['verified'])) {
    $my_username = $_POST['my_username'];
    $their_username = $_POST['their_username'];
    $verified = $_POST['verified'];
}

if ($users->user_exists($their_username)) {   

        $profile_data   = array();
        $their_user_id  = $users->fetch_info('user_id', 'username', $their_username); // Getting the user's id from the username in the Url.
        $profile_data   = $users->userdata($their_user_id);

        $first_name = $profile_data['first_name'];
        $last_name = $profile_data['last_name'];
        $email = $profile_data['email'];  
        $cover_color = $profile_data['cover_color'];
        $cover_filter = $profile_data['cover_picture_filter'];
        $profile_filter = $profile_data['profile_picture_filter'];

        $my_profile_data   = array();
        $my_user_id  = $users->fetch_info('user_id', 'username', $my_username); // Getting the user's id from the username in the Url.
        $my_profile_data   = $users->userdata($my_user_id);

        $my_first_name = $my_profile_data['first_name'];
        $my_last_name = $my_profile_data['last_name'];

        if (!empty($cover_color)) {
            echo '<style>.cover.profile .wrapper{border-bottom: 6px solid #'.$cover_color.';}</style>';
        } else {
            echo '<style>.cover.profile .wrapper{border-bottom: 1x solid #e2e9e6;}</style>';
        }

        $pet_count = $pets->count_pets($their_user_id);
        if(!empty($pet_count)) {
            $no_pets = $pet_count;
        } else {
            $no_pets = 0;
        }

        $post_count = $items->count_items($their_user_id);
        if(!empty($post_count)) {
            $no_posts = $post_count;
        } else {
            $no_posts = 0;
        }

        $friend_count = $friends->count_friends($their_user_id);
        if(!empty($friend_count)) {
            $no_friends = $friend_count;
        } else {
            $no_friends = 0;
        }

        if ($my_username) {
            echo '<span id="user-id" class="hidden">'.$my_user_id.'</span>';
            echo '<span id="my-name" class="hidden">'. $my_first_name.' '.$my_last_name.'</span>';
        }

        echo '<span id="to-user" class="hidden">'.$their_user_id.'</span>';


    echo '
    <!-- Setting variables in a hidden form to resend confirmation email -->
    <form id="resend-form" class="resend-form" name="resend-form" method="GET" style="display:none;">
        <input type="password" id="first-name-resend" name="first-name-resend" value="'.$first_name.'" class="form-control hidden">
        <input type="password" id="email-resend" name="email-resend" value="'.$email.'" class="form-control hidden">
        <input type="password" id="username-resend" name="username-resend" value="'.$their_username.'" class="form-control hidden">
    </form>

    <!-- Intro Header if user is found -->
    <div class="intro-home">
        <div class="intro-body">
            <div class="container profile-container">';


                    // Make some code to recognize their username
                    if ($verified == '1')  {
                        
                        if ($users->email_confirmed($their_username) === false) {
                            echo '<div class="col-md-10">
                                    <br>
                                    <div id="email-confirm-box" class="activate-box"></div>
                                        <div class="warning-box activate-box activate-container" style="top: -22px;">
                                            <p class="alert alert-warning" role="alert">
                                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                                Please check your email and activate your account.
                                                <br><br>
                                                <input type="submit" id="resend-email" class="resend-email btn btn-warning" name="resend-email" value="Resend Email" />
                                            </p>
                                    </div>
                                  </div>';
                        }

                    }

            echo '
                <div class="row">
                    <div class="col-md-10">
                        <div class="cover profile">
                            <div class="wrapper">';

                                if ($verified == '1')  {

                                echo '<div id="camera-cover-upload">
                                        <div id="cover-pic-file" class="fileUploadNoBg fileUpload">
                                            <i class="fa fa-camera"></i>
                                            <input id="cover-input" class="cover-input upload" name="cover_file" type="file" accept="image/*" data-toggle="modal" data-target="#cover-modal" />
                                        </div>
                                    </div>';
                                }

                            echo '<div class="image">';
                            $cover = $users->get_cover_photo($their_username);
                                    if ($cover) { 
                                        echo '<img class="cover-photo-profile '.$cover_filter.'" src="'.$cover.'">';
                                    } else { 
                                        echo '<img src="http://petstapost.com/img/cover-placeholder.jpg" alt="">';
                                    }
                                
                            echo '</div>
                        </div>
                        <div class="cover-info">';
                                    if ($verified == '1')  {
                                        echo '<div id="camera-profile-upload">
                                                <div id="profile-pic-file" class="fileUploadNoBg fileUpload">
                                                    <i class="fa fa-camera"></i>
                                                    <input id="avatar-input" class="avatar-input upload" name="avatar_file" type="file" accept="image/*" data-toggle="modal" data-target="#avatar-modal" />
                                                </div>
                                            </div>';
                                    }
                            echo '<div class="avatar">';
                            $profile_pic = $users->get_profile_photo($their_username);
                            if ($profile_pic) { 
                                echo '<img class="profile-pic '.$profile_filter.'" src="'.$profile_pic.'">';
                            } else { 
                                echo '<img src="http://petstapost.com/img/avatar-placeholder.png" alt="">';
                            }

                            echo '
                                </div>
                                <div class="name">
                                    <a href="yappy.html?user='.$their_username.'" id="their-name">';
                                        if($verified == '1'){ echo '<i class="fa fa-home"></i>&nbsp;'; }
                                        echo $first_name . ' ' . $last_name;
                            echo '   </a>
                                </div>
                                <ul class="cover-nav">
                                    <li id="show-pets" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fi-guide-dog" style="margin-left: 4px;"></i>&nbsp;Pets ('.$no_pets.')</a>
                                    </li>
                                    <li id="show-friends" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fa fa-fw fa-users"></i>&nbsp;Friends ('.$no_friends.')</a>
                                    </li>
                                    <li id="show-posts" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fa fa-fw fa-th" style="margin-left: -6px;"></i>&nbsp;Posts ('.$no_posts.')</a>
                                    </li>';
                                    if($verified == '1' && $users->check_secure_profile($their_user_id) === false){
                                        echo '<li id="secure-text" class="idle">
                                                    <a href="javascript:void(0);" id="secure-profile"><i class="fa fa-fw fa-lock"></i>&nbsp;Make Profile Private</a>
                                                </li>';
                                    } elseif ($verified == '1' && $users->check_secure_profile($their_user_id) === true) {
                                        echo '<li id="secure-text" class="idle">
                                                    <a href="javascript:void(0);" id="open-profile"><i class="fa fa-fw fa fa-unlock"></i>&nbsp;Make Profile Public</a>
                                                </li>';
                                    }
                             echo '</ul>
                            </div>
                        </div>
                    </div>
                </div>';

                if ($verified == '1')  {
                    echo '<div class="row profile-row left">
                            <form class="video-upload" action="http://petstapost.com/mobile/post-upload.php" method="POST" enctype="multipart/form-data">
                                <div id="profile-item-file" class="btn btn-item-upload fileUpload">
                                    <i class="fi-photo"></i>&nbsp;Post a Photo or Video
                                    <input id="item-input" class="item-input upload" name="item_file" type="file" data-toggle="modal" data-target="#item-modal" />
                                </div>
                                <!-- <p class="video-time">(Video must be 30 seconds or less)</p> -->
                                <div class="vid-caption-upload col-md-8 profile-item hidden">
                                    <h3 class="caption video-caption">Video Caption:</h3>
                                    <textarea autofocus id="custom-video-caption" class="form-control" name="custom-video-caption" maxlength="160" rows="2" style="resize:none"></textarea> 
                                    <button class="btn btn-primary video-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;Post</button>
                                </div>
                            </form>
                        </div>
                        <br>
                        <br>';
                    }
                    // Check if user is your friend
                    if ($verified == '0' && $friends->check_friends($my_user_id, $their_user_id) === true)  {

                    echo '<div class="row profile-row left">
                            <button id="remove-friend" class="btn btn-item-upload" href="javascript:void(0);">
                                <i class="fa fa-times-circle"></i>&nbsp;Remove Friend
                            </button>
                            <span id="friend-remove" class="hidden btn btn-item-upload"></span>
                        </div>
                        <br>
                        <br>';

                    } elseif ($verified == '0' && $friends->check_friend_request($my_user_id, $their_user_id) === true) {

                    echo '<div class="row profile-row left">
                        <button id="remove-friend-request" class="btn btn-item-upload" href="javascript:void(0);">
                                <i class="fa fa-minus-square"></i>&nbsp;Remove Friend Request
                            </button>
                            <span id="friend-remove-request" class="hidden btn btn-item-upload"></span>
                        </div>
                        <br>
                        <br>';

                    } elseif ($verified == '0' && $friends->has_requested_friend($my_user_id, $their_user_id) === true) {

                    echo '<div class="row profile-row left">
                            <button id="accept-friend-button" class="btn btn-item-upload" href="javascript:void(0);">
                                <i class="fa fa-check-circle"></i>&nbsp;Accept Friend Request
                            </button>
                            <span id="friend-accept" class="hidden btn btn-item-upload"></span>
                        </div>
                        <br>
                        <br>';

                    } elseif ($verified == '0' && $friends->check_friends($my_user_id, $their_user_id) === false) {

                    echo '<div class="row profile-row left">
                            <button id="add-friend-button" class="btn btn-item-upload" href="javascript:void(0);">
                                <i class="fa fa-plus-square"></i>&nbsp;Add Friend
                            </button>
                            <span id="friend-add" class="hidden btn btn-item-upload"></span>
                        </div>
                        <br>
                        <br>';
                    }

                echo '<hr>
                <div id="main-profile-container" class="row">';

                    if ($verified == '0' && $friends->check_friends($my_user_id, $their_user_id) === false && $users->check_secure_profile($their_user_id) === true)  {

                        echo '<div class="col-md-12">
                                <div class="row profile-row">
                                    <div class="col-md-9 profile-item">
                                        <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                                    </div>
                                </div>
                            </div>';
                    } else {
                        $item_group_count = $items->get_item_group_count($their_user_id);

                        echo '<script type="text/javascript">
                                $(document).ready(function() {
                                    var track_load = 0; //total loaded record group(s)
                                    var loading  = false; //to prevents multipal ajax loads
                                    var total_groups = '.$item_group_count.'; //total record group(s)
                                    
                                    $("#results").load("http://petstapost.com/mobile/items-scroll-load.php?group_no="+track_load+"&my_user='.$my_user_id.'&their_user='.$their_user_id.'&verified='.$verified.'", function() {track_load++;}); //load first group
                                    
                                    $(window).scroll(function() { //detect page scroll
                                        
                                        if($(window).scrollTop() + $(window).height() + 20 >= $(document).height())
                                        {
                                            if (track_load < total_groups) {
                                                $(".keep-scrolling").show();
                                            }

                                            if (track_load == total_groups) {
                                                $(".keep-scrolling").hide();
                                            }
                                            
                                            if(track_load < total_groups && loading==false) //there"s more data to load
                                            {
                                                loading = true; //prevent further ajax loading
                                                $(".animation_image").show(); //show loading image
                                                
                                                //load data from the server using a HTTP POST request
                                                $.post("http://petstapost.com/mobile/items-scroll-load.php?group_no="+track_load+"&my_user='.$my_user_id.'&their_user='.$their_user_id.'&verified='.$verified.'", function(data){
                                                                    
                                                    $("#results").append(data); //append received data into the element

                                                    //hide loading image
                                                    $(".animation_image").hide(); //hide loading image once data is received
                                                    
                                                    track_load++; //loaded group increment
                                                    loading = false; 
                                                
                                                }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
                                                    
                                                    $(".animation_image").hide(); //hide loading image
                                                    loading = false;
                                                
                                                });
                                                
                                            }
                                        }
                                    });
                                });
                            </script>

                        <div class="col-md-12 display-items-area">
                            <div  id="results" class="row profile-row">
                            
                            </div>
                            <div class="col-md-3 profile-item profile-item-no-margin animation_image" style="display:none">
                                <img src="img/ajax-loader.gif">
                            </div>
                        </div>';

                        

                        // Pet section

                        echo '<div class="col-md-12 display-pets-area hidden">
                        <div class="row profile-row">';

                            $current_pet_data = $pets->get_current_pets($their_user_id);
                            $past_pet_data = $pets->get_past_pets($their_user_id);
                            $pet_count = $pets->count_pets($their_user_id);
                            $current_pet_count = $pets->count_current_pets($their_user_id);
                            $past_pet_count = $pets->count_past_pets($their_user_id);
                            
                            if ($current_pet_count >= 1) {
                                echo '<div id="current-pets" class="col-md-9 profile-item profile-item-no-margin"><h3 class="hashtag-name center">Current Pets</h3></div>';
                                foreach ($current_pet_data as $result) { 

                                  echo '<div id="profile_pet_'.$result["pet_id"].'" class="col-md-9 profile-item profile-item-no-margin">';

                                    if ($verified == '1') { 

                                            $pet_name = $result['pet_name'];
                                            $pet_name_quotes = "'".$result['pet_name']."'";
                                            $profile_pic = $result['pet_avatar_url'];
                                            $profile_filter = $result['pet_filter'];
                                            $type = $result['type'];
                                            $breed = $result['breed'];

                                            echo '<span class="comment-link">
                                                    <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                                                  </span>
                                                  <div id="camera-pet-upload">
                                                        <a id="pet-avatar-file" href="javascript:void(0);" data-toggle="modal" data-target="#pet-avatar-modal" onclick="getPetID('.$pet_name_quotes.')">
                                                            <i class="fa fa-camera"></i>
                                                        </a>
                                                  </div>
                                                    <h2 class="pet-name">'.$pet_name.'</h2>
                                                    <h4 class="pet-info">'.$breed.'</h4>';

                                    } else if ($verified == '0') {

                                        $pet_name = $result['pet_name'];
                                        $profile_pic = $result['pet_avatar_url'];
                                        $profile_filter = $result['pet_filter'];
                                        $type = $result['type'];
                                        $breed = $result['breed'];

                                        if (!empty($pet_name)) {
                                            echo '<span class="comment-link">
                                                    <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                                                  </span>
                                                    <h2 class="pet-name">'.$pet_name.'</h2>
                                                    <h4 class="pet-info">'.$breed.'</h4>';
                                        }
                                    } 

                                echo '</div>';
                                }
                            }

                            if ($past_pet_count >= 1) {
                                
                                echo '<div id="past-pets" class="col-md-9 profile-item profile-item-no-margin"><h3 class="hashtag-name center">Past Pets</h3></div>';
                                
                                foreach ($past_pet_data as $result) { 

                                  echo '<div id="profile_pet_'.$result["pet_id"].'" class="col-md-9 profile-item profile-item-no-margin">';

                                    if ($verified == '1') { 

                                            $pet_name = $result['pet_name'];
                                            $pet_name_quotes = "'".$result['pet_name']."'";
                                            $profile_pic = $result['pet_avatar_url'];
                                            $profile_filter = $result['pet_filter'];
                                            $type = $result['type'];
                                            $breed = $result['breed'];

                                            echo '<span class="comment-link">
                                                    <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                                                  </span>
                                                  <div id="camera-pet-upload">
                                                        <a id="pet-avatar-file" href="javascript:void(0);" data-toggle="modal" data-target="#pet-avatar-modal" onclick="getPetID('.$pet_name_quotes.')">
                                                            <i class="fa fa-camera"></i>
                                                        </a>
                                                  </div>
                                                    <h2 class="pet-name">'.$pet_name.'</h2>
                                                    <h4 class="pet-info">'.$breed.'</h4>';

                                    } else if ($verified == '0') {

                                        $pet_name = $result['pet_name'];
                                        $profile_pic = $result['pet_avatar_url'];
                                        $profile_filter = $result['pet_filter'];
                                        $type = $result['type'];
                                        $breed = $result['breed'];

                                        if (!empty($pet_name)) {
                                            echo '<span class="comment-link">
                                                    <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                                                  </span>
                                                    <h2 class="pet-name">'.$pet_name.'</h2>
                                                    <h4 class="pet-info">'.$breed.'</h4>';
                                        }
                                    } 

                                echo '</div>';

                                }

                            }
                            
                            if ($verified == '1' && $pet_count < 1) {
                                echo '<div class="col-md-9 profile-item profile-item-no-margin no-pets">
                                        <h1 class="no-items-found">No Pets Yet</h1>
                                        <h1 class="no-items-found"><i class="fa fa-arrow-down"></i></h1>
                                        <p class="friend-container" style="margin-bottom: 30px;">
                                            <button class="btn btn-item-upload add-pet" href="javascript:void(0);">
                                                <i class="fa fa-plus-square"></i>&nbsp;Add Your Pet
                                            </button>
                                        </p>
                                      </div>';
                            } elseif ($verified == '0' && $pet_count < 1) {
                                echo '<div class="col-md-9 profile-item profile-item-no-margin"><h1 class="no-items-found">No Pets, Just Hangin\'</h1></div>';
                            }

                            if ($verified == '1' && $pet_count >= 1) {

                            echo '<div class="col-md-9 profile-item profile-item-no-margin register-pet-container" style="display:none;">
                                <div id="register-pet-container">
                                    <div id="register-pet-response"></div>
                                    <div class="register-pet-modal-content">
                                        <form id="register-pet-form" class="register-pet-form" name="register-pet-form" method="POST">
                                            <div class="form-group">
                                                <select id="past_present" name="past_present" class="form-control">
                                                  <option value="Unknown">Current or Past Pet...</option>
                                                  <option value="1">Current Pet</option>
                                                  <option value="0">Past Pet</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" id="name_pet" name="name_pet" placeholder="Pet Name" value="" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <select id="type_pet" name="type_pet" class="form-control">
                                                  <option value="Unknown">Pet Type...</option>
                                                  <option value="Dog">Dog</option>
                                                  <option value="Cat">Cat</option>
                                                  <option value="Horse">Horse</option>
                                                  <option value="Rabbit">Rabbit</option>
                                                  <option value="Ferret">Ferret</option>
                                                  <option value="Bird">Bird</option>
                                                  <option value="Fish">Fish</option>
                                                  <option value="Gerbal">Gerbal</option>
                                                  <option value="Hamster">Hamster</option>
                                                  <option value="Guinea Pig">Guinea Pig</option>
                                                  <option value="Snake">Snake</option>
                                                  <option value="Lizard">Lizard</option>
                                                  <option value="Turtle">Turtle</option>
                                                  <option value="Other">Other</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" id="breed_pet" name="breed_pet" placeholder="Breed" value="" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <input type="submit" id="submit_pet" class="btn btn-primary" value="Add Pet!">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="add-pet-div" class="col-md-9 profile-item profile-item-no-margin">
                                <button class="btn btn-item-upload add-pet" href="javascript:void(0);" style="margin-bottom:15px;">
                                    <i class="fa fa-plus-square"></i>&nbsp;Add Another Pet
                                </button>
                            </div>';

                            }

                        echo '</div>
                    </div>

                    <div class="container" id="crop-pet-avatar">

                        <!-- Cropping modal -->
                        <div class="modal fade" id="pet-avatar-modal" aria-hidden="true" aria-labelledby="pet-avatar-modal-label" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header picture-modal">
                                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                                        <h2 class="modal-title" id="pet-avatar-modal-label">Change Pet Pic</h2>
                                    </div>
                                    <div class="modal-body modal-body-pet-avatar">
                                    <form class="pet-avatar-pic-upload hidden" action=""http://petstapost.com/mobile/post-upload.php" method="POST" enctype="multipart/form-data">
                                        <input type="file" name="my_file" class="hidden" multiple="multiple" />
                                            <!-- Current avatar -->
                                            <div class="pet-avatar-view">
                                                <img src="" alt="Avatar Not Found">
                                            </div>
                                            <div class="row avatar-btns">
                                                <div class="col-md-12"> 
                                                    <div class="btn-group-wrap">
                                                        <div class="btn-group">
                                                          <button id="bw-pet-avatar" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                                          <button id="chrome-pet-avatar" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                                          <button id="bold-pet-avatar" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group-wrap">
                                                        <div class="btn-group">
                                                          <button id="fade-pet-avatar" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                                          <button id="color-blast-pet-avatar" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                                          <button id="antique-pet-avatar" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group-wrap">
                                                        <div class="btn-group">
                                                          <button id="brighten-pet-avatar" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                                          <button id="enhance-pet-avatar" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                                          <button id="original-pet-avatar" class="btn btn-primary btn-filter left" type="button">Original</button>
                                                          <span id="pet-filter" class="hidden"></span>
                                                          <span id="pet-username" class="hidden">'.$my_username.'</span>
                                                          <span id="pet-id" class="hidden"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button id="close-modal-pet-avatar" class="btn btn-primary avatar-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                                    <button class="btn btn-primary avatar-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="pet-avatar-body">
                                            <form class="pet-avatar-form" action="http://petstapost.com/crop-pet-avatar.php" enctype="multipart/form-data" method="post">
                                                <div class="pet-avatar-upload fileUpload btn btn-primary">
                                                    <input class="pet-avatar-src" name="pet_avatar_src" type="hidden">
                                                    <input class="pet-avatar-data" name="pet_avatar_data" type="hidden">
                                                    <input class="username" name="username" type="hidden" value="'.$my_username.'">
                                                    <i class="fi-photo"></i>&nbsp;
                                                    <span>Upload Photo</span>
                                                    <input id="pet-avatar-input" class="pet-avatar-input upload" name="pet_avatar_file" type="file" accept="image/*" />
                                                </div>

                                                <!-- Crop and preview -->
                                                <div class="row crop-preview-pet-avatar hidden">
                                                    <div class="col-md-9">
                                                        <div class="pet-avatar-wrapper"></div>
                                                    </div>
                                                    <div class="col-md-3 preview-div">
                                                        <h3 class="preview">Preview</h3>
                                                        <div class="pet-avatar-preview preview-lg"></div>
                                                    </div>
                                                </div>

                                                <div class="row avatar-btns">
                                                    <div class="col-md-3 right">
                                                        <button class="btn btn-primary btn-block pet-avatar-save hidden" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal -->
                    </div>';



                    // Friend Section
                    echo '<div class="col-md-12 display-friends-area hidden">
                            <div class="row profile-row">';

                                $friend_data = $friends->get_friends($their_user_id);
                                $friend_count = $friends->count_friends($their_user_id);
                                
                                if ($friend_count >= 1) {
                                    foreach ($friend_data as $result) { 

                                        echo '<div id="profile_friend_'.$result["friendship_id"].'" class="col-md-3 profile-item">';

                                                $profile_data   = array();
                                                $profile_data   = $users->userdata($result['user2_id']);

                                                $first = $profile_data['first_name'];
                                                $last = $profile_data['last_name'];
                                                $username = $profile_data['username'];
                                                $profile_pic = $profile_data['profile_picture'];
                                                if (empty($profile_pic)) {
                                                    $profile_pic = 'http://petstapost.com/img/avatar-placeholder.png';
                                                }
                                                $profile_filter = $profile_data['profile_picture_filter'];

                                                if (!empty($first)) {
                                                    echo '<a class="comment-link" href="yappy.html?user='.$username.'">
                                                            <img src="'.$profile_pic.'" class="friend-img '.$profile_filter.'">
                                                          </a>
                                                          <p class="friend-container">
                                                            <a class="comment-link" href="yappy.html?user='.$username.'">
                                                                '.$first.' '.$last.'
                                                            </a>
                                                          </p><br>';
                                                }

                                        echo '</div>';

                                    }
                                } elseif ($verified == '1' && $friend_count < 1) {
                                    echo '<div class="col-md-9 profile-item">
                                            <h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No Friends Yet</h1>
                                          </div>';
                                } else {
                                    echo '<div class="col-md-9 profile-item">
                                            <h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No Friends Yet</h1>
                                          </div>';
                                }
                            echo '
                            </div>
                        </div>';

                    }

            echo '</div>
                </div>
            </div>
        </div>';

} else {
    require_once __DIR__ .'/../inc/yappy/not-found.php';
} 

echo "
<script>
    $('a.div-scroll').bind('click', function(event) {
        var _anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $(_anchor.attr('href')).offset().top - 60
        }, 400, 'easeInCirc');
        event.preventDefault();
    });

    $('#show-posts').on('click', function(e) {
        e.preventDefault();
        $('.display-items-area').removeClass('hidden');
        $('.display-friends-area').addClass('hidden');
        $('.display-pets-area').addClass('hidden');
    });

    $('#show-friends').on('click', function(e) {
        e.preventDefault();
        $('.display-friends-area').removeClass('hidden');
        $('.display-items-area').addClass('hidden');
        $('.display-pets-area').addClass('hidden');
    });

    $('#show-pets').on('click', function(e) {
        e.preventDefault();
        $('.display-pets-area').removeClass('hidden');
        $('.display-friends-area').addClass('hidden');
        $('.display-items-area').addClass('hidden');
    });
</script>
";


echo '<div class="container" id="crop-cover">

        <!-- Cropping modal -->
        <div class="modal fade" id="cover-modal" aria-hidden="true" aria-labelledby="cover-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-no-border">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="cover-modal-label">Change Cover Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <form class="cover-pic-upload hidden" action="http://petstapost.com/mobile/post-upload.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                            <!-- Current cover -->
                            <div class="cover-view">
                                <img src="" alt="Cover Photo Not Found">
                            </div>
                            <div class="row cover-btns">
                                <div class="col-md-12">
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-cover" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-cover" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-cover" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-cover" class="btn btn-primary btn-filter left" type="button">Fade</button>  
                                          <button id="color-blast-cover" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-cover" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-cover" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-cover" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-cover" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="cover-filter" class="hidden"></span>
                                          <span id="cover-username" class="hidden"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-cover" class="btn btn-primary cover-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary cover-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="cover-body">
                            <form class="cover-form" action="http://petstapost.com/crop-cover.php" enctype="multipart/form-data" method="post">
                                <div class="cover-upload hidden">
                                    <input class="cover-src" name="cover_src" type="hidden">
                                    <input class="cover-data" name="cover_data" type="hidden">
                                    <input id="cover-body-username" class="username" name="username" type="hidden" value="">
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-cover">
                                    <div class="col-md-9">
                                        <div class="cover-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="cover-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row cover-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block cover-save" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <div class="container" id="crop-avatar">

        <!-- Cropping modal -->
        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-no-border">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="avatar-modal-label">Change Profile Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <div id="avatar-failed-upload-message"></div>
                    <form class="profile-pic-upload hidden" action="http://petstapost.com/mobile/post-upload.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                            <!-- Current avatar -->
                            <div class="avatar-view">
                                <img src="" alt="Avatar Not Found">
                            </div>
                            <div class="row avatar-btns">
                                <div class="col-md-12"> 
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-avatar" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-avatar" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-avatar" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-avatar" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                          <button id="color-blast-avatar" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-avatar" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-avatar" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-avatar" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-avatar" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="profile-filter" class="hidden"></span>
                                          <span id="profile-username" class="hidden"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-avatar" class="btn btn-primary avatar-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary avatar-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="avatar-body">
                            <form class="avatar-form" action="http://petstapost.com/crop-avatar.php" enctype="multipart/form-data" method="post">
                                <div class="avatar-upload hidden">
                                    <input class="avatar-src" name="avatar_src" type="hidden">
                                    <input class="avatar-data" name="avatar_data" type="hidden">
                                    <input id="avatar-body-username" class="username" name="username" type="hidden" value="">
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-avatar">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="avatar-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row avatar-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block avatar-save" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <div class="container" id="crop-item">

        <!-- Cropping modal -->
        <div class="modal fade" id="item-modal" aria-hidden="true" aria-labelledby="item-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-no-border">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="item-modal-label">Add a Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <div id="item-failed-upload-message"></div>
                    <form class="item-pic-upload hidden" action="http://petstapost.com/mobile/post-upload.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                        <input type="text" id="item-filter-input" name="item" class="hidden" value="">
                            <!-- Current item -->
                            <div class="item-view">
                                <img src="" alt="Pic Not Found">
                            </div>
                            <div class="row item-btns">
                                <div class="col-md-12">
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-item" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-item" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-item" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-item" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                          <button id="color-blast-item" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-item" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-item" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-item" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-item" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="item-filter" class="hidden">original-filter</span>
                                          <span id="item-username" class="hidden"></span>
                                          <span id="item-filename" class="hidden"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="caption-group">
                                <h3 class="caption">Caption:</h3>
                                <textarea class="form-control custom-caption" name="caption" rows="2" style="resize:none" maxlength="160" onKeyUp="mentionCaption()"></textarea> 
                                <ul class="dropdown-menu" id="mention-results-caption"></ul>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-item" class="btn btn-primary item-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary item-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="item-body">
                            <form class="item-form" action="http://petstapost.com/crop-item.php" enctype="multipart/form-data" method="post">
                                <div class="item-upload hidden">
                                    <input class="item-src" name="item_src" type="hidden">
                                    <input class="item-data" name="item_data" type="hidden">
                                    <input id="item-body-username" class="username" name="username" type="hidden" value="">
                                    <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-item ">
                                    <div class="col-md-9">
                                        <div class="item-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="item-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row item-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block item-save " type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <script src="js/petstapost.js"></script>

    <script src="js/cropper.min.js"></script>

    <script src="js/crop-avatar.js"></script>

    <script src="js/crop-pet-avatar.js"></script>

    <script src="js/crop-cover.js"></script>

    <script src="js/crop-item.js"></script>

    <script src="js/transloadit.min.js"></script>

    <div id="script-container"></div>

    <script type="text/javascript">

        $(function() {

            var username = localStorage.getItem("pp_username");

            $("#item-username").html(username);
            $("#item-body-username").attr("value", username);

            $("#profile-username").html(username);
            $("#avatar-body-username").attr("value", username);

            $("#cover-username").html(username);
            $("#cover-body-username").attr("value", username);

        });

    </script>
    ';
?>