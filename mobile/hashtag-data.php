<?php
  require __DIR__ .'/../core/init.php';


  function convertLinks($message) {
      $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="yappy.html?user=$2">@$2</a>', '$1<a class="comment-link" href="hashtag.html?hashtag=$2">#$2</a>'), $message);
      return $parsedMessage;
  }

  if (isset($_POST['username']) && isset($_POST['hashtag'])) {
    $username = $_POST['username'];
    $hashtag = $_POST['hashtag'];
  } else {
    echo '<h2>No Hashtag Info</h2>';
    exit();
  }

  $profile_data   = array();
  $my_user_id  = $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
  $profile_data   = $users->userdata($my_user_id);

  echo '<header class="intro-home">
    <div class="intro-body">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="row profile-row">';

            if(!empty($hashtag)) {

              echo '<div class="col-md-3"></div>
              <div id="hashtag" class="col-md-6 profile-item">
                <h3 class="hashtag-name center">#'.$hashtag.'</h3>
              </div>';

              // Add percent symbols for LIKE in WHERE clauses
              $hashsearch = '%'.$hashtag.'%';

              $item_data = $items->get_item_hashtag($hashsearch);
              $item_count = count($item_data);
              
              if ($item_count >= 1) {
                  foreach ($item_data as $result) { 
                    $caption = $result['caption'];
                    $caption = substr($caption, 0, 20);

                    echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';

                    if (!empty($caption) && strlen($caption) >= 20) {
                        $caption = convertLinks($caption) . '...';
                    }

                  echo '<div class="col-md-3"></div>
                  <div id="profile_item_'.$result['item_id'].'" class="col-md-6 profile-item">
                    <h3 class="kibble-user">
                        '.$result['first_name'].' '.$result['last_name'].'&nbsp;
                        <span class="comment-time">'.date('F jS', $result['time']).' at '.date('g:i a', $result['time']).'</span>
                    </h3>
                    <a href="'.$result['username'].'">
                        <img class="img-responsive kibble-profile '.$result['profile_picture_filter'].'" src="'.$result['profile_picture'].'">
                    </a>';

                    if ($friends->check_friends($my_user_id, $result['user_id']) === false && $result['secured'] == 1 && $my_user_id != $result['user_id']) {
                      $caption = '';

                      echo '<h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>';

                    } else if ($result['filter'] == 'video') { 

                        echo '<div class="img-responsive" style="background:url('.$result['url'].') no-repeat; background-size: 100%;">
                            <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item='.$result['item_id'].'&user='.$my_user_id.'">
                                <img class="img-responsive" src="img/video-overlay.png">
                            </a>
                        </div>';

                    } else {                        

                        echo '<a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item='.$result['item_id'].'&user='.$my_user_id.'">
                            <img class="img-responsive '.$result['filter'].'" src="'.$result['url'].'" alt="'.$result['name'].'">
                        </a>';

                    }  

                    if ($friends->check_friends($my_user_id, $result['user_id']) === true && $result['secured'] == 1 && $my_user_id != $result['user_id']) {

                      echo '<p>';

                      $fav_count = $favorites->count_favorites($result['item_id']);
                      $comment_count = $comments->count_comments($result['item_id']);

                        echo '<span id="user-id" class="hidden">'.$my_user_id.'</span>';
                        echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';

                        echo '<span id="fav-pop-up-'.$result['item_id'].'"></span>
                        <a id="item_'.$result['item_id'].'" href="javascript:void(0);" class="favorite left">';
                                
                            if ($favorites->already_favorited($result['item_id'], $my_user_id) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }

                            echo '<span id="fav-count-'.$result['item_id'].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                        </a>
                        <a id="comment-link-'.$result['item_id'].'" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                            <i class="fa fa-comment-o"></i><span id="comment-count-'.$result['item_id'].'" class="comment-count">'.$comment_count.'</span>
                        </a>
                      </p>';

                    } else if ($result['secured'] == 0 || $my_user_id == $result['user_id']) {
                      echo '<p>';

                      $fav_count = $favorites->count_favorites($result['item_id']);
                      $comment_count = $comments->count_comments($result['item_id']);

                        echo '<span id="user-id" class="hidden">'.$my_user_id.'</span>';
                        echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';

                        echo '<span id="fav-pop-up-'.$result['item_id'].'"></span>
                        <a id="item_'.$result['item_id'].'" href="javascript:void(0);" class="favorite left">';
                                
                            if ($favorites->already_favorited($result['item_id'], $my_user_id) === true) {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-heart"></i>';
                            } else {
                                echo '<i id="fav-icon-'.$result['item_id'].'" class="fa fa-paw"></i>';
                            }

                            echo '<span id="fav-count-'.$result['item_id'].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                        </a>
                        <a id="comment-link-'.$result['item_id'].'" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                            <i class="fa fa-comment-o"></i><span id="comment-count-'.$result['item_id'].'" class="comment-count">'.$comment_count.'</span>
                        </a>
                      </p>';
                    }
                    
                    if (!empty($caption) && $fav_count < 100 && $comment_count < 100) {
                      echo '<p class="post-caption">'.convertLinks($caption).'</p>';
                    } else {
                      echo '<p class="post-caption hidden">no caption</p>';
                    }

                  echo '</div>
                    <div class="show-comments-'.$result['item_id'].' hidden"></div>
                      <script>
                      $(document).ready(function() {
                          var item_id = '.$result['item_id'].';
                          $.ajax({
                                  type: "GET",
                                  url: "http://petstapost.com/util/show-limited-comments-profile",
                                  data: "item_id="+item_id,
                                  success: function(msg){
                                      $(".show-comments-"+item_id).html(msg);
                                  },
                                  error: function() {
                                      $(".show-comments-"+item_id).html("<p>There was an error.</p>");
                                  }
                              });
                      });
                      </script>';

                  }
              }

              $item_comment_data = $comments->get_item_comment_hashtag($hashtag);
              $item_comment_count = count($item_comment_data);
              
              if ($item_comment_count >= 1) {
                  foreach ($item_comment_data as $result_comment) {
                    if ($result['item_id'] != $result_comment['item_id']) {

                      $caption_comment = $result_comment['caption'];
                      $caption_comment = substr($caption_comment, 0, 20);

                      echo '<span id="to-user-'.$result_comment['item_id'].'" class="hidden">'.$result_comment['user_id'].'</span>';

                      if (!empty($caption_comment) && strlen($caption_comment) >= 20) {
                          $caption_comment = convertLinks($caption_comment) . '...';
                      }

                      echo '<div class="col-md-3"></div>
                      <div id="profile_item_'.$result_comment['comment_id'].'" class="col-md-6 profile-item">
                        <h3 class="kibble-user">
                            '.$result_comment['first_name'].' '.$result_comment['last_name'].'&nbsp;
                            <span class="comment-time">'.date('F jS', $result_comment['time']).' at '.date('g:i a', $result_comment['time']).'</span>
                        </h3>
                        <a href="'.$result_comment['username'].'">
                            <img class="img-responsive kibble-profile '.$result_comment['profile_picture_filter'].'" src="'.$result_comment['profile_picture'].'">
                        </a>';

                      if ($friends->check_friends($my_user_id, $result_comment['poster_id']) === false && $result_comment['secured'] == 1) {
                        $caption_comment = '';

                        echo '<h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>';

                      } else if ($result_comment['filter'] == 'video') { 

                          echo '<div class="img-responsive" style="background:url('.$result_comment['url'].') no-repeat; background-size: 100%;">
                              <a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item='.$result_comment['item_id'].'&user='.$my_user_id.'">
                                  <img class="img-responsive" src="img/video-overlay.png">
                              </a>
                          </div>';

                      } else {                        

                          echo '<a class="simple-ajax-popup-align-top" href="http://petstapost.com/mobile/get-item.php?item='.$result_comment['item_id'].'&user='.$my_user_id.'">
                              <img class="img-responsive '.$result_comment['filter'].'" src="'.$result_comment['url'].'" alt="'.$result_comment['name'].'">
                          </a>';

                      } 

                    if ($friends->check_friends($my_user_id, $result_comment['poster_id']) === true && $result_comment['secured'] == 1) {

                        echo '<p>';
                        
                        $fav_count = $favorites->count_favorites($result_comment['item_id']);
                        $comment_count = $comments->count_comments($result_comment['item_id']);

                          echo '<span id="user-id" class="hidden">'.$my_user_id.'</span>';
                          echo '<span id="to-user-'.$result_comment['item_id'].'" class="hidden">'.$result_comment['user_id'].'</span>';

                          echo '<span id="fav-pop-up-'.$result_comment['item_id'].'"></span>
                          <a id="item_'.$result_comment['item_id'].'" href="javascript:void(0);" class="favorite left">';

                                  if ($favorites->already_favorited($result_comment['item_id'], $my_user_id) === true) {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-heart"></i>';
                                  } else {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-paw"></i>';
                                  }

                          echo '<span id="fav-count-'.$result_comment['item_id'].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                          </a>
                          <a id="comment-link-'.$result_comment['item_id'].'" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                              <i class="fa fa-comment-o"></i><span id="comment-count-'.$result_comment['item_id'].'" class="comment-count">'.$comment_count.'</span>
                          </a>
                        </p>';

                    } else if ($result_comment['secured'] == 0) {

                        echo '<p>';
                        
                        $fav_count = $favorites->count_favorites($result_comment['item_id']);
                        $comment_count = $comments->count_comments($result_comment['item_id']);

                          echo '<span id="user-id" class="hidden">'.$my_user_id.'</span>';
                          echo '<span id="to-user-'.$result_comment['item_id'].'" class="hidden">'.$result_comment['user_id'].'</span>';

                          echo '<span id="fav-pop-up-'.$result_comment['item_id'].'"></span>
                          <a id="item_'.$result_comment['item_id'].'" href="javascript:void(0);" class="favorite left">';

                                  if ($favorites->already_favorited($result_comment['item_id'], $my_user_id) === true) {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-heart"></i>';
                                  } else {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-paw"></i>';
                                  }

                          echo '<span id="fav-count-'.$result_comment['item_id'].'" class="fav-count">&nbsp;'.$fav_count.'</span>
                          </a>
                          <a id="comment-link-'.$result_comment['item_id'].'" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                              <i class="fa fa-comment-o"></i><span id="comment-count-'.$result_comment['item_id'].'" class="comment-count">'.$comment_count.'</span>
                          </a>
                        </p>';

                    }
                          
                    if (!empty($caption_comment) && $fav_count < 100 && $comment_count < 100) {

                      echo '<p class="post-caption">'.convertLinks($caption_comment).'</p>';

                    } else {

                      echo '<p class="post-caption hidden">no caption</p>';

                    }

                    echo '</div>
                    <div class="show-comments-'.$result_comment['item_id'].' hidden"></div>
                    <script>
                    $(document).ready(function() {
                        var item_id = '.$result_comment['item_id'].';
                        $.ajax({
                                type: "GET",
                                url: "http://petstapost.com/util/show-limited-comments-profile",
                                data: "item_id="+item_id,
                                success: function(msg){
                                    $(".show-comments-"+item_id).html(msg);
                                },
                                error: function() {
                                    $(".show-comments-"+item_id).html("<p>There was an error.</p>");
                                }
                            });
                    });
                    </script>';

                    }
                  }
              }

              if ($item_count == 0 && $item_comment_count == 0) {

                echo '<div class="col-md-3"></div>
                <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No hashtags for this yet.</h1></div>';

              }

            } else { 

              echo '<div class="col-md-3"></div>
              <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No hashtags for this yet.</h1></div>';
  
            }

            echo '</div>
          </div>
        </div>
      </div>
    </div>
  </header>';

  echo "
    <script>
        $('.simple-ajax-popup-align-top').magnificPopup({
            type: 'ajax',
            alignTop: true,
            overflowY: 'scroll'
        });

        $('a.div-scroll').bind('click', function(event) {
            var _anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(_anchor.attr('href')).offset().top - 60
            }, 400, 'easeInCirc');
            event.preventDefault();
        });

        $('#show-posts').on('click', function(e) {
            e.preventDefault();
            $('.display-items-area').removeClass('hidden');
            $('.display-friends-area').addClass('hidden');
            $('.display-pets-area').addClass('hidden');
        });

        $('#show-friends').on('click', function(e) {
            e.preventDefault();
            $('.display-friends-area').removeClass('hidden');
            $('.display-items-area').addClass('hidden');
            $('.display-pets-area').addClass('hidden');
        });

        $('#show-pets').on('click', function(e) {
            e.preventDefault();
            $('.display-pets-area').removeClass('hidden');
            $('.display-friends-area').addClass('hidden');
            $('.display-items-area').addClass('hidden');
        });
    </script>
    ";
