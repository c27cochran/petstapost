<div class="col-md-12 display-items-area">
    <div class="row profile-row">
    <?php

        $item_data = $items->get_items($user_id);
        $item_count = count($item_data);

        function convertLinks($message) {
            $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="$2">@$2</a>', '$1<a class="comment-link" href="hashtag.php?hashtag=$2">#$2</a>'), $message);
            return $parsedMessage;
        }
        
        if ($item_count >= 1) {
            foreach ($item_data as $result) { 
                $caption = $result['caption'];
                $caption = substr($caption, 0, 20);

                if (!empty($caption) && strlen($caption) >= 20) {
                    $caption = $caption . '...';
                } else if (!empty($caption) && strlen($caption) < 20) {
                    $caption = convertLinks($caption);
                }

                echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$user_id.'</span>';
    ?>
            <div id="profile_item_<?php echo $result['item_id'];?>" class="col-md-3 profile-item">
                <?php if ($general->logged_in() === false) { ?>
                    <a href="javascript:void(0);" class="hide-item-anchor"><i class="fa fa-times"></i></a>
                <?php } ?>
                <?php if ($general->logged_in() === true && $users->verify($user_id) === true) { 
                        $secured = $users->fetch_info('secured', 'user_id', $user_id);
                ?>
                    <li class="dropdown item-dropdown right">
                        <a href="javascript:void(0);" class="dropdown-toggle item-dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-caret-square-o-down caret-drop"></i>
                        </a>
                        <ul class="dropdown-menu item-dropdown-menu" role="menu">
                        <?php
                            if ($secured == 0)  {
                        ?>
                            <li>
                                <a href="petstapost.php?item_id=<?php echo $result['item_id'];?>">
                                    <i class="fa fa-users"></i>&nbsp;Share
                                </a>
                            </li>
                        <?php
                            } else {
                        ?>
                            <li>
                                <p class="public-to-share">
                                    Make Profile Public to Share This Post
                                </p>
                            </li>
                        <?php
                            }
                        ?>
                            <li class="divider"></li>
                            <li>
                                <button class="open-delete-form right delete-post">
                                    <i class="fa fa-trash"></i>&nbsp;Delete Post
                                </button>
                                <form class="hidden delete-post-form" method="post" action="util/delete-item?username=<?php echo $username.'&delete_item='.$result['item_id'].'&id='.$result['cdn_id'];?>">
                                    <p class="delete-confirm">Are you sure?</p>
                                    <button type="submit" class="right delete-post sure">
                                        <i class="fa fa-check-circle-o"></i>&nbsp;Yep
                                    </button>
                                    <button type="reset" class="do-not-delete-post left delete-post">
                                        <i class="fa fa-times-circle-o"></i>&nbsp;No
                                    </button>
                                </form>
                            </li>
                        </ul>
                  </li>
                  <?php 
                    if ($result['filter'] == 'video') { ?>
                        <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%; margin-top: 22px;">
                            <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>
                  <?php
                    } else {
                  ?>
                      <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                        <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>">
                      </a>
                  <?php
                    }
                  ?>
                <?php 
                } else if ($general->logged_in() === true && $users->verify($user_id) === false) { 
                    if ($result['filter'] == 'video') { 
                ?>
                        <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%;">
                            <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>
                <?php
                    } else {                        
                ?>
                        <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                            <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>">
                        </a>
                <?php
                    }

                } 
                ?>

                <?php if ($general->logged_in() === false) { 
                    if ($result['filter'] == 'video') { 
                ?>
                        <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%;">
                            <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>
                <?php
                    } else {                        
                ?>
                    <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">
                        <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>">
                    </a>
                <?php 
                    }
                } ?>
                <p>
                <?php 
                    if ($general->logged_in() === true && $friends->check_friends($_SESSION['id'], $user_id) === true)  {
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);
                ?>
                    <span id="fav-pop-up-<?php echo $result['item_id'];?>"></span>
                    <a id="item_<?php echo $result['item_id'];?>" href="javascript:void(0);" class="favorite left">
                        <?php
                            if ($favorites->already_favorited($result['item_id'], $_SESSION['id']) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }
                        ?>
                        <span id="fav-count-<?php echo $result['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                    </a>
                    <a id="comment-link-<?php echo $result['item_id'];?>" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                        <i class="fa fa-comment-o"></i><span id="comment-count-<?php echo $result['item_id'];?>" class="comment-count"><?php echo $comment_count;?></span>
                    </a>
                <?php
                    } elseif ($general->logged_in() === true) {
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);
                ?>
                    <span id="fav-pop-up-<?php echo $result['item_id'];?>"></span>
                    <a id="item_<?php echo $result['item_id'];?>" href="javascript:void(0);" class="favorite left">
                        <?php
                            if ($favorites->already_favorited($result['item_id'], $_SESSION['id']) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }
                        ?>
                        <span id="fav-count-<?php echo $result['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                    </a>
                    <a id="comment-link-<?php echo $result['item_id'];?>" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                        <i class="fa fa-comment-o"></i><span id="comment-count-<?php echo $result['item_id'];?>" class="comment-count"><?php echo $comment_count;?></span>
                    </a>
                <?php
                    } else {
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);
                ?>
                    <span class="favorite left">
                        <i class="fa fa-paw"></i><span class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                    </span>
                    
                    <span class="comment right">
                        <i class="fa fa-comment-o"></i><span class="comment-count"><?php echo $comment_count;?></span>
                    </span>
                <?php
                    }
                ?>
                </p>
                <?php
                    if (!empty($caption) && $fav_count < 100 && $comment_count < 100) {
                ?>
                <p class="post-caption"><?php echo $caption; ?></p>
                <?php
                    } else {
                ?>
                <p class="post-caption hidden">no caption</p>
                <?php
                    }
                ?>
            </div>
            <?php if ($general->logged_in() === true) { ?>
                <div class="show-comments-<?php echo $result['item_id'];?> hidden"></div>
                <script>
                $(document).ready(function() {
                    var item_id = <?php echo $result['item_id'];?>;
                    $.ajax({
                            type: "GET",
                            url: "util/show-limited-comments-profile",
                            data: "item_id="+item_id,
                            success: function(msg){
                                $('.show-comments-'+item_id).html(msg);
                            },
                            error: function() {
                                $('.show-comments-'+item_id).html('<p>There was an error.</p>');
                            }
                        });
                });
                </script>
            <?php } ?>
    <?php
            }
        } elseif ($general->logged_in() === true && $users->verify($user_id) === true  && $item_count < 1) {
            echo '<div class="col-md-9 profile-item"><h1 class="no-items-found"><i class="fa fa-arrow-up"></i>&nbsp;Post some pics to get started!</h1></div>';
        } else {
            echo '<div class="col-md-9 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No posts yet</h1></div>';
        }
    ?>
    </div>
</div>