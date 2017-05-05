<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="../$2">@$2</a>', '$1<a class="comment-link" href="../hashtag.php?hashtag=$2">#$2</a>'), $message);
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

//throw HTTP error if group number is not valid
if(!is_numeric($group_number)){
    header('HTTP/1.1 500 Invalid number!');
    exit();
}

//get current starting point of records
$items_per_group = 8;
$position = ($group_number * $items_per_group);

//Limit our results within a specified range. 
$kibble_data = $items->get_kibble($_SESSION['id'], $position, $items_per_group);
$kibble_count = count($kibble_data);
$total_count = $items->get_kibble_group_count($_SESSION['id']);

    if ($kibble_count >= 1) {
        foreach ($kibble_data as $result) { 

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
                    <?php echo $result['first_name'].' '.$result['last_name'];?>&nbsp;<br>
                    <span class="comment-time"><?php echo date('F jS', $result['time']).' at '.date('g:i a', $result['time']);?></span>
                </h3>
                <a href="../<?php echo $result['username'];?>">
                    <img class="img-responsive kibble-profile <?php echo $result['profile_picture_filter'];?>" src="<?php echo $result['profile_picture'];?>">
                </a>
                <?php 
                    if ($result['filter'] == 'video') { ?>
                        <div class="img-responsive" style="background:url(<?php echo $result['url'];?>) no-repeat; background-size: 100%; margin-top: 22px;">
                            <a class="simple-ajax-popup-align-top" href="../util/get-item-feed?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                <img class="img-responsive" src="../img/video-overlay.png">
                            </a>
                        </div>
                <?php
                    } else {
                ?>
                    <a class="simple-ajax-popup-align-top" href="../util/get-item-feed?item_id=<?php echo $result['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                        <div class="img_wrapper">
                            <img class="img-responsive <?php echo $result['filter'];?>" src="<?php echo $result['url'];?>" alt="<?php echo $result['name'];?>" onload="imgLoaded(this)">
                        </div>
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

        }
    } else {
        echo '<div class="col-md-9 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No friends have posted yet.</h1></div>';
    }
?>
<?php 
    if ($kibble_count >= 1) { ?>
		<div class="col-md-3"></div>
        <div class="col-md-6 profile-item keep-scrolling">
        	<h1 class="no-items-found">
        		<i class="fa fa-long-arrow-down"></i>&nbsp;<br>Scroll down for more
    		</h1>
		</div>
        <div class="col-md-3 placeholder"></div>
        <div class="col-md-6 placeholder"></div>
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
</script>
