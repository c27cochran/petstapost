<?php
    require_once 'header.php';

    if (isset($_GET['user'])) {
        $username = $_GET['user'];
    }

    if ($users->user_exists($username) && $username == $_SESSION['username']) {

        $kibble_data = $items->get_kibble($user_id);
        $kibble_count = count($kibble_data);

        function convertLinks($message) {
            $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="../$2">@$2</a>', '$1<a class="comment-link" href="../hashtag.php?hashtag=$2">#$2</a>'), $message);
            return $parsedMessage;
        }

        if (isset($_SESSION['id'])) {
            echo '<span id="user-id" class="hidden">'.$_SESSION['id'].'</span>';
            echo '<span id="my-name" class="hidden">'. $_SESSION['name'].'</span>';
        }

        if ($general->logged_in() === true && $users->verify($user_id) === true)  {
            $profile_data   = array();
            $user_id        = $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
            $profile_data   = $users->userdata($user_id);

            if (!empty($_SESSION['remember_me']) && $_SESSION['remember_me'] == 1) {
                $cookie_time = (3600 * 24 * 30); // 30 days
                
                if ($domain == "localhost") {
                    setrawcookie('Petsta', 'usr='.$username.'&code='.$profile_data['email_code'], time() + $cookie_time, '/petstapost', '', false);
                } elseif ($domain == "petstapost.com") {
                    setrawcookie('Petsta', 'usr='.$username.'&code='.$profile_data['email_code'], time() + $cookie_time, '/', $domain, false);
                }
            }
    ?>

    <!-- Intro Header if user is found -->
    <div class="intro-home">
        <div class="intro-body">
            <div class="container profile-container">
                <?php
                    if (empty($username)) {
                        if (!isset($_SESSION['username']) && empty($_SESSION['username'])) {
                            header('Location: ../account/logout');
                        } else {
                            header('Location: '.$_SESSION['username']);
                        }
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            if ($general->logged_in() === true && $users->verify($user_id) === true)  {
                        ?>
                            <div class="row profile-row mobile-only">
                                <form class="video-upload" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                    <div id="profile-item-file" class="btn btn-item-upload fileUpload">
                                        <i class="fi-photo"></i>&nbsp;Post a Photo or Video
                                        <input id="item-input" class="item-input upload" name="item_file" type="file" data-toggle="modal" data-target="#item-modal" />
                                    </div>
                                    <p class="video-time">(Video must be 30 seconds or less)</p>
                                    <div class="vid-caption-upload col-md-8 profile-item hidden">
                                        <h3 class="caption video-caption">Video Caption:</h3>
                                        <textarea autofocus maxlength="160" id="custom-video-caption" class="form-control" name="custom-video-caption" rows="2" style="resize:none"></textarea> 
                                        <button class="btn btn-primary video-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;Post</button>
                                    </div>
                                </form>
                                <br>
                            </div>
                        <?php
                            }
                        ?>
                        <div class="row profile-row">
                        <?php
                            if ($kibble_count >= 1) {
                                foreach ($kibble_data as $result) { 
                                    // if ($friends->check_friends($_SESSION['id'], $result['user_id']) === true) { 
                                        $caption = $result['caption'];
                                        $caption = substr($caption, 0, 20);

                                        echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';

                                        if (!empty($caption) && strlen($caption) >= 20) {
                                            $caption = $caption . '...';
                                        } else if (!empty($caption) && strlen($caption) < 20) {
                                            $caption = convertLinks($caption);
                                        }
                        ?>
                                    <div class="col-md-3"></div>
                                    <div id="kibble_item_<?php echo $result['item_id'];?>" class="col-md-6 profile-item">
                                        <h3 class="kibble-user">
                                            <?php echo $result['first_name'].' '.$result['last_name'];?>&nbsp;
                                            <span class="comment-time"><?php echo date('F jS', $result['time']).' at '.date('g:i a', $result['time']);?></span>
                                        </h3>
                                        <a href="../<?php echo $result['username'];?>">
                                            <img class="img-responsive kibble-profile <?php echo $result['profile_picture_filter'];?>" src="<?php echo $result['profile_picture'];?>">
                                        </a>
                                        <?php 
                                            if ($result['filter'] == 'video') { ?>
                                                <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%; margin-top: 22px;">
                                                    <a class="simple-ajax-popup-align-top" href="../util/get-item?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                                        <img class="img-responsive" src="../img/video-overlay.png">
                                                    </a>
                                                </div>
                                        <?php
                                            } else {
                                        ?>
                                            <a class="simple-ajax-popup-align-top" href="../util/get-item-feed?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                                <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>">
                                            </a>
                                        <?php
                                            }
                                        ?>
                                        <p>
                                        <?php 
                                            $fav_count = $favorites->count_favorites($result['item_id']);
                                            $comment_count = $comments->count_comments($result['item_id']);
                                            echo '<span id="user-id" class="hidden">'.$_SESSION['id'].'</span>';
                                            echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';
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
                                    <div class="show-comments-<?php echo $result['item_id'];?> hidden"></div>
                                    <script>
                                    $(document).ready(function() {
                                        var item_id = <?php echo $result['item_id'];?>;
                                        $.ajax({
                                                type: "GET",
                                                url: "../util/show-limited-comments-feed",
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
                        <?php
                                    // }
                                }
                            } else {
                                echo '<div class="col-md-9 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No friends have posted yet.</h1></div>';
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        } elseif ($general->logged_in() === false && empty($username)) {
                        header('Location: ../account/logout');
        }
    } else {
        require_once 'not-found.php';
    } 
    ?>

<?php
    require_once 'footer.php';
?>