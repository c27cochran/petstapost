<?php
    require_once 'header.php';

    if (isset($_GET['user'])) {
        $username = $_GET['user'];
    }

    if ($users->user_exists($username) && $username == $_SESSION['username']) {

        $kibble_group_count = $items->get_kibble_group_count($user_id);

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

    <script type="text/javascript">
        $(document).ready(function() {
            var track_load = 0; //total loaded record group(s)
            var loading  = false; //to prevents multipal ajax loads
            var total_groups = <?php echo $kibble_group_count; ?>; //total record group(s)
            
            $('#results').load("../util/scroll-load.php?group_no="+track_load, function() {track_load++;}); //load first group
            
            $(window).scroll(function() { //detect page scroll
                
                if($(window).scrollTop() + $(window).height() + 20 >= $(document).height())
                {

                    if (track_load < total_groups) {
                        $('.keep-scrolling').show();
                        $('.placeholder').hide();
                    }

                    if (track_load == total_groups) {
                        $('.placeholder').show();
                        $('.keep-scrolling').hide();
                    }
                    
                    if(track_load < total_groups && loading==false) //there's more data to load
                    {
                        loading = true; //prevent further ajax loading
                        $('.animation_image').show(); //show loading image
                        
                        //load data from the server using a HTTP POST request
                        $.post('../util/scroll-load.php?group_no='+track_load, function(data){
                                            
                            $("#results").append(data); //append received data into the element

                            //hide loading image
                            $('.animation_image').hide(); //hide loading image once data is received
                            
                            track_load++; //loaded group increment
                            loading = false; 
                        
                        }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
                            
                            $('.animation_image').hide(); //hide loading image
                            loading = false;
                        
                        });
                        
                    }
                }
            });
        });
    </script>
    
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
                        <div id="results" class="row profile-row">
                        
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-6 profile-item animation_image" style="display:none" align="center">
                            <img src="../img/ajax-loader.gif">
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

    require_once 'footer.php';
?>