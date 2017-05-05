<div class="col-md-12 display-friends-area hidden">
    <div class="row profile-row">
    <?php
        $friend_data = $friends->get_friends($user_id);
        $friend_count = $friends->count_friends($user_id);
        
        if ($friend_count >= 1) {
            foreach ($friend_data as $result) { 
    ?>
            <div id="profile_friend_<?php echo $result['friendship_id'];?>" class="col-md-3 profile-item">
                <?php

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
                            echo '<a class="comment-link" href="'.$username.'">
                                    <img src="'.$profile_pic.'" class="friend-img '.$profile_filter.'">
                                  </a>
                                  <p class="friend-container">
                                    <a class="comment-link" href="'.$username.'">
                                        '.$first.' '.$last.'
                                    </a>
                                  </p><br>';
                        }
 
                ?>

            </div>
    <?php
            }
        } elseif ($general->logged_in() === true && $users->verify($user_id) === true && $friend_count < 1) {
            echo '<div class="col-md-9 profile-item">
                    <h1 class="no-items-found">No Friends Yet</h1>
                    <h1 class="no-items-found"><i class="fa fa-arrow-down"></i></h1>
                    <p class="friend-container" style="margin-bottom: 30px;">
                        <a class="comment-link" href="search.php"><i class="fa fa-users"></i>&nbsp;Search for friends</a>
                    </p>
                  </div>';
        } else {
            echo '<div class="col-md-9 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No Friends Yet</h1></div>';
        }
    ?>
    </div>
</div>