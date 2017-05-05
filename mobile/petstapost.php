<?php

require __DIR__ .'/../core/init.php';

$item_exists = $items->item_exists($_GET['item_id']);

if (isset($_GET['item_id']) && $item_exists === true) {
    $item_id = $_GET['item_id'];
    $item_url = $items->fetch_info('url', 'item_id', $item_id);
    $og_caption = $items->fetch_info('caption', 'item_id', $item_id);

    $this_url = 'http://petstapost.com/petstapost.php?item_id='.$item_id.'';

    $onclick = "window.plugins.socialsharing.shareViaFacebook('".$og_caption."', null, '".$this_url."', function() {console.log('share ok')}, function(errormsg){alert(errormsg)})";
}

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="yappy.html?user=$2">@$2</a>', '$1<a class="comment-link" href="hashtag.html?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

echo '<div class="intro-home">
    <div class="intro-body">
        <div class="container profile-container">
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <button class="btn btn-primary" onclick="'.$onclick.'"><i class="fa fa-facebook fa-fw"></i>&nbsp;Share on Facebook</button>';

                    if (isset($_GET['item_id']) && $item_exists === true) {

                        $user_id = $items->fetch_info('user_id', 'item_id', $item_id);
                        $secured = $users->fetch_info('secured', 'user_id', $user_id);

                        $item_data = $items->get_one_item($item_id);

                        $caption = $item_data['caption'];
                        $item_id = $item_data['item_id'];
                        $url = $item_data['url'];


                        if ($item_data['filter'] == 'video') { 
                            $container_width = ($item_data['video_width'] + 40);

                        	echo '<div id="custom-content-'.$item_id.'" class="white-popup-block" style="max-width:520px; padding: 40px 20px 0 20px;">';

                                if (!empty($caption)) {

                            		echo '<h2 class="popup-caption" style="margin: 0 0 10px;">'.convertLinks($caption).'</h2>';
                                } else {
                                    echo '<br><br>';
                                }

                            echo '<script type="text/javascript">
                                if(window.innerWidth <= 550) {
                                    var video = document.getElementsByTagName("video")[0];
                                    video.height = 160;
                                    video.width = 240;
                                }
                            </script>
                            <video width="'.$item_data['video_width'].'" height="'.$item_data['video_height'].'" controls="">
                                <source id="mp4Video" src="'.$item_data['video_url'].'" type="'.$item_data['video_mime'].'" codecs="'.$item_data['audio_codec'].', '.$item_data['video_codec'].'">
                            </video>
                            <br>
                            <p>
                                <span id="fav-pop-up-'.$item_data['item_id'].'"></span>
                                <a id="item_'.$item_data['item_id'].'" href="javascript:void(0);" class="favorite right">';

                                        if ($favorites->already_favorited($item_data['item_id'], $user_id) === true) {
                                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                                        } else {
                                            echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                                        }

                                echo '<span id="fav-count-'.$item_data['item_id'].'" class="fav-count">'.$fav_count.'</span>
                                </a>

                            </p>
                            <br><br>';

                        } else { 

                        echo '<div id="custom-content-'.$item_id.'" class="white-popup-block" style="max-width:600px;">';

                                if (!empty($caption)) {

                            		echo '<h2 class="popup-caption" style="margin: 7px 20px 20px;">'.convertLinks($caption).'</h2>';
                                } else {
                                    echo '<br><br>';
                                }

                            echo '<img class="img-responsive '.$item_data['filter'].'" src="'.$url.'" alt="'.$item_data['name'].'"  style="padding: 0 7px;">
                            <br>
                            <p class="comment-like-container">';

                                $fav_count = $favorites->count_favorites($item_data['item_id']);
                                $comment_count = $comments->count_comments($item_data['item_id']);
                                if ($fav_count == '') {
                                    $fav_count = 0;
                                }

                                echo '<span class="popup-comment right logo">
                                    <i class="fa fa-heart"></i><span class="fav-count">&nbsp;'.$fav_count.'</span>
                                </span>
                            </p>
                            <br><br>
                            <div class="comment-like-container">';
                        }

                                $fav_data = $favorites->get_favorites($item_id);
                                $fav_count = count($fav_data);

                                if ($fav_count == 1) {
                                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                                    echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                    echo ' digs this.</p>';

                                }

                                if ($fav_count == 2) {
                                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                                    echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                    echo ' and <a href="'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

                                    echo ' dig this.</p>';

                                }

                                if ($fav_count > 2) {
                                    $count = ($fav_count-2);
                                    echo '<p class="popup-comment two-favorites"><i class="fa fa-heart"></i>&nbsp;';

                                    echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                    echo ', <a href="'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

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
                                        echo '<a class="comment-link" href="'.$fav_data[$i]['username'].'">'.$fav_data[$i]['first_name'].' ' . $fav_data[$i]['last_name'].'</a>, ';
                                    }
                                        echo 'and <a class="comment-link" href="'.$fav_data[$new_fav_count]['username'].'">'.$fav_data[$new_fav_count]['first_name'].' ' . $fav_data[$new_fav_count]['last_name'].'</a>';
                                        echo ' dig this.';

                                    echo '</p>';
                                }


                            echo '<br>
                            <div class="comment-data-container">';

                                $comm_data = $comments->get_comments($item_id);

                                foreach ($comm_data as $comm) {
                                    
                                    $first = $users->fetch_info('first_name', 'user_id', $comm['user_id']);
                                    $last = $users->fetch_info('last_name', 'user_id', $comm['user_id']);
                                    $username = $users->fetch_info('username', 'user_id', $comm['user_id']);

                                    echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
                                    echo '<p class="popup-comment"><a class="comment-link" href="'.$username.'">'.$first.' '.$last.'</a>: '.$comm['comment'].'</p>';
                                    echo '<br>';

                                }


                            echo '</div>
                        </div>
                    </div>';

                    } else { 
                        echo '<div class="col-md-3"></div>
                        <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;Post not found.</h1></div>';
                    }

                echo '</div>
            </div>
        </div>
    </div>
</div>';

