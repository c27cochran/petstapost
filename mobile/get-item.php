<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="yappy.html?user=$2">@$2</a>', '$1<a class="comment-link" href="hashtag.html?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

if (isset($_GET["item"]) && isset($_GET["user"])) {

	$item_id = $_GET["item"];
	$user_id = $_GET["user"];
    $verified = $_GET["verified"];

	$item_data = $items->get_one_item($item_id);


        $caption = $item_data["caption"];
        $item_id = $item_data["item_id"];
        $url = $item_data["url"];


        if ($item_data["filter"] == 'video') { 
            $container_width = ($item_data["video_width"] + 40);

            echo '<div id="custom-content-'.$item_id.'" class="white-popup-block" style="max-width:520px; padding: 40px 20px 0 20px;">';
                if (!empty($caption)) {
                    if ($verified == '1')  {
                        echo '<h2 class="popup-caption my-caption" style="margin: 0 0 10px;">'.convertLinks($caption).'
                                <a href="javascript:void(0);" class="edit-caption-pencil comment-link">
                                    <i class="fa fa-pencil"></i>
                                </a>
                              </h2>
                              <textarea maxlength="160" id="edit-caption-'.$item_data["item_id"].'" autofocus class="form-control edit-caption-textarea hidden">'.$caption.'</textarea>
                              <button type="submit" class="btn btn-primary btn-edit-caption btn-block hidden">
                                Change Caption
                              </button>';
                    } else {
                        echo '<h2 class="popup-caption" style="margin: 0 0 10px;">'.convertLinks($caption).'</h2>';
                    }
                    
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
                    <video width="'.$item_data["video_width"].'" height="'.$item_data["video_height"].'" controls="">
                        <source id="mp4Video" src="'.$item_data["video_url"].'" type="'.$item_data["video_mime"].'" codecs="'.$item_data["audio_codec"].', '.$item_data["video_codec"].'">
                    </video>
                    <br>
                    <p>';

                    $fav_count = $favorites->count_favorites($item_data["item_id"]);
                    $comment_count = $comments->count_comments($item_data["item_id"]);

            echo '<span id="fav-pop-up-'.$item_data["item_id"].'"></span>
                    <a id="item_'.$item_data["item_id"].'" href="javascript:void(0);" class="favorite right">';
                            if ($favorites->already_favorited($item_data["item_id"], $user_id) === true) {
                                echo '<i id="fav-icon-'.$item_data["item_id"].'" class="fa fa-heart shadow"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$item_data["item_id"].'" class="fa fa-paw shadow"></i>';
                            }
                        echo '<span id="fav-count-'.$item_data["item_id"].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                    </a>
                </p>
                <br><br>';

        } else { 

            echo '<div id="custom-content-'.$item_id.'" class="white-popup-block" style="max-width:600px;">';

            if (!empty($caption)) {
                if ($verified == '1')  {
                    echo '<h2 class="popup-caption my-caption" style="margin: 7px 20px 20px;">'.convertLinks($caption).'
                            <a href="javascript:void(0);" class="edit-caption-pencil comment-link">
                                <i class="fa fa-pencil"></i>
                            </a>
                          </h2>
                          <textarea maxlength="160" autofocus id="edit-caption-'.$item_data["item_id"].'" class="form-control edit-caption-textarea hidden">'.$caption.'</textarea>
                          <button type="submit" class="btn btn-primary btn-edit-caption btn-block hidden">
                            Change Caption
                          </button>';
                } else {
                    echo '<h2 class="popup-caption" style="margin: 7px 20px 20px;">'.convertLinks($caption).'</h2>';
                }
            } else {
                echo '<br><br>';
            }
            
            echo '<img class="img-responsive '.$item_data["filter"].'" src="'.$url.'" alt="'.$item_data["name"].'"  style="padding: 0 7px;">
                <br>
                <p class="comment-like-container">';

                $fav_count = $favorites->count_favorites($item_data["item_id"]);
                $comment_count = $comments->count_comments($item_data["item_id"]);

                echo '<span id="fav-pop-up-'.$item_data["item_id"].'"></span>
                    <a id="item_'.$item_data["item_id"].'" href="javascript:void(0);" class="favorite right">';

                            if ($favorites->already_favorited($item_data["item_id"], $user_id) === true) {
                                echo '<i id="fav-icon-'.$item_data["item_id"].'" class="fa fa-heart shadow"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$item_data["item_id"].'" class="fa fa-paw shadow"></i>';
                            }

                        echo '<span id="fav-count-'.$item_data["item_id"].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                    </a>
                </p>
                <br><br>
                <div class="comment-like-container">';
        }

                $fav_data = $favorites->get_favorites($item_id);
                $fav_count = count($fav_data);

                if ($fav_count == 1) {
                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="'.$fav_data[0]["username"].'">'.$fav_data[0]["first_name"].' ' . $fav_data[0]["last_name"].'</a>';

                    echo ' digs this.</p>';

                }

                if ($fav_count == 2) {
                    echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="'.$fav_data[0]["username"].'">'.$fav_data[0]["first_name"].' ' . $fav_data[0]["last_name"].'</a>';

                    echo ' and <a href="'.$fav_data[1]["username"].'" class="comment-link">'.$fav_data[1]["first_name"].' ' . $fav_data[1]["last_name"].'</a>';

                    echo ' dig this.</p>';

                }

                if ($fav_count > 2) {
                    $count = ($fav_count-2);
                    echo '<p class="popup-comment two-favorites"><i class="fa fa-heart"></i>&nbsp;';

                    echo '<a class="comment-link" href="'.$fav_data[0]["username"].'">'.$fav_data[0]["first_name"].' ' . $fav_data[0]["last_name"].'</a>';

                    echo ', <a href="'.$fav_data[1]["username"].'" class="comment-link">'.$fav_data[1]["first_name"].' ' . $fav_data[1]["last_name"].'</a>';

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
                        echo '<a class="comment-link" href="'.$fav_data[$i]["username"].'">'.$fav_data[$i]["first_name"].' ' . $fav_data[$i]["last_name"].'</a>, ';
                    }
                        echo 'and <a class="comment-link" href="'.$fav_data[$new_fav_count]["username"].'">'.$fav_data[$new_fav_count]["first_name"].' ' . $fav_data[$new_fav_count]["last_name"].'</a>';
                        echo ' dig this.';

                    echo '</p>';
                }

            echo '<br>
                <div class="comment-data-container">';

                $comm_data = $comments->get_comments($item_id);

                foreach ($comm_data as $comm) {
                    
                    $first = $users->fetch_info('first_name', 'user_id', $comm["user_id"]);
                    $last = $users->fetch_info('last_name', 'user_id', $comm["user_id"]);
                    $username = $users->fetch_info('username', 'user_id', $comm["user_id"]);

                    if ($comments->already_commented($comm["comment_id"], $user_id)) {
                        echo '<div class="my-comment-'.$comm["comment_id"].'">';
                        echo '<p class="comment-time">'.date('F jS', $comm["time"]).' at '.date('g:i a', $comm["time"]).'</p>';
                        echo '<p class="popup-comment"><a class="comment-link" href="'.$username.'">Me</a>: 
                                '.convertLinks($comm["comment"]).'
                                <a id="delete-comment-'.$comm["comment_id"].'" class="delete-comment-link" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                              </p>';
                        echo '</div><br>';
                    } else {
                        echo '<p class="comment-time">'.date('F jS', $comm["time"]).' at '.date('g:i a', $comm["time"]).'</p>';
                        echo '<p class="popup-comment"><a class="comment-link" href="'.$username.'">'.$first.' '.$last.'</a>: '.$comm["comment"].'</p>';
                        echo '<br>';
                    }

                }

            echo '</div>
            <div id="popover-content-'.$item_id.'">
                <div class="comment-load"></div>
                <div class="form-group">
                    <textarea class="form-control comment-box comment-text-'.$item_id.'" maxlength="160" placeholder="Add a comment…" onKeyUp="mention()"></textarea>
                </div><ul class="dropdown-menu" id="mention-results"></ul>
                <button id="comment-btn-'.$item_id.'" type="submit" class="btn btn-comment-get-item btn-block">Submit</button>
            </div>
            <br><br>
        </div>
    </div>';

    echo '
        <script type="text/javascript">
            $(".edit-caption-pencil").on("click", function(e) {
                e.preventDefault();
                $(".my-caption").addClass("hidden");
                $(".btn-edit-caption").removeClass("hidden");
                $(".edit-caption-textarea").removeClass("hidden");
            });

            $(".btn-edit-caption").on("click", function(e) {
                e.preventDefault();

                var item_id = "'.$item_id.'";
                var user_id = "'.$user_id.'";
                var caption = $("#edit-caption-"+item_id).val();
                var caption_hash = caption.replace(/\#/g, "%23");

                $.ajax({
                    type: "POST",
                    url: "http://petstapost.com/util/update-caption",
                    data: "item_id="+item_id+"&user_id="+user_id+"&caption="+caption_hash,
                    success: function(msg){
                        $(".my-caption").removeClass("hidden");
                        $(".btn-edit-caption").addClass("hidden");
                        $(".edit-caption-textarea").addClass("hidden");
                        $(".my-caption").html(msg);
                    },
                    error: function() {
                        $(".my-caption").html("There was an error.");
                    }
                });

            });
        </script>';


}