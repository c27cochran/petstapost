<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="yappy.html?user=$2">@$2</a>', '$1<a class="comment-link" href="hashtag.html?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

?>
<script type="text/javascript">
$(function() {

    $('.simple-ajax-popup-align-top').magnificPopup({
        type: 'ajax',
        alignTop: true,
        overflowY: 'scroll'
    });
});
</script>

<?php
//sanitize post value
$group_number = filter_var($_GET['group_no'], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
$my_user_id = $_GET['my_user'];
$their_user_id = $_GET['their_user'];
$verified = $_GET['verified'];

$username  = $users->fetch_info('username', 'user_id', $my_user_id);

//throw HTTP error if group number is not valid
if(!is_numeric($group_number)){
    header('HTTP/1.1 500 Invalid number!');
    exit();
}

//get current starting point of records
$items_per_group = 5;
$position = ($group_number * $items_per_group);

//Limit our results within a specified range. 
$item_data = $items->get_items($their_user_id, $position, $items_per_group);
$item_count = count($item_data);
$total_count = $items->get_item_group_count($their_user_id);

    if ($item_count >= 1) {
            foreach ($item_data as $result) { 
                $caption = $result['caption'];
                $caption = substr($caption, 0, 20);

                if (!empty($caption) && strlen($caption) >= 20) {
                    $caption = $caption . '...';
                } else if (!empty($caption) && strlen($caption) < 20) {
                    $caption = convertLinks($caption);
                }

                echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$their_user_id.'</span>';
    ?>
            <div id="profile_item_<?php echo $result['item_id'];?>" class="col-md-3 profile-item profile-item-no-margin">
                <?php if ($verified == '1') { 
                        $secured = $users->fetch_info('secured', 'user_id', $their_user_id);
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
                                <a href="petstapost.html?item_id=<?php echo $result['item_id'];?>">
                                    <i class="fa fa-users"></i>&nbsp;Share
                                </a>
                            </li>
                        <?php
                            }
                        ?>
                            <li class="divider"></li>
                            <li>
                                <button class="open-delete-form right delete-post">
                                    <i class="fa fa-trash"></i>&nbsp;Delete Post
                                </button>
                                <form class="hidden delete-post-form">
                                    <div id="delete_item_id" class="hidden"><?php echo $result['item_id'];?></div>
                                    <div id="cdn_id" class="hidden"><?php echo $result['cdn_id'];?></div>
                                    <p class="delete-confirm">Are you sure?</p>
                                    <button id="delete-post-yes" type="submit" class="right delete-post sure">
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
                            <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item=<?php echo $result['item_id'];?>&user=<?php echo $my_user_id;?>&verified=1">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>
                  <?php
                    } else {
                  ?>
                      <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item=<?php echo $result['item_id'];?>&user=<?php echo $my_user_id;?>&verified=1">
                        <div class="img_wrapper">
                            <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>" onload="imgLoaded(this)">
                        </div>
                      </a>
                  <?php
                    }
                  ?>
                <?php 
                } else if ($verified == '0') { 
                    if ($result['filter'] == 'video') { 
                ?>
                        <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%;">
                            <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item=<?php echo $result['item_id'];?>&user=<?php echo $my_user_id;?>">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>
                <?php
                    } else {                        
                ?>
                        <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item=<?php echo $result['item_id'];?>&user=<?php echo $my_user_id;?>">
                            <div class="img_wrapper">
                                <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>" onload="imgLoaded(this)">
                            </div>
                        </a>
                <?php
                    }

                } 
                ?>
                <p>
                <?php 
                    if ($friends->check_friends($my_user_id, $their_user_id) === true || $my_user_id == $their_user_id)  {
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);
                ?>
                    <span id="fav-pop-up-<?php echo $result['item_id'];?>"></span>
                    <a id="item_<?php echo $result['item_id'];?>" href="javascript:void(0);" class="favorite left">
                        <?php
                            if ($favorites->already_favorited($result['item_id'], $my_user_id) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }
                        ?>
                        <span id="fav-count-<?php echo $result['item_id'];?>" class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                    </a>
                    <a id="comment-link-<?php echo $result['item_id'];?>" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                        <i class="fa fa-comment-o"></i><span id="comment-count-<?php echo $result['item_id'];?>" class="comment-count"><?php echo $comment_count;?></span>
                    </a>
                <?php
                    } else {
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);
                ?>
                    <span id="fav-pop-up-<?php echo $result['item_id'];?>"></span>
                    <a id="item_<?php echo $result['item_id'];?>" href="javascript:void(0);" class="favorite left">
                        <?php
                            if ($favorites->already_favorited($result['item_id'], $my_user_id) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }
                        ?>
                        <span id="fav-count-<?php echo $result['item_id'];?>" class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                    </a>
                    <a id="comment-link-<?php echo $result['item_id'];?>" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                        <i class="fa fa-comment-o"></i><span id="comment-count-<?php echo $result['item_id'];?>" class="comment-count"><?php echo $comment_count;?></span>
                    </a>
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
            <div class="show-comments-<?php echo $result['item_id'];?> hidden"></div>
            <script>
            $(document).ready(function() {
                var item_id = <?php echo $result['item_id'];?>;
                $.ajax({
                        type: "GET",
                        url: "http://petstapost.com/util/show-limited-comments-profile",
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
            }
        } elseif ($verified == '1' && $item_count < 1) {
            echo '<div class="col-md-9 profile-item profile-item-no-margin"><h1 class="no-items-found"><i class="fa fa-arrow-up"></i>&nbsp;Post some pics to get started!</h1></div>';
        } else {
            echo '<div class="col-md-9 profile-item profile-item-no-margin"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No posts yet</h1></div>';
        }

?>

    <?php if ($item_count >= 1) { ?>
        <!-- <div class="col-md-3 profile-item keep-scrolling">
        	<h1 class="no-items-found">
        		<i class="fa fa-long-arrow-down"></i>&nbsp;<br>Scroll down for more
    		</h1>
		</div> -->
        <div class="col-md-3 profile-item profile-item-no-margin keep-scrolling">
            <?php if ($verified == '1') { ?>
                <li class="dropdown item-dropdown right">
                    <a href="javascript:void(0);" class="dropdown-toggle item-dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-spinner caret-drop"></i>
                    </a>
                </li>
            <?php
                }
            ?>
            <img class="img-responsive" src="img/scroll-down.jpg" alt="scroll down for more">
            <p>
                <span class="favorite left">
                    <i class="fa fa-paw"></i><span class="fav-count"></span>
                </span>
                
                <span class="comment right">
                    <i class="fa fa-comment-o"></i><span class="comment-count"></span>
                </span>
            </p>
            <p class="post-caption hidden"><i class="fa fa-angle-down"></i></p>
        </div>
    <?php
        }
    ?>
</div>

<script type="text/javascript">
    $("[data-toggle=popover]").popover({
        html: true, 
        content: function() {

            var item_id = $(this).attr('id').replace('comment-link-', '');
            var html = $('.show-comments-'+item_id).html();
            
            return '<div id="popover-content-'+item_id+'">'+
                    '<div class="comment-load">'+html+'</div>' +
                    '<div class="form-group">'+
                        '<textarea maxlength="160" autofocus class="form-control comment-box comment-text-'+item_id+'" placeholder="Add a comment…" onKeyUp="mention()"></textarea>'+
                    '</div><ul class="dropdown-menu" id="mention-results"></ul>'+
                    '<button id="comment-btn-'+item_id+'" type="submit" class="btn btn-comment btn-block">Submit</button></div>';
        }
    });

    $('.register-modal-launcher').on('click', function(e) {
        e.preventDefault();
        $('#loginModalLabel').hide();
        $('#login-container').hide();
        $('#createModalLabel').show();
        $('#register-container').show();
        $('#username_register').focus();
    });

    $('#secure-profile').on('click', function(e) {
        e.preventDefault();
        console.log('secured');
        var span = $('#secure-text');
        $.ajax({
            type: "POST",
            data: "user="+<?php echo $my_user_id;?>,
            url: "http://petstapost.com/mobile/secure-profile.php",
            success: function(msg){
                span.html(msg);
                location.reload();
            },
            error: function() {
                // Do nothing
            }
        });
    });

    $('#open-profile').on('click', function(e) {
        e.preventDefault();
        console.log('open');
        var span = $('#secure-text');
        $.ajax({
            type: "POST",
            data: "user="+<?php echo $my_user_id;?>,
            url: "http://petstapost.com/mobile/remove-security.php",
            success: function(msg){
                span.html(msg);
                location.reload();
            },
            error: function() {
                // Do nothing
            }
        });
    });

    $(".open-delete-form").on('click', function(e) {
        e.stopPropagation();
        $(".open-delete-form").addClass("hidden");
        $(".delete-post-form").removeClass("hidden");
    });

    $(".do-not-delete-post").on('click', function(e) {
        e.preventDefault();
        $(".dropdown").removeClass("open");
        $(".delete-post-form").addClass("hidden");
        $(".open-delete-form").removeClass("hidden");
    });

    $("#delete-post-yes").on('click', function(e) {
        e.preventDefault();
        var username = "<?php echo $username;?>";
        var delete_item = $('#delete_item_id').text();
        var cdn_id = $('#cdn_id').text();

        $.ajax({
            type: "POST",
            url: 'http://petstapost.com/mobile/delete-item.php',
            data: 'username='+username+'&delete_item='+delete_item+'&id='+cdn_id,
            success: function(msg) {
                if (msg.indexOf("Deleted") > -1) {
                    $('#profile_item_'+delete_item).html('<h1 class="no-items-found">Post has been deleted</h1>');
                } else if (msg.indexOf("Not") > -1) {
                    $('#profile_item_'+delete_item).html('<p class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i>&nbsp;Sorry! Post was not deleted. Please try again.</p>');
                } else if (msg.indexOf("Error")) {
                    alert('There was an error trying to delete your post');
                }
            },
            error: function() {
                alert('There was an error.');
            }
        });

    });
</script>
