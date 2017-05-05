<?php
    require_once 'inc/yappy/yappy-header.php';
    
    if (!isset($_SESSION['profile_img_url']) && empty($_SESSION['profile_img_url'])) {
        $_SESSION['profile_img_url'] = 'img/avatar/'.$username.'-profile.png';
    } else {
        if (file_exists($_SESSION['profile_img_url'])) {
            unlink($_SESSION['profile_img_url']);
        }
    }

    if (!isset($_SESSION['cover_img_url']) && empty($_SESSION['cover_img_url'])) {
        $_SESSION['cover_img_url'] = 'img/avatar/'.$username.'-cover.png';
    } else {
        if (file_exists($_SESSION['cover_img_url'])) {
            unlink($_SESSION['cover_img_url']);
        }
    }

    if (!isset($_SESSION['pet_img_url']) && empty($_SESSION['pet_img_url'])) {
        $_SESSION['pet_img_url'] = 'img/pet-avatar/'.$username.'-pet-avatar.png';
    } else {
        if (file_exists($_SESSION['pet_img_url'])) {
            unlink($_SESSION['pet_img_url']);
        }
    }

    if (!empty($_SESSION['profile_img_url'])) {
        if (file_exists($_SESSION['profile_img_url'])) {
          unlink($_SESSION['profile_img_url']);
        }
    }

    if (!empty($_SESSION['cover_img_url'])) {
        if (file_exists($_SESSION['cover_img_url'])) {
          unlink($_SESSION['cover_img_url']);
        }
    }

    if (!empty($_SESSION['pet_img_url'])) {
        if (file_exists($_SESSION['pet_img_url'])) {
          unlink($_SESSION['pet_img_url']);
        }
    }

    if ($users->user_exists($username)) {   

        $profile_data   = array();
        $user_id        = $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
        $profile_data   = $users->userdata($user_id);

        $first_name = $profile_data['first_name'];
        $last_name = $profile_data['last_name'];
        $email = $profile_data['email'];  
        $cover_color = $profile_data['cover_color'];
        $cover_filter = $profile_data['cover_picture_filter'];
        $profile_filter = $profile_data['profile_picture_filter'];

        if (!empty($cover_color)) {
            echo '<style>.cover.profile .wrapper{border-bottom: 6px solid #'.$cover_color.';}</style>';
        } else {
            echo '<style>.cover.profile .wrapper{border-bottom: 1x solid #e2e9e6;}</style>';
        }

        $pet_count = $pets->count_pets($user_id);
        if(!empty($pet_count)) {
            $no_pets = $pet_count;
        } else {
            $no_pets = 0;
        }

        $post_count = $items->count_items($user_id);
        if(!empty($post_count)) {
            $no_posts = $post_count;
        } else {
            $no_posts = 0;
        }

        $friend_count = $friends->count_friends($user_id);
        if(!empty($friend_count)) {
            $no_friends = $friend_count;
        } else {
            $no_friends = 0;
        }

        if (isset($_SESSION['id'])) {
            echo '<span id="user-id" class="hidden">'.$_SESSION['id'].'</span>';
            echo '<span id="my-name" class="hidden">'. $_SESSION['name'].'</span>';
        }

        echo '<span id="to-user" class="hidden">'.$user_id.'</span>';

?>

    <!-- Setting variables in a hidden form to resend confirmation email -->
    <form id="resend-form" class="resend-form" name="resend-form" method="GET" style="display:none;">
        <input type="password" id="first-name-resend" name="first-name-resend" value="<?php echo $first_name;?>" class="form-control hidden">
        <input type="password" id="email-resend" name="email-resend" value="<?php echo $email;?>" class="form-control hidden">
        <input type="password" id="username-resend" name="username-resend" value="<?php echo $username; ?>" class="form-control hidden">
    </form>

    <!-- Intro Header if user is found -->
    <div class="intro-home">
        <div class="intro-body">
            <div class="container profile-container">
                <?php

                    // Make some code to recognize their username
                    if ($general->logged_in() === true && $users->verify($user_id) === true)  {
                        
                        if (empty($username)) {
                            if (!isset($_SESSION['username']) && empty($_SESSION['username'])) {
                                header('Location: account/logout');
                            } else {
                                header('Location: '.$_SESSION['username']);
                            }
                        }
                        if ($users->email_confirmed($username) === false) {
                            echo '<div class="col-md-10">
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

                    } elseif ($general->logged_in() === false && empty($username)) {
                        header('Location: account/logout');
                    }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        <div class="cover profile">
                            <div class="wrapper">
                                <?php 
                                    if ($general->logged_in() === true && $users->verify($user_id) === true)  {
                                ?>
                                <div id="camera-cover-upload">
                                    <div id="cover-pic-file" class="fileUploadNoBg fileUpload">
                                        <i class="fa fa-camera"></i>
                                        <input id="cover-input" class="cover-input upload" name="cover_file" type="file" accept="image/*" data-toggle="modal" data-target="#cover-modal" />
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                                <div class="image">
                                <?php
                                    $cover = $users->get_cover_photo($username);
                                    if ($cover) { 
                                        echo '<img class="cover-photo-profile '.$cover_filter.'" src="'.$cover.'">';
                                    } else { 
                                        echo '<img src="http://petstapost.com/img/cover-placeholder.jpg" alt="">';
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="cover-info">
                                <?php 

                                    if ($general->logged_in() === true  && $users->verify($user_id) === true)  {
                                        ?>
                                        <div id="camera-profile-upload">
                                            <div id="profile-pic-file" class="fileUploadNoBg fileUpload">
                                                <i class="fa fa-camera"></i>
                                                <input id="avatar-input" class="avatar-input upload" name="avatar_file" type="file" accept="image/*" data-toggle="modal" data-target="#avatar-modal" />
                                            </div>
                                        </div>
                                <?php
                                    }
                                ?>
                                <div class="avatar">
                                    <?php
                                        $profile_pic = $users->get_profile_photo($username);
                                        if ($profile_pic) { 
                                            echo '<img class="profile-pic '.$profile_filter.'" src="'.$profile_pic.'">';
                                        } else { 
                                            echo '<img src="http://petstapost.com/img/avatar-placeholder.png" alt="">';
                                        }
                                    ?>
                                </div>
                                <div class="name">
                                    <a href="<?php echo $username;?>" id="their-name">
                                        <?php if($general->logged_in() && $users->verify($user_id)){ ?><i class="fa fa-home"></i>&nbsp;<?php } ?>
                                        <?php echo $first_name . ' ' . $last_name; ?>
                                    </a>
                                </div>
                                <ul class="cover-nav">
                                    <li id="show-pets" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fi-guide-dog"></i>&nbsp;Pets (<?php echo $no_pets;?>)</a>
                                    </li>
                                    <li id="show-friends" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fa fa-fw fa-users"></i>&nbsp;Friends (<?php echo $no_friends;?>)</a>
                                    </li>
                                    <li id="show-posts" class="idle">
                                        <a class="div-scroll" href="#main-profile-container"><i class="fa fa-fw fa-th"></i>&nbsp;Posts (<?php echo $no_posts;?>)</a>
                                    </li>
                                    <!-- <li class="idle">
                                        <a href="javascript:void(0);"><i class="fa fa-fw fa-book"></i>&nbsp;Albums</a>
                                    </li> -->
                                    <?php if($general->logged_in() && $users->verify($user_id) && $users->check_secure_profile($user_id) === false){ ?>
                                        <li id="secure-text" class="idle">
                                            <a href="javascript:void(0);" id="secure-profile"><i class="fa fa-fw fa-lock"></i>&nbsp;Make Profile Private</a>
                                        </li>
                                    <?php } elseif ($general->logged_in() && $users->verify($user_id) && $users->check_secure_profile($user_id) === true) { ?>
                                        <li id="secure-text" class="idle">
                                            <a href="javascript:void(0);" id="open-profile"><i class="fa fa-fw fa fa-unlock"></i>&nbsp;Make Profile Public</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if ($general->logged_in() === true && $users->verify($user_id) === true)  {
                ?>
                    <div class="row profile-row left">
                        <form class="video-upload" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                            <div id="profile-item-file" class="btn btn-item-upload fileUpload">
                                <i class="fi-photo"></i>&nbsp;Post a Photo or Video
                                <input id="item-input" class="item-input upload" name="item_file" type="file" data-toggle="modal" data-target="#item-modal" />
                            </div>
                            <p class="video-time">(Video must be 30 seconds or less)</p>
                            <div class="vid-caption-upload col-md-8 profile-item hidden">
                                <h3 class="caption video-caption">Video Caption:</h3>
                                <textarea autofocus id="custom-video-caption" class="form-control" name="custom-video-caption" maxlength="160" rows="2" style="resize:none"></textarea> 
                                <button class="btn btn-primary video-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;Post</button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <br>
                <?php
                    }
                ?>
                <?php
                    // Check if user is your friend
                    if ($general->logged_in() === true && $users->verify($user_id) === false && $friends->check_friends($_SESSION['id'], $user_id) === true)  {
                ?>
                    <div class="row profile-row left">
                        <button id="remove-friend" class="btn btn-item-upload" href="javascript:void(0);">
                            <i class="fa fa-times-circle"></i>&nbsp;Remove Friend
                        </button>
                        <span id="friend-remove" class="hidden btn btn-item-upload"></span>
                    </div>
                    <br>
                    <br>
                <?php
                    } elseif ($general->logged_in() === true && $users->verify($user_id) === false && $friends->check_friend_request($_SESSION['id'], $user_id) === true) {
                ?>
                    <div class="row profile-row left">
                        <button id="remove-friend-request" class="btn btn-item-upload" href="javascript:void(0);">
                            <i class="fa fa-minus-square"></i>&nbsp;Remove Friend Request
                        </button>
                        <span id="friend-remove-request" class="hidden btn btn-item-upload"></span>
                    </div>
                    <br>
                    <br>  
                <?php
                    } elseif ($general->logged_in() === true && $users->verify($user_id) === false && $friends->has_requested_friend($_SESSION['id'], $user_id) === true) {
                ?>
                    <div class="row profile-row left">
                        <button id="accept-friend-button" class="btn btn-item-upload" href="javascript:void(0);">
                            <i class="fa fa-check-circle"></i>&nbsp;Accept Friend Request
                        </button>
                        <span id="friend-accept" class="hidden btn btn-item-upload"></span>
                    </div>
                    <br>
                    <br>
                <?php
                    } elseif ($general->logged_in() === true && $users->verify($user_id) === false && $friends->check_friends($_SESSION['id'], $user_id) === false) {
                ?>
                    <div class="row profile-row left">
                        <button id="add-friend-button" class="btn btn-item-upload" href="javascript:void(0);">
                            <i class="fa fa-plus-square"></i>&nbsp;Add Friend
                        </button>
                        <span id="friend-add" class="hidden btn btn-item-upload"></span>
                    </div>
                    <br>
                    <br>
                <?php
                    }
                ?>
                <hr>
                <div id="main-profile-container" class="row">
                    <?php
                        // Check if user is your friend
                        if ($general->logged_in() === false && $users->check_secure_profile($user_id) === true) {
                    ?>
                        <div class="col-md-12">
                            <div class="row profile-row">
                                <div class="col-md-9 profile-item">
                                    <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                                </div>
                            </div>
                        </div>
                    <?php
                        } elseif ($general->logged_in() === true && $users->verify($user_id) === false && $friends->check_friends($_SESSION['id'], $user_id) === false && $users->check_secure_profile($user_id) === true)  {
                    ?>
                        <div class="col-md-12">
                            <div class="row profile-row">
                                <div class="col-md-9 profile-item">
                                    <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                                </div>
                            </div>
                        </div>
                    <?php
                        } elseif ($users->verify($user_id) === false && $users->check_secure_profile($user_id) === false)  {

                            require_once 'inc/yappy/display-items.php';
                            require_once 'inc/yappy/display-friends.php';
                            require_once 'inc/yappy/display-pets.php';

                        } elseif ($users->verify($user_id) === false && $friends->check_friends($_SESSION['id'], $user_id) === true && $users->check_secure_profile($user_id) === true)  {

                            require_once 'inc/yappy/display-items.php';
                            require_once 'inc/yappy/display-friends.php';
                            require_once 'inc/yappy/display-pets.php';

                        } elseif ($users->verify($user_id) === true)  {

                            require_once 'inc/yappy/display-items.php';
                            require_once 'inc/yappy/display-friends.php';
                            require_once 'inc/yappy/display-pets.php';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
        } else {
            require_once 'inc/yappy/not-found.php';
        } 
    ?>

<?php
    require_once 'inc/yappy/yappy-footer.php';
?>