<?php
  require_once 'inc/search/search-header.php';
  function convertLinks($message) {
      $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="$2">@$2</a>', '$1<a class="comment-link" href="hashtag.php?hashtag=$2">#$2</a>'), $message);
      return $parsedMessage;
  }
?>
  <header class="intro-home">
    <div class="intro-body">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="row profile-row">
          <?php
            if(isset($_GET['hashtag']) && !empty($_GET['hashtag'])) {
              $hashtag = trim($_GET['hashtag']);
          ?>
              <div class="col-md-3"></div>
              <div id="hashtag" class="col-md-6 profile-item">
                <?php echo '<h3 class="hashtag-name center">#'.$hashtag.'</h3>';?>
              </div>
          <?php
              // Add percent symbols for LIKE in WHERE clauses
              $hashtag = '%'.trim($_GET['hashtag']).'%';

              $item_data = $items->get_item_hashtag($hashtag);
              $item_count = count($item_data);
              
              if ($item_count >= 1) {
                  foreach ($item_data as $result) { 
                    $caption = $result['caption'];
                    $caption = substr($caption, 0, 20);

                    echo '<span id="to-user-'.$result['item_id'].'" class="hidden">'.$result['user_id'].'</span>';

                    if (!empty($caption) && strlen($caption) >= 20) {
                        $caption = convertLinks($caption) . '...';
                    }
          ?>
                  <div class="col-md-3"></div>
                  <div id="profile_item_<?php echo $result['item_id'];?>" class="col-md-6 profile-item">
                    <h3 class="kibble-user">
                        <?php echo $result['first_name'].' '.$result['last_name'];?>&nbsp;
                        <span class="comment-time"><?php echo date('F jS', $result['time']).' at '.date('g:i a', $result['time']);?></span>
                    </h3>
                    <a href="<?php echo $result['username'];?>">
                        <img class="img-responsive kibble-profile <?php echo $result['profile_picture_filter'];?>" src="<?php echo $result['profile_picture'];?>">
                    </a>
                    <?php 
                        if ($general->logged_in() === true) { 
                          if ($users->verify($result['user_id']) === false && $friends->check_friends($_SESSION['id'], $result['user_id']) === false && $result['secured'] == 1) {
                            $caption = '';
                      ?>
                          <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                      <?php 
                        } else if ($result['filter'] == 'video') { 
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

                      <?php 
                      if ($general->logged_in() === false) { 
                        if ($result['secured'] == 1) {
                          $caption = '';
                      ?>
                          <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                      <?php 
                        } else if ($result['filter'] == 'video') { 
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
                        $fav_count = $favorites->count_favorites($result['item_id']);
                        $comment_count = $comments->count_comments($result['item_id']);

                        if ($general->logged_in() === true) {
                          if ($users->verify($result['user_id']) === false && $friends->check_friends($_SESSION['id'], $result['user_id']) === false && $result['secured'] == 1) {

                          } else {
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
                          }
                        }
                        if (!empty($caption) && $fav_count < 100 && $comment_count < 100) {
                    ?>
                    <p class="post-caption"><?php echo convertLinks($caption); ?></p>
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
          <?php
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
            ?>
                    <div class="col-md-3"></div>
                    <div id="profile_item_<?php echo $result_comment['comment_id'];?>" class="col-md-6 profile-item">
                      <h3 class="kibble-user">
                          <?php echo $result_comment['first_name'].' '.$result_comment['last_name'];?>&nbsp;
                          <span class="comment-time"><?php echo date('F jS', $result_comment['time']).' at '.date('g:i a', $result_comment['time']);?></span>
                      </h3>
                      <a href="<?php echo $result_comment['username'];?>">
                          <img class="img-responsive kibble-profile <?php echo $result_comment['profile_picture_filter'];?>" src="<?php echo $result_comment['profile_picture'];?>">
                      </a>
                      <?php 
                        if ($general->logged_in() === true) { 
                          if ($users->verify($result_comment['poster_id']) === false && $friends->check_friends($_SESSION['id'], $result_comment['poster_id']) === false && $result_comment['secured'] == 1) {
                          $caption_comment = '';
                      ?>
                          <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                      <?php 
                        } else if ($result_comment['filter'] == 'video') { 
                      ?>
                              <div class="img-responsive" style="background:url(<?php echo $result_comment['url'];?>) no-repeat; background-size: 100%;">
                                  <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result_comment['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                      <img class="img-responsive" src="img/video-overlay.png">
                                  </a>
                              </div>
                      <?php
                          } else {                        
                      ?>
                              <a class="simple-ajax-popup-align-top" href="util/get-item?item_id=<?php echo $result_comment['item_id'];?>&user_id=<?php echo $_SESSION['id'];?>">
                                  <img class="img-responsive <?php echo $result_comment['filter'];?>" src="<?php echo $result_comment['url'];?>" alt="<?php echo $result_comment['name'];?>">
                              </a>
                      <?php
                          }

                      } 
                      ?>

                      <?php 
                      if ($general->logged_in() === false) { 
                        if ($result_comment['secured'] == 1) {
                          $caption_comment = '';
                      ?>
                          <h1 class="no-items-found"><i class="fa fa-lock"></i>&nbsp;User Profile is Secured.</h1>
                      <?php 
                        } else if ($result_comment['filter'] == 'video') { 
                      ?>
                              <div class="img-responsive" style="background:url(<?php echo $result_comment['url'];?>) no-repeat; background-size: 100%;">
                                  <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">
                                      <img class="img-responsive" src="img/video-overlay.png">
                                  </a>
                              </div>
                      <?php
                          } else {                        
                      ?>
                          <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">
                              <img class="img-responsive <?php echo $result_comment['filter'];?>" src="<?php echo $result_comment['url'];?>" alt="<?php echo $result_comment['name'];?>">
                          </a>
                      <?php 
                          }
                      } ?>
                      <p>
                      <?php 
                          $fav_count = $favorites->count_favorites($result_comment['item_id']);
                          $comment_count = $comments->count_comments($result_comment['item_id']);
                          if ($general->logged_in() === true) {
                            if ($users->verify($result_comment['poster_id']) === false && $friends->check_friends($_SESSION['id'], $result_comment['poster_id']) === false && $result_comment['secured'] == 1) {

                            } else {
                              echo '<span id="user-id" class="hidden">'.$_SESSION['id'].'</span>';
                              echo '<span id="to-user-'.$result_comment['item_id'].'" class="hidden">'.$result_comment['user_id'].'</span>';
                      ?>
                          <span id="fav-pop-up-<?php echo $result_comment['item_id'];?>"></span>
                          <a id="item_<?php echo $result_comment['item_id'];?>" href="javascript:void(0);" class="favorite left">
                              <?php
                                  if ($favorites->already_favorited($result_comment['item_id'], $_SESSION['id']) === true) {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-heart"></i>';
                                  } else {
                                      echo '<i id="fav-icon-'.$result_comment['item_id'].'" class="fa fa-paw"></i>';
                                  }
                              ?>
                              <span id="fav-count-<?php echo $result_comment['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                          </a>
                          <a id="comment-link-<?php echo $result_comment['item_id'];?>" href="javascript:void(0);" class="comment right" data-placement="left" data-toggle="popover" data-container="body" type="button" data-html="true">
                              <i class="fa fa-comment-o"></i><span id="comment-count-<?php echo $result_comment['item_id'];?>" class="comment-count"><?php echo $comment_count;?></span>
                          </a>
                      </p>
                      <?php
                            }
                          }
                          if (!empty($caption_comment) && $fav_count < 100 && $comment_count < 100) {
                      ?>
                      <p class="post-caption"><?php echo convertLinks($caption_comment); ?></p>
                      <?php
                          } else {
                      ?>
                      <p class="post-caption hidden">no caption</p>
                      <?php
                          }
                      ?>
                    </div>
                    <div class="show-comments-<?php echo $result_comment['item_id'];?> hidden"></div>
                    <script>
                    $(document).ready(function() {
                        var item_id = <?php echo $result_comment['item_id'];?>;
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
          <?php
                    }
                  }
              }

              if ($item_count == 0 && $item_comment_count == 0) { ?>
              <div class="col-md-3"></div>
              <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No hashtags for this yet.</h1></div>
          <?php
              }
            } else { ?>
              <div class="col-md-3"></div>
              <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;No hashtags for this yet.</h1></div>
          <?php    
            }
          ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

<?php
  require_once 'inc/search/search-footer.php';
?>