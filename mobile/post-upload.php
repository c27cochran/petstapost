<?php

    require __DIR__ .'/../core/init.php';

    if (isset($_POST['transloadit']) && isset($_GET['user'])) {

        $profile_data   = array();
        $my_user_id     = $users->fetch_info('user_id', 'username', $_GET['user']);
        $profile_data   = $users->userdata($my_user_id);

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
                    $items->post_item_video($my_user_id, $p_cdn_id, $p_name, $p_mime, $p_url, $p_ssl_url, $p_width, $p_height, $p_date_recorded, 
                        $p_date_file_created, $p_date_file_modified, $p_aspect_ratio, $p_city, $p_state, $p_country, $p_device_name, $p_latitude, $p_longitude, 
                        $p_orientation, $p_colorspace, $p_average_color, $p_filter, $p_caption, $hashtag, $mention, $v_url, $v_ssl_url, $v_name, $v_mime, $v_width , $v_height, 
                        $v_duration, $v_framerate, $v_video_bitrate, $v_video_codec, $v_audio_codec, $v_date_file_created);

                    // echo  'yappy.html?user='.$_POST['username'];
                    echo '<script language=javascript>window.history.go(-1);</script> ';
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

                $items->post_item_video($my_user_id, $p_cdn_id, $p_name, $p_mime, $p_url, $p_ssl_url, $p_width, $p_height, $p_date_recorded, 
                        $p_date_file_created, $p_date_file_modified, $p_aspect_ratio, $p_city, $p_state, $p_country, $p_device_name, $p_latitude, $p_longitude, 
                        $p_orientation, $p_colorspace, $p_average_color, $p_filter, $p_caption, $hashtag, $mention, $v_url, $v_ssl_url, $v_name, $v_mime, $v_width , $v_height, 
                        $v_duration, $v_framerate, $v_video_bitrate, $v_video_codec, $v_audio_codec, $v_date_file_created);
                
                // echo  'yappy.html?user='.$_POST['username'];
                echo '<script language=javascript>window.history.go(-1);</script> ';
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
                    $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_GET['user']."/avatar.png";
                    $users->update_profile_photo($s3_url, $filter_class, $my_user_id);
                    // echo  'yappy.html?user='.$_POST['username'];
                    echo '<script language=javascript>window.history.go(-1);</script> ';
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
                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_GET['user']."/avatar.png";
                $users->update_profile_photo($s3_url, $filter_class, $my_user_id);
                // echo  'yappy.html?user='.$_POST['username'];
                echo '<script language=javascript>window.history.go(-1);</script> ';
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

                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_GET['user']."/cover.png";
            
                if (!empty($cover_picture)) {
                    $users->update_cover_photo($s3_url, $filter_class_cover, $border_color, $my_user_id);
                    // echo  'yappy.html?user='.$_POST['username'];
                    echo '<script language=javascript>window.history.go(-1);</script> ';
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
                $s3_url = "http://s3-us-west-1.amazonaws.com/petstapost/".$_GET['user']."/cover.png";
                $users->update_cover_photo($s3_url, $filter_class_cover, $border_color, $my_user_id);
                // echo  'yappy.html?user='.$_POST['username'];
                echo '<script language=javascript>window.history.go(-1);</script> ';
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
                    $items->post_item($my_user_id, $cdn_id, $name, $mime, $url, $ssl_url, $width, $height, $date_recorded, 
                        $date_file_created, $date_file_modified, $aspect_ratio, $city, $state, $country, $device_name, $latitude, $longitude, 
                        $orientation, $colorspace, $average_color, $filter, $caption, $hashtag, $mention);

                    // echo  'yappy.html?user='.$_POST['username'];
                    echo '<script language=javascript>window.history.go(-1);</script> ';
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

                $items->post_item($my_user_id, $cdn_id, $name, $mime, $url, $ssl_url, $width, $height, $date_recorded, 
                        $date_file_created, $date_file_modified, $aspect_ratio, $city, $state, $country, $device_name, $latitude, $longitude, 
                        $orientation, $colorspace, $average_color, $filter, $caption, $hashtag, $mention);
                
                // echo  'yappy.html?user='.$_POST['username'];
                echo '<script language=javascript>window.history.go(-1);</script> ';
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
                    // echo  'yappy.html?user='.$_POST['username'];
                    echo '<script language=javascript>window.history.go(-1);</script> ';
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
                // echo  'yappy.html?user='.$_POST['username'];
                echo '<script language=javascript>window.history.go(-1);</script> ';
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
