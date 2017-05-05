<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="/$2">@$2</a>', '$1<a class="comment-link" href="/hashtag.php?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

if (isset($_SESSION['id'])) {

    $user_id = $_SESSION['id'];

    if ($general->logged_in() === true && $users->verify($user_id) === true)  {

        // People you may know data
        $possible_friends = $friends->you_may_know($user_id);
        // Friend request data
        $fr_data = $notifications->get_friend_request_notifications($user_id);
        // Friend accepted data
        $accepted_data = $notifications->get_friend_accepted_notifications($user_id);
        // Comment data
        $comm_count = $notifications->count_comment_notifications($user_id);
        $comm_data = $notifications->get_comment_notifications($user_id);
        // Dig data
        $fav_data = $notifications->get_favorite_notifications($user_id);

        $username = $_SESSION['username'];
        // Comment mentions
        $comm_mention_data = $notifications->get_comment_mention_notifications($username);
        // Caption mentions
        $caption_mention_data = $notifications->get_mention_notifications($username);

        // Mark as viewed
        $notifications->set_viewed_notifications($user_id);
?>

<div id="notification-container" class="white-popup-block" style="max-width:600px; margin: 20px auto;">
<?php
    if (empty($accepted_data) && empty($fr_data)  && empty($comm_count)  && empty($fav_data) && empty($possible_friends) 
        && empty($comm_mention_data) && empty($caption_mention_data)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px; text-align: center;">No new notifications</h2>
<?php
    }

    if (!empty($accepted_data) || !empty($fr_data) || !empty($comm_count) || !empty($fav_data) || !empty($possible_friends) 
        || !empty($comm_mention_data) || !empty($caption_mention_data)) {

        echo '<button id="clear-all" class="clear-notifs-btn">Clear All</button>';

    }
?>
<?php 
    if (!empty($possible_friends)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px;">You may know...</h2>

<?php
        foreach ($possible_friends as $pf) {
            $first = $pf['first_name'];
            $last = $pf['last_name'];
            $username = $pf['username'];
            $profile_pic = $pf['profile_picture'];
            if (empty($profile_pic)) {
                $profile_pic = 'http://petstapost.com/img/avatar-placeholder.png';
            }
            $profile_filter = $pf['profile_picture_filter'];

            if (!empty($first)) {
                echo '<p class="popup-comment" style="margin: 20px 30px;">
                        <a class="comment-link" href="'.$username.'" target="_blank">
                            <img src="'.$profile_pic.'" class="fr-img '.$profile_filter.'">&nbsp;'.$first.' '.$last.'
                        </a>
                      </p>';
            }                
        }
    }
?>
<?php
    if (!empty($accepted_data)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px;">New Friends!</h2>
<?php
        foreach ($accepted_data as $ac) {

            $profile_data   = array();
            $profile_data   = $users->userdata($ac['accepted_user']);

            $first = $profile_data['first_name'];
            $last = $profile_data['last_name'];
            $username = $profile_data['username'];
            $profile_pic = $profile_data['profile_picture'];
            if (empty($profile_pic)) {
                $profile_pic = 'http://petstapost.com/img/avatar-placeholder.png';
            }
            $profile_filter = $profile_data['profile_picture_filter'];

            if (!empty($first)) {
                echo '<p class="popup-comment" style="margin: 20px 30px;">
                        <a class="comment-link" href="'.$username.'">
                            <img src="'.$profile_pic.'" class="fr-img '.$profile_filter.'">&nbsp;'.$first.' '.$last.'
                        </a>
                      </p>';
            }

        }
    }
?>
<?php
    if (!empty($fr_data)) {

        echo '<span id="my-user-id" class="hidden">'.$_SESSION['id'].'</span>';
        echo '<span id="my-full-name" class="hidden">'. $_SESSION['name'].'</span>';
?>
    <h2 class="notify-head" style="margin: 20px 30px;">New Friend Requests!</h2>
<?php
        foreach ($fr_data as $fr) {

            $profile_data   = array();
            $profile_data   = $users->userdata($fr['friend_request_user']);

            $first = $profile_data['first_name'];
            $last = $profile_data['last_name'];
            $username = $profile_data['username'];
            $profile_pic = $profile_data['profile_picture'];
            if (empty($profile_pic)) {
                $profile_pic = 'http://petstapost.com/img/avatar-placeholder.png';
            }
            $profile_filter = $profile_data['profile_picture_filter'];
            $notify_id = $fr['notify_id'];

            echo '<span id="friend-user-'.$notify_id.'" class="hidden">'.$fr['friend_request_user'].'</span>';
            echo '<span id="their-full-name-'.$notify_id.'" class="hidden">'.$first.' '.$last.'</span>';

            if (!empty($first)) {
                echo '<p class="popup-comment" style="margin: 20px 30px;">
                        <a class="comment-link" href="'.$username.'">
                            <img src="'.$profile_pic.'" class="fr-img '.$profile_filter.'">&nbsp;'.$first.' '.$last.'
                        </a>
                        <span id="friend-message-'.$notify_id.'" class="accept-friend-button-msg hidden"></span>
                        <button id="accept-friend-'.$notify_id.'" class="btn btn-item-upload accept-friend-button-modal" href="javascript:void(0);">
                            <span id="friend-accept-modal-'.$notify_id.'"><i class="fa fa-check-circle"></i>&nbsp;Accept</span>
                        </button>
                      </p>
                      <p id="accepted-'.$notify_id.'" class="popup-comment" style="margin: 20px 30px;"></p><br>';
            }
?>
<script type="text/javascript">
    $('#accept-friend-<?php echo $notify_id?>').on('click', function(e) {
        var notify_id = <?php echo $notify_id?>;
        var my_id = $('#my-user-id').text();
        var their_id = $('#friend-user-'+notify_id).text();
        var their_name = $('#their-full-name-'+notify_id).text();
        var my_name = $('#my-full-name').text();
        var span = $('#friend-accept-modal-'+notify_id);
        var p = $('#accepted-'+notify_id);
        var message = $('#friend-message-'+notify_id);

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "util/accept-friend",
            data: "my_id="+my_id+"&their_id="+their_id+"&my_name="+my_name+"&their_name="+their_name,
            success: function(msg){
                span.parent().remove();
                message.removeClass('hidden');
                message.html(msg);
                p.html('Check out your new <a href="kibble/<?php echo $_SESSION["username"];?>" class="logo light comment-link">Kibble!</a>');
            },
            error: function() {
                span.html('There was an error.');
            }
        });

    });
</script>

<?php
        }
    }
?>
<?php
    if (!empty($comm_mention_data) || !empty($caption_mention_data)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px;">New Mentions!</h2>
<?php
        if (!empty($comm_mention_data)) {
            foreach ($comm_mention_data as $comm) {

                $profile_data   = array();
                $profile_data   = $users->userdata($comm['mention_user']);

                $first = $profile_data['first_name'];
                $last = $profile_data['last_name'];
                $username = $profile_data['username'];
                $item_pic = $comm['url'];
                $item_filter = $comm['filter'];
                $item_id = $comm['commented_item'];

                if (!empty($first)) {
                    echo '<p class="popup-comment notification-section">
                            <a href="petstapost.php?item_id='.$item_id.'" target="_blank">
                                <img src="'.$item_pic.'" class="comm-img '.$item_filter.'" style="clear:right; float: left;">&nbsp
                            </a>
                            <a class="comment-link" href="'.$username.'">
                                '.$first.' '.$last.'
                            </a> - "'.convertLinks($comm['comment']).'"
                          </p>';
                }

            }
        }

        if (!empty($caption_mention_data)) {
            foreach ($caption_mention_data as $cap) {

                $profile_data   = array();
                $profile_data   = $users->userdata($cap['mention_user']);

                $first = $profile_data['first_name'];
                $last = $profile_data['last_name'];
                $username = $profile_data['username'];
                $item_pic = $cap['url'];
                $item_filter = $cap['filter'];
                $item_id = $cap['item_id'];

                if (!empty($first)) {
                    echo '<p class="popup-comment notification-section">
                            <a href="petstapost.php?item_id='.$item_id.'" target="_blank">
                                <img src="'.$item_pic.'" class="comm-img '.$item_filter.'" style="clear:right; float: left;">&nbsp
                            </a>
                            <a class="comment-link" href="'.$username.'">
                                '.$first.' '.$last.'
                            </a> - "'.convertLinks($cap['caption']).'"
                          </p>';
                }

            }
        }
    }
?>
<?php
    if (!empty($comm_count)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px;">Comments on Your Posts!</h2>
<?php
        foreach ($comm_data as $comm) {

            $profile_data   = array();
            $profile_data   = $users->userdata($comm['commented_user']);

            $first = $profile_data['first_name'];
            $last = $profile_data['last_name'];
            $username = $profile_data['username'];
            $item_pic = $comm['url'];
            $item_filter = $comm['filter'];
            $item_id = $comm['commented_item'];

            if (!empty($comm['commented_user'])) {
                echo '<p class="popup-comment notification-section">
                        <a href="petstapost.php?item_id='.$item_id.'" target="_blank">
                            <img src="'.$item_pic.'" class="comm-img '.$item_filter.'" style="clear:right; float: left;">&nbsp
                        </a>
                         <a class="comment-link" href="'.$username.'">
                            '.$first.' '.$last.'
                        </a> - "'.convertLinks($comm['comment']).'"
                      </p>';
            }

        }
    }
?>
<?php
    if (!empty($fav_data)) {
?>
    <h2 class="notify-head" style="margin: 20px 30px;">People <i class="fa fa-heart"></i> Your Posts!</h2>
<?php
        foreach ($fav_data as $fav) {

            $fav_item = $notifications->get_favorite_item_notifications($user_id, $fav['liked_item']);

        //     $profile_data   = array();
        //     $profile_data   = $users->userdata($fav['liked_user']);

        //     $first = $profile_data['first_name'];
        //     $last = $profile_data['last_name'];
        //     $username = $profile_data['username'];
        //     $item_pic = $fav['url'];
        //     $item_filter = $fav['filter'];

        //     if (!empty($fav['liked_user'])) {
        //         echo '<p class="popup-comment" style="margin: 20px 30px;">
        //                 <img src="'.$item_pic.'" class="comm-img '.$item_filter.'">&nbsp
        //                 <a class="comment-link" href="'.$username.'">
        //                     '.$first.' '.$last.'</a> digs this one.
        //               </p>';
        //     }

        // }

            // foreach ($fav_item_data as $fav_item) {
                // $item_pic = $fav_item['url'];
                // $item_filter = $fav_item['filter'];

                $fav_count = $notifications->count_favorite_notifications($user_id, $fav['liked_item']);
                // $fav_count = count($fav_item_data);

                // echo '<pre>';
                // var_dump($fav_item);
                // echo '</pre>';

                if ($fav_count == 1) {

                    echo '<p class="popup-comment" style="margin: 20px 30px;">
                            <a href="petstapost.php?item_id='.$fav_item[0]["item_id"].'" target="_blank">
                                <img src="'.$fav_item[0]['url'].'" class="comm-img '.$fav_item[0]['filter'].'">&nbsp
                            </a>';

                    echo '<a class="comment-link" href="'.$fav_item[0]['username'].'">'.$fav_item[0]['first_name'].' ' . $fav_item[0]['last_name'].'</a>';

                    echo ' digs this.</p>';
                }

                if ($fav_count == 2) {
                    echo '<p class="popup-comment" style="margin: 20px 30px;">
                            <a href="petstapost.php?item_id='.$fav_item[0]["item_id"].'" target="_blank">
                                <img src="'.$fav_item[0]['url'].'" class="comm-img '.$fav_item[0]['filter'].'">&nbsp
                            </a>';

                    echo '<a class="comment-link" href="'.$fav_item[0]['username'].'">'.$fav_item[0]['first_name'].' ' . $fav_item[0]['last_name'].'</a>';

                    echo ' and <a href="'.$fav_item[1]['username'].'" class="comment-link">'.$fav_item[1]['first_name'].' ' . $fav_item[1]['last_name'].'</a>';

                    echo ' dig this.</p>';
                }

                if ($fav_count > 2 && $fav_count < 12) {
                    $count = ($fav_count-2);
                    echo '<p class="popup-comment two-favorites notification-section" style="margin: 20px 30px;">
                            <a href="petstapost.php?item_id='.$fav_item[0]["item_id"].'" target="_blank">
                                <img src="'.$fav_item[0]['url'].'" class="comm-img '.$fav_item[0]['filter'].'" style="clear:right; float: left;">&nbsp;
                            </a>';

                    echo '<a class="comment-link" href="'.$fav_item[0]['username'].'">'.$fav_item[0]['first_name'].' ' . $fav_item[0]['last_name'].'</a>';

                    echo ', <a href="'.$fav_item[1]['username'].'" class="comment-link">'.$fav_item[1]['first_name'].' ' . $fav_item[1]['last_name'].'</a>';

                    echo ' and <a href="javascript:void(0);" class="show-others comment-link">';

                        if ($count == 1) {
                            echo $count.' other</a>';
                        } else {
                            echo $count.' others</a>';
                        }

                    echo ' dig this.</p>';

                    echo '<p class="popup-comment all-favorites hidden notification-section" style="margin: 20px 30px;">
                            <a href="petstapost.php?item_id='.$fav_item[0]["item_id"].'" target="_blank">
                                <img src="'.$fav_item[0]['url'].'" class="comm-img '.$fav_item[0]['filter'].'" style="clear:right; float: left;">&nbsp;
                            </a>';

                    $new_fav_count = ($fav_count-1); 
                    for ($i=0; $i<$new_fav_count; $i++) {
                        echo '<a class="comment-link" href="'.$fav_item[$i]['username'].'">'.$fav_item[$i]['first_name'].' ' . $fav_item[$i]['last_name'].'</a>, ';
                    }
                        echo 'and <a class="comment-link" href="'.$fav_item[$new_fav_count]['username'].'">'.$fav_item[$new_fav_count]['first_name'].' ' . $fav_item[$new_fav_count]['last_name'].'</a>';
                        echo ' dig this.';

                    echo '</p>';
                }

                if ($fav_count >= 12) {

                    echo '<p class="popup-comment" style="margin: 20px 30px;">
                            <a href="petstapost.php?item_id='.$fav_item[0]["item_id"].'" target="_blank">
                                <img src="'.$fav_item[0]['url'].'" class="comm-img '.$fav_item[0]['filter'].'">&nbsp
                            </a>';

                    echo $fav_count.' people dig this.</p>';
                }
            // }
        }
    }

    echo '<script type="text/javascript">
            $("#clear-all").on("click", function(e) {

                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "http://petstapost.com/util/clear-notifications",
                    data: "user_id='.$_SESSION['id'].'",
                    success: function(){
                        $("#notification-container").html("<h2 class=\'notify-head\' style=\'margin: 20px 30px; text-align: center;\'>No new notifications</h2>");
                        $(".drop-notifications").addClass("hidden");
                        $(".notifications").addClass("hidden");
                        $(".mobile-notifications").addClass("hidden");
                    },
                    error: function() {
                        alert("There was an error.");
                    }
                });

            });
        </script>';
?>
</div>

<?php
    } else {
        // must be logged in
        exit();
    }

} else {
    // something went wrong
}
?>