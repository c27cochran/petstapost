<?php

require __DIR__ .'/../core/init.php';

function convertLinks($message) {
    $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="$2">@$2</a>', '$1<a class="comment-link" href="hashtag.php?hashtag=$2">#$2</a>'), $message);
    return $parsedMessage;
}

if (isset($_GET['item_id'])) {

	$item_id = $_GET['item_id'];
    $user_id = $_SESSION['id'];

    $comm_data = $comments->get_last_three_comments($item_id); 
    foreach ($comm_data as $comm) {
    	
    	$first = $users->fetch_info('first_name', 'user_id', $comm['user_id']);
    	$last = $users->fetch_info('last_name', 'user_id', $comm['user_id']);
    	$username = $users->fetch_info('username', 'user_id', $comm['user_id']);

        if ($comments->already_commented($comm['comment_id'], $user_id)) {
            echo '<div class="my-comment-'.$comm['comment_id'].'">';
            echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
            echo '<p><a class="comment-link" href="'.$username.'">Me</a>: 
                    '.convertLinks($comm['comment']).'
                    <a id="delete-comment-'.$comm['comment_id'].'" class="delete-comment-link" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                  </p>';
            echo '</div><br>';
        } else {
            echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
            echo '<p><a class="comment-link" href="'.$username.'">'.$first.' '.$last.'</a>: '.$comm['comment'].'</p>';
            echo '<br>';
        }
    }

    $comm_count = $comments->count_comments($item_id);

    if ($comm_count > 3) {
        echo '<a class="simple-ajax-popup comment-link" href="util/get-item?item_id='.$item_id.'&user_id='.$user_id.'">';
        echo '...View More</a><br><br>';
        echo "<script>
                $('.simple-ajax-popup').magnificPopup({
                    type: 'ajax',
                    alignTop: true,
                    overflowY: 'scroll'
                });
                $(document).on('click', '.simple-ajax-popup', function(e) {
                    e.preventDefault();
                    $('[data-toggle=\"popover\"]').popover('hide');
                });
            </script>";
    }

} else {
	echo 'Oops...something went wrong.';
}
