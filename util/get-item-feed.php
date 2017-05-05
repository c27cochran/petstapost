<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="../$2">@$2</a>', '$1<a class="comment-link" href="../hashtag.php?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

if (isset($_GET['item_id']) && isset($_GET['user_id'])) {

	$item_id = $_GET['item_id'];
	$user_id = $_GET['user_id'];

	$item_data = $items->get_one_item($item_id);

    if ($general->logged_in() === true && $users->verify($user_id) === true)  {

        $caption = $item_data['caption'];
        $item_id = $item_data['item_id'];
        $url = $item_data['url'];

if ($item_data['filter'] == 'video') { 
            $container_width = ($item_data['video_width'] + 40);
?>
        <div id="custom-content-<?php echo $item_id;?>" class="white-popup-block" style="max-width:520px; margin: 20px auto; padding: 40px 20px 0 20px;">
            <?php
                if (!empty($caption)) {
            ?>
            <h2 class="popup-caption" style="margin: 0 0 10px;"><?php echo convertLinks($caption); ?></h2>
            <?php
                } else {
                    echo '<br><br>';
                }
            ?>
            <script type="text/javascript">
                if(window.innerWidth <= 550) {
                    var video = document.getElementsByTagName("video")[0];
                    video.height = 160;
                    video.width = 240;
                }
            </script>
            <video width="<?php echo $item_data['video_width'];?>" height="<?php echo $item_data['video_height'];?>" controls="">
                <source id="mp4Video" src="<?php echo $item_data['video_url'];?>" type="<?php echo $item_data['video_mime'];?>" codecs="<?php echo $item_data['audio_codec'];?>, <?php echo $item_data['video_codec'];?>">
            </video>
            <br>
            <p>
            <?php 
                if ($general->logged_in() === true && $friends->check_friends($_SESSION['id'], $user_id) === true)  {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                    <?php
                        if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                        } else {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                        }
                    ?>
                    <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                </a>
            <?php
                } elseif ($general->logged_in() === true) {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                    <?php
                        if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                        } else {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                        }
                    ?>
                    <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                </a>
            <?php
                } else {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span class="favorite right">
                    <i class="fa fa-paw"></i><span class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                </span>
            <?php
                }
            ?>
            </p>
            <br><br>
<?php
        } else { 
?>
        <div id="custom-content-<?php echo $item_id;?>" class="white-popup-block" style="max-width:600px;">
            <?php
                if (!empty($caption)) {
            ?>
            <h2 class="popup-caption" style="margin: 7px 20px 20px;"><?php echo convertLinks($caption); ?></h2>
            <?php
                } else {
                    echo '<br><br>';
                }
            ?>
            <img class="img-responsive <?php echo $item_data['filter'];?>" src="<?php echo $url;?>" alt="<?php echo $item_data['name'];?>" style="padding: 0 7px;">
            <br>
            <p class="comment-like-container">
            <?php 
                if ($general->logged_in() === true && $friends->check_friends($_SESSION['id'], $user_id) === true)  {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                    <?php
                        if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                        } else {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                        }
                    ?>
                    <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                </a>
            <?php
                } elseif ($general->logged_in() === true) {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                    <?php
                        if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                        } else {
                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                        }
                    ?>
                    <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                </a>
            <?php
                } else {
                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                    $comment_count = $comments->count_comments($item_data['item_id']);
            ?>
                <span class="favorite left">
                    <i class="fa fa-paw"></i><span class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                </span>
            <?php
                }
            ?>
            </p>
            <br><br>
            <div class="comment-like-container">
            <?php 
        }
                $fav_data = $favorites->get_favorites($item_id);
                $fav_count = count($fav_data);

                if ($fav_count == 1) {
                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="../'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                    echo ' digs this.</p>';

                }

                if ($fav_count == 2) {
                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="../'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                    echo ' and <a href="../'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

                    echo ' dig this.</p>';

                }

                if ($fav_count > 2) {
                    $count = ($fav_count-2);
                    echo '<p class="popup-comment two-favorites"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="../'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                    echo ', <a href="../'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

                    echo ' and <a href="javascript:void(0);" class="show-others comment-link">';

                        if ($count == 1) {
                            echo $count.' other</a>';
                        } else {
                            echo $count.' others</a>';
                        }

                    echo ' dig this.</p>';

                    echo '<p class="popup-comment all-favorites hidden"><i class="fa fa-heart"></i>&nbsp;';

                    $new_fav_count = ($fav_count-1); 
                    for ($i=0; $i<$new_fav_count; $i++) {
                        echo '<a class="comment-link" href="../'.$fav_data[$i]['username'].'">'.$fav_data[$i]['first_name'].' ' . $fav_data[$i]['last_name'].'</a>, ';
                    }
                        echo 'and <a class="comment-link" href="../'.$fav_data[$new_fav_count]['username'].'">'.$fav_data[$new_fav_count]['first_name'].' ' . $fav_data[$new_fav_count]['last_name'].'</a>';
                        echo ' dig this.';

                    echo '</p>';
                }

            ?>
            <br>
            <div class="comment-data-container">
            <?php

                $comm_data = $comments->get_comments($item_id);

                foreach ($comm_data as $comm) {
                    
                    $first = $users->fetch_info('first_name', 'user_id', $comm['user_id']);
                    $last = $users->fetch_info('last_name', 'user_id', $comm['user_id']);
                    $username = $users->fetch_info('username', 'user_id', $comm['user_id']);

                    if ($comments->already_commented($comm['comment_id'], $user_id)) {
                        echo '<div class="my-comment-'.$comm['comment_id'].'">';
                        echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
                        echo '<p class="popup-comment"><a class="comment-link" href="../'.$username.'">Me</a>: 
                                '.convertLinks($comm['comment']).'
                                <a id="delete-comment-'.$comm['comment_id'].'" class="delete-comment-link" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                              </p>';
                        echo '</div><br>';
                    } else {
                        echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
                        echo '<p class="popup-comment"><a class="comment-link" href="../'.$username.'">'.$first.' '.$last.'</a>: '.$comm['comment'].'</p>';
                        echo '<br>';
                    }

                }

            ?>
            </div>
            <div id="popover-content-<?php echo $item_id;?>">
                <div class="comment-load"></div>
                <div class="form-group">
                    <textarea class="form-control comment-box comment-text-<?php echo $item_id;?>" maxlength="160" placeholder="Add a comment…" onKeyUp="mention()"></textarea>
                </div><ul class="dropdown-menu" id="mention-results"></ul>
                <button id="comment-btn-<?php echo $item_id;?>" type="submit" class="btn btn-comment-get-item btn-block">Submit</button>
            </div>
            <br><br>
        </div>
    </div>
<?php

    }

}