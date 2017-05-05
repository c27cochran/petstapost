    <?php

    if (isset($_POST['transloadit']) &&  $general->logged_in() === true  && $users->verify($user_id) === true) {

        function getHashtags($msg) {

            preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $msg, $matchedHashtags);
            $hashtag = '';

            if(!empty($matchedHashtags[0])) {
                foreach($matchedHashtags[0] as $match) {
                    $hashtag .= preg_replace("/[^a-z0-9]+/i", "", $match).', ';
                }
            }
            return rtrim($hashtag, ', ');
        }

        function getMentions($msg) {

            preg_match_all('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', $msg, $matchedMentions);
            $mention = '';

            if(!empty($matchedMentions[0])) {
                foreach($matchedMentions[0] as $match) {
                    $mention .= preg_replace("/[^a-z0-9]+/i", "", $match).', ';
                }
            }
            return rtrim($mention, ', ');
        }

        if (isset($_GET['video']) && !isset($_GET['profile']) && !isset($_GET['cover']) && !isset($_POST['item'])) {
            
            $result = $_POST['transloadit'];
            if (ini_get('magic_quotes_gpc') === '1') {
              $result = stripslashes($result);
            }

            $response_profile = json_decode($result, true);

            $status = $response_profile['ok'];
            $code = $response_profile['httpCode'];
            $p_caption = strip_tags(trim($_POST['custom-video-caption']));
            $hashtag = getHashtags($p_caption);
            $mention = getMentions($p_caption);

            if (!empty($mention) && $mention != '') {
                $mentions = explode(', ', $mention);

                foreach ($mentions as $usrn) {
                    $uid = $users->fetch_info('user_id', 'username', $usrn);
                    $notifications->insert_mention_notification($uid, $_SESSION['id'], $p_caption, $usrn);
                }
            }

            $p_filter = 'video';

            if ($status == 'ASSEMBLY_COMPLETED') {

                $v_url = $response_profile['results']['iphone_video'][0]['url'];
                $v_ssl_url = $response_profile['results']['iphone_video'][0]['ssl_url'];
                $v_name = $response_profile['results']['iphone_video'][0]['name'];
                $v_mime = $response_profile['results']['iphone_video'][0]['mime'];
                $v_width = $response_profile['results']['iphone_video'][0]['meta']['width'];
                $v_height = $response_profile['results']['iphone_video'][0]['meta']['height'];
                $v_duration = $response_profile['results']['iphone_video'][0]['meta']['duration'];
                $v_framerate = $response_profile['results']['iphone_video'][0]['meta']['framerate'];
                $v_video_bitrate = $response_profile['results']['iphone_video'][0]['meta']['video_bitrate'];
                $v_video_codec = $response_profile['results']['iphone_video'][0]['meta']['video_codec'];
                $v_audio_codec = $response_profile['results']['iphone_video'][0]['meta']['audio_codec'];
                $v_date_file_created = $response_profile['results']['iphone_video'][0]['meta']['date_file_created'];

                $p_url = $response_profile['results']['extracted_thumbs'][0]['url'];
                $p_ssl_url = $response_profile['results']['extracted_thumbs'][0]['ssl_url'];
                $p_cdn_id = $response_profile['results']['extracted_thumbs'][0]['id'];
                $p_name = $response_profile['results']['extracted_thumbs'][0]['name'];
                $p_mime = $response_profile['results']['extracted_thumbs'][0]['mime'];
                $p_width = $response_profile['results']['extracted_thumbs'][0]['meta']['width'];
                $p_height = $response_profile['results']['extracted_thumbs'][0]['meta']['height'];
                $p_date_recorded = $response_profile['results']['extracted_thumbs'][0]['meta']['date_recorded'];
                $p_date_file_created = $response_profile['results']['extracted_thumbs'][0]['meta']['date_file_created'];
                $p_date_file_modified = $response_profile['results']['extracted_thumbs'][0]['meta']['date_file_modified'];
                $p_aspect_ratio = $response_profile['results']['extracted_thumbs'][0]['meta']['aspect_ratio'];
                $p_city = $response_profile['results']['extracted_thumbs'][0]['meta']['city'];
                $p_state = $response_profile['results']['extracted_thumbs'][0]['meta']['state'];
                $p_country = $response_profile['results']['extracted_thumbs'][0]['meta']['country'];
                $p_device_name = $response_profile['results']['extracted_thumbs'][0]['meta']['device_name'];
                $p_latitude = $response_profile['results']['extracted_thumbs'][0]['meta']['latitude'];
                $p_longitude = $response_profile['results']['extracted_thumbs'][0]['meta']['longitude'];
                $p_orientation = $response_profile['results']['extracted_thumbs'][0]['meta']['orientation'];
                $p_colorspace = $response_profile['results']['extracted_thumbs'][0]['meta']['colorspace'];
                $p_average_color = $response_profile['results']['extracted_thumbs'][0]['meta']['average_color'];
            
                if (!empty($v_url)) {
                    $items->post_item_video($user['user_id'], $p_cdn_id, $p_name, $p_mime, $p_url, $p_ssl_url, $p_width, $p_height, $p_date_recorded, 
                        $p_date_file_created, $p_date_file_modified, $p_aspect_ratio, $p_city, $p_state, $p_country, $p_device_name, $p_latitude, $p_longitude, 
                        $p_orientation, $p_colorspace, $p_average_color, $p_filter, $p_caption, $hashtag, $mention, $v_url, $v_ssl_url, $v_name, $v_mime, $v_width , $v_height, 
                        $v_duration, $v_framerate, $v_video_bitrate, $v_video_codec, $v_audio_codec, $v_date_file_created);

                    header('Location: '.$_SESSION['username']);
                    exit;
                } else {
                    echo '<div id="profile-fail" class="warning-box">
                            <p class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                Sorry, there was an error uploading. Please try again.
                            </p>
                          </div>';
                }

            } elseif ($code == 200) {
                
                $assembly_url = $response_profile['assembly_url'];
                $json = file_get_contents($assembly_url);
                $obj = json_decode($json);

                $v_url = $obj->results->iphone_video[0]->url;
                $v_ssl_url = $obj->results->iphone_video[0]->ssl_url;
                $v_name = $obj->results->iphone_video[0]->name;
                $v_mime = $obj->results->iphone_video[0]->mime;
                $v_width = $obj->results->iphone_video[0]->meta->width;
                $v_height = $obj->results->iphone_video[0]->meta->height;
                $v_duration = $obj->results->iphone_video[0]->meta->duration;
                $v_framerate = $obj->results->iphone_video[0]->meta->framerate;
                $v_video_bitrate = $obj->results->iphone_video[0]->meta->video_bitrate;
                $v_video_codec = $obj->results->iphone_video[0]->meta->video_codec;
                $v_audio_codec = $obj->results->iphone_video[0]->meta->audio_codec;
                $v_date_file_created = $obj->results->iphone_video[0]->meta->date_file_created;

                $p_url = $obj->results->extracted_thumbs[0]->url;
                $p_ssl_url = $obj->results->extracted_thumbs[0]->ssl_url;
                $p_cdn_id = $obj->results->extracted_thumbs[0]->id;
                $p_name = $obj->results->extracted_thumbs[0]->name;
                $p_mime = $obj->results->extracted_thumbs[0]->mime;
                $p_width = $obj->results->extracted_thumbs[0]->meta->width;
                $p_height = $obj->results->extracted_thumbs[0]->meta->height;
                $p_date_recorded = $obj->results->extracted_thumbs[0]->meta->date_recorded;
                $p_date_file_created = $obj->results->extracted_thumbs[0]->meta->date_file_created;
                $p_date_file_modified = $obj->results->extracted_thumbs[0]->meta->date_file_modified;
                $p_aspect_ratio = $obj->results->extracted_thumbs[0]->meta->aspect_ratio;
                $p_city = $obj->results->extracted_thumbs[0]->meta->city;
                $p_state = $obj->results->extracted_thumbs[0]->meta->state;
                $p_country = $obj->results->extracted_thumbs[0]->meta->country;
                $p_device_name = $obj->results->extracted_thumbs[0]->meta->device_name;
                $p_latitude = $obj->results->extracted_thumbs[0]->meta->latitude;
                $p_longitude = $obj->results->extracted_thumbs[0]->meta->longitude;
                $p_orientation = $obj->results->extracted_thumbs[0]->meta->orientation;
                $p_colorspace = $obj->results->extracted_thumbs[0]->meta->colorspace;
                $p_average_color = $obj->results->extracted_thumbs[0]->meta->average_color;

                $items->post_item_video($user['user_id'], $p_cdn_id, $p_name, $p_mime, $p_url, $p_ssl_url, $p_width, $p_height, $p_date_recorded, 
                        $p_date_file_created, $p_date_file_modified, $p_aspect_ratio, $p_city, $p_state, $p_country, $p_device_name, $p_latitude, $p_longitude, 
                        $p_orientation, $p_colorspace, $p_average_color, $p_filter, $p_caption, $hashtag, $mention, $v_url, $v_ssl_url, $v_name, $v_mime, $v_width , $v_height, 
                        $v_duration, $v_framerate, $v_video_bitrate, $v_video_codec, $v_audio_codec, $v_date_file_created);
                
                header('Location: '.$_SESSION['username']);
                exit;

            } else {
                echo '<div id="profile-fail" class="warning-box">
                        <p class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;
                            Video upload failed. Please try again.
                        </p>
                      </div>';
            }
        }

        if (isset($_GET['profile']) && !isset($_GET['cover']) && !isset($_POST['item']) && !isset($_GET['video'])) {
            
            $result = $_POST['transloadit'];
            if (ini_get('magic_quotes_gpc') === '1') {
              $result = stripslashes($result);
            }

            if (!empty($_SESSION['profile_img_url'])) {
                if (file_exists($_SESSION['profile_img_url'])) {
                  unlink($_SESSION['profile_img_url']);
                }
            }

            $response_profile = json_decode($result, true);

            $status = $response_profile['ok'];
            $code = $response_profile['httpCode'];
            $filter_class = strip_tags(trim($_GET['profile']));

            if ($status == 'ASSEMBLY_COMPLETED') {
                $profile_picture = $response_profile['results']['import'][0]['url'];
            
                if (!empty($profile_picture)) {
                    $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_SESSION['username']."/avatar.png";
                    $users->update_profile_photo($s3_url, $filter_class, $user['user_id']);
                    header('Location: '.$_SESSION['username']);
                    exit;
                } else {
                    echo '<div id="profile-fail" class="warning-box">
                            <p class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                Sorry, there was an error uploading. Please try again.
                            </p>
                          </div>';
                }

            } elseif ($code == 200) {
                
                $assembly_url = $response_profile['assembly_url'];
                $json = file_get_contents($assembly_url);
                $obj = json_decode($json);
                $profile_picture_url = $obj->results->import[0]->url;
                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_SESSION['username']."/avatar.png";
                $users->update_profile_photo($s3_url, $filter_class, $user['user_id']);
                header('Location: '.$_SESSION['username']);
                exit;

            } else {
                echo '<div id="profile-fail" class="warning-box">
                        <p class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;
                            Sorry, there was an error uploading. Please try again.
                        </p>
                      </div>';
            }
        }

        if (isset($_GET['cover']) && !isset($_GET['profile']) && !isset($_POST['item']) && !isset($_GET['video'])) {
            
            $result = $_POST['transloadit'];
            if (ini_get('magic_quotes_gpc') === '1') {
              $result = stripslashes($result);
            }

            if (!empty($_SESSION['cover_img_url'])) {
                if (file_exists($_SESSION['cover_img_url'])) {
                  unlink($_SESSION['cover_img_url']);
                }
            }

            $response_profile = json_decode($result, true);

            $status = $response_profile['ok'];
            $code = $response_profile['httpCode'];
            $filter_class_cover = strip_tags(trim($_GET['cover']));

            if ($status == 'ASSEMBLY_COMPLETED') {
                $cover_picture = $response_profile['results']['import'][0]['url'];
                $border_color = $response_profile['results']['import'][0]['meta']['average_color'];
                $border_color = strtok($border_color, "#");

                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_SESSION['username']."/cover.png";
            
                if (!empty($cover_picture)) {
                    $users->update_cover_photo($s3_url, $filter_class_cover, $border_color, $user['user_id']);
                    header('Location: '.$_SESSION['username']);
                    exit;
                } else {
                    echo '<div id="profile-fail" class="warning-box">
                            <p class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                Sorry, there was an error uploading. Please try again.
                            </p>
                          </div>';
                }

            } elseif ($code == 200) {
                
                $assembly_url = $response_profile['assembly_url'];
                $json = file_get_contents($assembly_url);
                $obj = json_decode($json);
                $cover_picture_url = $obj->results->import[0]->url;
                $border_color = $obj->results->import[0]->meta->average_color;
                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_SESSION['username']."/cover.png";
                $users->update_cover_photo($s3_url, $filter_class_cover, $border_color, $user['user_id']);
                header('Location: '.$_SESSION['username']);
                exit;

            } else {
                echo '<div id="profile-fail" class="warning-box">
                        <p class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;
                            Sorry, there was an error uploading. Please try again.
                        </p>
                      </div>';
            }
        }

        if (isset($_POST['item']) && !isset($_GET['profile']) && !isset($_GET['cover']) && !isset($_GET['video'])) {
            
            $result = $_POST['transloadit'];
            if (ini_get('magic_quotes_gpc') === '1') {
              $result = stripslashes($result);
            }

            $response_profile = json_decode($result, true);

            $status = $response_profile['ok'];
            $code = $response_profile['httpCode'];
            $filter = strip_tags(trim($_POST['item']));
            $caption = strip_tags(trim($_POST['caption']));
            $hashtag = getHashtags($caption);
            $mention = getMentions($caption);

            if (!empty($mention) && $mention != '') {
                $mentions = explode(', ', $mention);

                foreach ($mentions as $usrn) {
                    $uid = $users->fetch_info('user_id', 'username', $usrn);
                    $notifications->insert_mention_notification($uid, $_SESSION['id'], $caption, $usrn);
                }
            }

            if ($status == 'ASSEMBLY_COMPLETED') {
                $url = $response_profile['results']['import'][0]['url'];
                $ssl_url = $response_profile['results']['import'][0]['ssl_url'];
                $cdn_id = $response_profile['results']['import'][0]['id'];
                $name = $response_profile['results']['import'][0]['name'];
                $mime = $response_profile['results']['import'][0]['mime'];
                $width = $response_profile['results']['import'][0]['meta']['width'];
                $height = $response_profile['results']['import'][0]['meta']['height'];
                $date_recorded = $response_profile['results']['import'][0]['meta']['date_recorded'];
                $date_file_created = $response_profile['results']['import'][0]['meta']['date_file_created'];
                $date_file_modified = $response_profile['results']['import'][0]['meta']['date_file_modified'];
                $aspect_ratio = $response_profile['results']['import'][0]['meta']['aspect_ratio'];
                $city = $response_profile['results']['import'][0]['meta']['city'];
                $state = $response_profile['results']['import'][0]['meta']['state'];
                $country = $response_profile['results']['import'][0]['meta']['country'];
                $device_name = $response_profile['results']['import'][0]['meta']['device_name'];
                $latitude = $response_profile['results']['import'][0]['meta']['latitude'];
                $longitude = $response_profile['results']['import'][0]['meta']['longitude'];
                $orientation = $response_profile['results']['import'][0]['meta']['orientation'];
                $colorspace = $response_profile['results']['import'][0]['meta']['colorspace'];
                $average_color = $response_profile['results']['import'][0]['meta']['average_color'];
            
                if (!empty($url)) {
                    $items->post_item($user['user_id'], $cdn_id, $name, $mime, $url, $ssl_url, $width, $height, $date_recorded, 
                        $date_file_created, $date_file_modified, $aspect_ratio, $city, $state, $country, $device_name, $latitude, $longitude, 
                        $orientation, $colorspace, $average_color, $filter, $caption, $hashtag, $mention);

                    header('Location: '.$_SESSION['username']);
                    exit;
                } else {
                    echo '<div id="profile-fail" class="warning-box">
                            <p class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                Sorry, there was an error uploading. Please try again.
                            </p>
                          </div>';
                }

            } elseif ($code == 200) {
                
                $assembly_url = $response_profile['assembly_url'];
                $json = file_get_contents($assembly_url);
                $obj = json_decode($json);


                $url = $obj->results->import[0]->url;
                $ssl_url = $obj->results->import[0]->ssl_url;
                $cdn_id = $obj->results->import[0]->id;
                $name = $obj->results->import[0]->name;
                $mime = $obj->results->import[0]->mime;
                $width = $obj->results->import[0]->meta->width;
                $height = $obj->results->import[0]->meta->height;
                $date_recorded = $obj->results->import[0]->meta->date_recorded;
                $date_file_created = $obj->results->import[0]->meta->date_file_created;
                $date_file_modified = $obj->results->import[0]->meta->date_file_modified;
                $aspect_ratio = $obj->results->import[0]->meta->aspect_ratio;
                $city = $obj->results->import[0]->meta->city;
                $state = $obj->results->import[0]->meta->state;
                $country = $obj->results->import[0]->meta->country;
                $device_name = $obj->results->import[0]->meta->device_name;
                $latitude = $obj->results->import[0]->meta->latitude;
                $longitude = $obj->results->import[0]->meta->longitude;
                $orientation = $obj->results->import[0]->meta->orientation;
                $colorspace = $obj->results->import[0]->meta->colorspace;
                $average_color = $obj->results->import[0]->meta->average_color;

                $items->post_item($user['user_id'], $cdn_id, $name, $mime, $url, $ssl_url, $width, $height, $date_recorded, 
                        $date_file_created, $date_file_modified, $aspect_ratio, $city, $state, $country, $device_name, $latitude, $longitude, 
                        $orientation, $colorspace, $average_color, $filter, $caption, $hashtag, $mention);
                
                header('Location: '.$_SESSION['username']);
                exit;

            } else {
                echo '<div id="profile-fail" class="warning-box">
                        <p class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;
                            Sorry, there was an error uploading. Please try again.
                        </p>
                      </div>';
            }
        }

        if (isset($_GET['pet']) && !isset($_GET['profile']) && !isset($_GET['cover']) && !isset($_POST['item']) && !isset($_GET['video'])) {
            
            $result = $_POST['transloadit'];
            if (ini_get('magic_quotes_gpc') === '1') {
              $result = stripslashes($result);
            }

            if (!empty($_SESSION['pet_img_url'])) {
                if (file_exists($_SESSION['pet_img_url'])) {
                  unlink($_SESSION['pet_img_url']);
                }
            }

            $response_profile = json_decode($result, true);

            $status = $response_profile['ok'];
            $code = $response_profile['httpCode'];
            $filter_class = strip_tags(trim($_GET['pet']));
            $pet_name = strip_tags(trim($_GET['id']));

            if ($status == 'ASSEMBLY_COMPLETED') {
                $profile_picture = $response_profile['results']['import'][0]['url'];
            
                if (!empty($profile_picture)) {
                    $pets->update_pet_photo($profile_picture, $filter_class, $pet_name, $user_id);
                    header('Location: '.$_SESSION['username']);
                    exit;
                } else {
                    echo '<div id="profile-fail" class="warning-box">
                            <p class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;
                                Sorry, there was an error uploading. Please try again.
                            </p>
                          </div>';
                }

            } elseif ($code == 200) {
                
                $assembly_url = $response_profile['assembly_url'];
                $json = file_get_contents($assembly_url);
                $obj = json_decode($json);
                $profile_picture_url = $obj->results->import[0]->url;
                $pets->update_pet_photo($profile_picture, $filter_class, $pet_name, $user_id);
                header('Location: '.$_SESSION['username']);
                exit;

            } else {
                echo '<div id="profile-fail" class="warning-box">
                        <p class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle"></i>&nbsp;
                            Sorry, there was an error uploading. Please try again.
                        </p>
                      </div>';
            }
        }
    }

    ?>
    <!-- Modal Section -->
    <div class="modal fade" id="account-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header login_modal_header">
                    <button type="button" id="modal-close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <?php
                        if ($general->logged_in() === false)  { 
                    ?>
                        <h2 class="modal-title" id="loginModalLabel" style="display:none;">Log in</h2>
                        <h2 class="modal-title" id="recoverModalLabel" style="display:none;">Recover Your Password</h2>
                        <h2 class="modal-title" id="createModalLabel" style="display:none;">Create An Account</h2>
                    <?php
                        } else {
                    ?>  
                        <h2 class="modal-title" id="changeModalLabel">Change Your Password</h2>
                    <?php
                        }
                    ?>
                </div>
                <div class="modal-body login-modal">
                    <div class="clearfix"></div>
                    <?php
                        if ($general->logged_in() === false)  { 
                    ?>
                    <div id="login-container" style="display:none;">
                        <div id="login-response"></div>
                        <div id="social-icons-conatainer">
                            <div class="modal-body-left">
                                <form id="login-form" class="login-form" name="login-form" method="POST">
                                    <div class="form-group">
                                        <input type="text" id="username-login" name="username-login" placeholder="Username or Email" value="" class="form-control login-field">
                                        <i class="fa fa-user login-field-icon"></i>
                                    </div>
                    
                                    <div class="form-group">
                                        <input type="password" id="password-login" name="password-login" placeholder="Password" value="" class="form-control login-field">
                                        <i class="fa fa-key login-field-icon"></i>
                                    </div>

                                    <div class="form-group">
                                        <input id="login-checkbox" type="checkbox" name="autologin" value="1"><label for="login-checkbox">Remember Me</label>
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="submit" id="submit_login" class="btn btn-default modal-login-btn" value="Log in">
                                    </div>
                                </form>
                                <a id="lost-password-link" class="login-link text-center form-group page-scroll" href="#page-top" data-toggle="modal" data-target="#recover-modal">Lost your password?</a>
                            </div>
                        
                            <div class="modal-body-right">
                                <div class="modal-social-icons">
                                    <!-- <a href="#" class="btn btn-default facebook"> <i class="fa fa-facebook modal-icons"></i> Sign In with Facebook </a>
                                    <a href="#" class="btn btn-default twitter"> <i class="fa fa-twitter modal-icons"></i> Sign In with Twitter </a>
                                    <a href="#" class="btn btn-default google"> <i class="fa fa-google-plus modal-icons"></i> Sign In with Google </a>
                                    <a href="#" class="btn btn-default linkedin"> <i class="fa fa-linkedin modal-icons"></i> Sign In with Linkedin </a> -->
                                    <a href="#page-top" id="create-account-button" data-toggle="modal" data-target="#register-modal" class="btn btn-default modal-login-btn form-group">Create Account</a>
                                </div> 
                            </div>  
                        </div> 
                    </div>
                    <div id="recover-container" style="display:none;">
                        <div id="recover-response"></div>
                        <div class="recover-modal-content">
                            <form id="recover-form" class="recover-form" name="recover-form" method="GET">
                                <div class="form-group">
                                    <input type="email" id="recover-email" name="recover-email" placeholder="Email" value="" class="form-control login-field">
                                    <i class="fa fa-envelope-o login-field-icon"></i>
                                </div>
                                <div class="form-group">
                                    <input type="submit" id="submit_recover" class="btn btn-default modal-login-btn" value="Get Password">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="register-container" style="display:none;">
                        <div id="register-response"></div>
                        <div class="register-modal-content">
                            <form id="register-form" class="register-form" name="register-form" method="POST">
                                <div class="form-group">
                                    <input type="text" id="username_register" name="username_register" placeholder="Username" value="" class="form-control login-field">
                                    <i class="fa fa-user login-field-icon"></i>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="firstname_register" name="firstname_register" placeholder="First Name" value="" class="form-control login-field">
                                    <i class="fa fa-star-o login-field-icon"></i>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="lastname_register" name="lastname_register" placeholder="Last Name" value="" class="form-control login-field">
                                    <i class="fa fa-star-o login-field-icon"></i>
                                </div>

                                <div class="form-group">
                                    <input type="email" id="email_register" name="email_register" placeholder="Email" value="" class="form-control login-field">
                                    <i class="fa fa-envelope-o login-field-icon"></i>
                                </div>
                
                                <div class="form-group">
                                    <input type="password" id="password_register" name="password_register" placeholder="Password" value="" class="form-control login-field">
                                    <i class="fa fa-key login-field-icon"></i>
                                </div>
                                <div class="form-group">
                                    <input type="submit" id="submit_register" class="btn btn-default modal-login-btn" value="Create Account">
                                </div>
                            </form>
                            <label class="agreement">
                                By clicking "Create Account", you agree to our <a href="terms.php" target="_blank">terms of use</a> and have read our <a href="privacy.php" target="_blank">privacy policy.</a>
                            </label>
                        </div>
                    </div>
                    <?php
                        } else {
                    ?>  
                    <div id="change-container">
                        <div id="change-response"></div>
                        <div class="change-modal-content">
                            <form id="change-form" class="change-form" name="change-form" method="GET">
                                <div class="form-group">
                                    <input type="password" id="change-current" name="change-current" placeholder="Current Password" value="" class="form-control login-field">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="change-new" name="change-new" placeholder="New Password" value="" class="form-control login-field">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="change-new-again" name="change-new-again" placeholder="New Password (again)" value="" class="form-control login-field">
                                </div>
                                <div class="form-group">
                                    <input type="submit" id="submit_change" class="btn btn-default modal-login-btn" value="Change Password">
                                </div>
                            </form>
                        </div>
                    </div>   
                <?php
                    }
                ?>
                <div class="clearfix"></div>
                <div class="modal-footer login_modal_footer">
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="crop-cover">

        <!-- Cropping modal -->
        <div class="modal fade" id="cover-modal" aria-hidden="true" aria-labelledby="cover-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="cover-modal-label">Change Cover Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <form class="cover-pic-upload hidden" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                            <!-- Current cover -->
                            <div class="cover-view">
                                <img src="" alt="Cover Photo Not Found">
                            </div>
                            <div class="row cover-btns">
                                <div class="col-md-12">
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-cover" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-cover" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-cover" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-cover" class="btn btn-primary btn-filter left" type="button">Fade</button>  
                                          <button id="color-blast-cover" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-cover" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-cover" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-cover" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-cover" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="cover-filter" class="hidden"></span>
                                          <span id="cover-username" class="hidden"><?php echo $_SESSION['username'];?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-cover" class="btn btn-primary cover-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary cover-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="cover-body">
                            <form class="cover-form" action="crop-cover.php" enctype="multipart/form-data" method="post">
                                <div class="cover-upload hidden">
                                    <input class="cover-src" name="cover_src" type="hidden">
                                    <input class="cover-data" name="cover_data" type="hidden">
                                    <input class="username" name="username" type="hidden" value="<?php echo $_SESSION['username'];?>">
                                    <!-- <i class="fi-photo"></i>&nbsp;
                                    <span>Upload Photo</span>
                                    <input id="cover-input" class="cover-input upload" name="cover_file" type="file" accept="image/*" /> -->
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-cover hidden">
                                    <div class="col-md-9">
                                        <div class="cover-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="cover-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row cover-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block cover-save hidden" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <div class="container" id="crop-avatar">

        <!-- Cropping modal -->
        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="avatar-modal-label">Change Profile Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <div id="avatar-failed-upload-message"></div>
                    <form class="profile-pic-upload hidden" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                            <!-- Current avatar -->
                            <div class="avatar-view">
                                <img src="" alt="Avatar Not Found">
                            </div>
                            <div class="row avatar-btns">
                                <div class="col-md-12"> 
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-avatar" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-avatar" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-avatar" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-avatar" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                          <button id="color-blast-avatar" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-avatar" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-avatar" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-avatar" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-avatar" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="profile-filter" class="hidden"></span>
                                          <span id="profile-username" class="hidden"><?php echo $_SESSION['username'];?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-avatar" class="btn btn-primary avatar-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary avatar-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="avatar-body">
                            <form class="avatar-form" action="crop-avatar.php" enctype="multipart/form-data" method="post">
                                <div class="avatar-upload hidden">
                                    <input class="avatar-src" name="avatar_src" type="hidden">
                                    <input class="avatar-data" name="avatar_data" type="hidden">
                                    <input class="username" name="username" type="hidden" value="<?php echo $_SESSION['username'];?>">
                                    <!-- <i class="fi-photo"></i>&nbsp; -->
                                    <!-- <span>Upload Photo</span> -->
                                    <!-- <input id="avatar-input" class="avatar-input upload" name="avatar_file" type="file" accept="image/*" /> -->
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-avatar hidden">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="avatar-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row avatar-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block avatar-save hidden" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <div class="container" id="crop-item">

        <!-- Cropping modal -->
        <div class="modal fade" id="item-modal" aria-hidden="true" aria-labelledby="item-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header picture-modal">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h2 class="modal-title" id="item-modal-label">Add a Pic</h2>
                    </div>
                    <div class="modal-body modal-body-avatar">
                    <div id="item-failed-upload-message"></div>
                    <form class="item-pic-upload hidden" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                        <input type="text" id="item-filter-input" name="item" class="hidden" value="">
                            <!-- Current item -->
                            <div class="item-view">
                                <img src="" alt="Pic Not Found">
                            </div>
                            <div class="row item-btns">
                                <div class="col-md-12">
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="bw-item" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                          <button id="chrome-item" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                          <button id="bold-item" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="fade-item" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                          <button id="color-blast-item" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                          <button id="antique-item" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                        </div>
                                    </div>
                                    <div class="btn-group-wrap">
                                        <div class="btn-group">
                                          <button id="brighten-item" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                          <button id="enhance-item" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                          <button id="original-item" class="btn btn-primary btn-filter left" type="button">Original</button>
                                          <span id="item-filter" class="hidden">original-filter</span>
                                          <span id="item-username" class="hidden"><?php echo $_SESSION['username'];?></span>
                                          <span id="item-filename" class="hidden"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="caption-group">
                                <h3 class="caption">Caption:</h3>
                                <textarea class="form-control custom-caption" name="caption" rows="2" style="resize:none" maxlength="160" onKeyUp="mentionCaption()"></textarea> 
                                <ul class="dropdown-menu" id="mention-results-caption"></ul>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="close-modal-item" class="btn btn-primary item-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                    <button class="btn btn-primary item-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                                </div>
                            </div>
                        </form>
                        <div class="item-body">
                            <form class="item-form" action="crop-item.php" enctype="multipart/form-data" method="post">
                                <div class="item-upload hidden">
                                    <input class="item-src" name="item_src" type="hidden">
                                    <input class="item-data" name="item_data" type="hidden">
                                    <input class="username" name="username" type="hidden" value="<?php echo $_SESSION['username'];?>">
                                    <!-- <i class="fi-photo"></i>&nbsp; -->
                                    <!-- <span>Upload Photo</span> -->
                                    <input type="file" name="my_file" class="my_file_modal hidden" multiple="multiple" />
                                    <!-- <input id="item-input" class="item-input upload" name="item_file" type="file" accept="image/*" /> -->
                                </div>

                                <!-- Crop and preview -->
                                <div class="row crop-preview-item hidden">
                                    <div class="col-md-9">
                                        <div class="item-wrapper"></div>
                                    </div>
                                    <div class="col-md-3 preview-div">
                                        <h3 class="preview">Preview</h3>
                                        <div class="item-preview preview-lg"></div>
                                    </div>
                                </div>

                                <div class="row item-btns">
                                    <div class="col-md-3 right">
                                        <button class="btn btn-primary btn-block item-save hidden" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>Copyright <i class="fa fa-copyright"></i> <span class="logo" style="color: #90f5a5;">Petstapost</span> <?php echo date("Y"); ?></p>
        </div>
    </footer>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/petstapost.js"></script>

    <script src="js/jquery-browser.js"></script>

    <script src="js/jstz.min.js"></script>

    <script src="js/magnific-popup.min.js"></script>

    <script src="js/cropper.min.js"></script>

    <script src="js/crop-avatar.js"></script>

    <script src="js/crop-pet-avatar.js"></script>

    <script src="js/crop-cover.js"></script>

    <script src="js/crop-item.js"></script>

    <script src="js/transloadit.min.js"></script>
    <!-- <script src="//assets.transloadit.com/js/jquery.transloadit2-v2-latest.js"></script> -->

    <div id="script-container"></div>

    <script language="javascript">
      $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "util/tz",
                data: 'timezone=' + jstz.determine().name(),
                success: function(data){
                }
            });
        
        });
    </script>

</body>

</html>