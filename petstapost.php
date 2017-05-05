<?php
    require __DIR__ .'/core/init.php';

    define('FACEBOOK', '/core/classes/facebook/src/Facebook/');
    require __DIR__ . '/core/classes/facebook/autoload.php';

    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\GraphObject;
    use Facebook\GraphUser;
    use Facebook\FacebookRequestException;
    use Facebook\FacebookSDKException;

    FacebookSession::setDefaultApplication('confidential', 'confidential');

    $item_exists = $items->item_exists($_GET['item_id']);

    if (isset($_GET['item_id']) && $item_exists === true) {
        $item_id = $_GET['item_id'];
        $item_url = $items->fetch_info('url', 'item_id', $item_id);
        $user_id = $items->fetch_info('user_id', 'item_id', $item_id);
        $og_caption = $items->fetch_info('caption', 'item_id', $item_id);
    }

    $this_url = 'http://petstapost.com/petstapost.php?item_id='.$item_id;

    $helper = new FacebookRedirectLoginHelper($this_url);

    try {
      $fb_session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {
      // When Facebook returns an error
    } catch(Exception $ex) {
      // When validation fails or other local issues
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Keeping the Internet Cute.">
    <meta name="author" content="<?php echo $_SESSION['name'];?>">
    <link rel="canonical" href="http://petstapost.com/"/>
    <meta property="og:site_name" content="Petstapost"/>
    <meta property="og:title" content="<?php echo $og_caption;?>"/>
    <meta property="og:description" content="Keeping the Internet Cute.">
    <meta property="og:url" content="http://petstapost.com/petstapost.php?item_id=<?php echo $item_id;?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="<?php echo $item_url;?>"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@petstapost">
    <meta name="twitter:title" content="Petstapost">
    <meta name="twitter:description" content="<?php echo $og_caption;?>">
    <meta name="twitter:image:src" content="<?php echo $item_url;?>">

    <title>Petstapost - Keeping the Internet Cute.</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/cropper.min.css" rel="stylesheet">
    <link href="css/petstapost.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/ico" href="http://petstapost.com/img/favicon.ico?v=2"/>

    <!-- Custom Fonts -->
    <link href="foundation-icons/foundation-icons.css" rel="stylesheet" type="text/css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

    <style type="text/css">
        .fb_iframe_widget {
          background-color: #fff;
          padding: 9px;
          margin-bottom: 20px;
          margin-top: -13px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<?php
    if ($general->logged_in() === true)  { 
?>

    <!-- Navigation -->
    <nav id="verified-navbar" class="navbar navbar-custom navbar-yappy navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-mobile">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo $_SESSION['username'];?>">
                    <i class="fa fa-user"></i>&nbsp;<span class="light logo">Profile</span>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="hidden" href="#page-top"></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/">
                                    <i class="fa fa-home"></i>&nbsp;Home
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="alert('Petstapost app is not available yet');">
                                    <i class="fa fa-mobile"></i>&nbsp;Download App
                                </a>
                            </li>
                            <li>
                                <a href="search.php">
                                    <i class="fa fa-users"></i>&nbsp;Search for friends
                                </a>
                            </li>
                            <li>
                                <a id="change-modal-launcher" href="#" data-toggle="modal" data-target="#account-modal">
                                    <i class="fa fa-unlock-alt"></i>&nbsp;Change password
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="account/logout">
                                    <i class="fa fa-sign-out"></i>&nbsp;<span class="light">Sign Out</span>
                                </a>
                            </li>
                        </ul>
                  </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse navbar-right navbar-mobile" style="float:left;">
                <ul class="nav navbar-nav navbar-toggle navbar-mobile">
                    <li>
                        <a href="/">
                            <i class="fa fa-home"></i>&nbsp;Home
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="alert('Petstapost app is not available yet');">
                            <i class="fa fa-mobile"></i>&nbsp;Download App
                        </a>
                    </li>
                    <li>
                        <a href="search.php">
                            <i class="fa fa-users"></i>&nbsp;Search for friends
                        </a>
                    </li>
                    <li>
                        <a id="change-modal-launcher" href="#" data-toggle="modal" data-target="#account-modal">
                            <i class="fa fa-unlock-alt"></i>&nbsp;Change password
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="account/logout">
                            <i class="fa fa-sign-out"></i>&nbsp;<span class="light">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<?php
    } else {
?>     
<!-- Navigation -->
    <nav class="navbar navbar-custom navbar-yappy navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a id="login-modal-launcher" class="navbar-brand page-scroll" href="#page-top" data-toggle="modal" data-target="#account-modal">
                    <i class="fa fa-sign-in"></i>  <span class="light logo">Log in</span>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <li class="hidden active">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.php#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.php#download">Download App</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.php#contact">Contact</a>
                    </li>
                    <li>
                        <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">Create Account</a>
                    </li>
                </ul>
            </div>
        <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<?php
    }
?>

<?php
    function convertLinks($message) {
        $parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank" rel="nofollow">$1</a>', '$1<a class="comment-link" href="/$2">@$2</a>', '$1<a class="comment-link" href="/hashtag.php?hashtag=$2">#$2</a>'), $message);
        return $parsedMessage;
    }
?>

<div class="intro-home">
    <div class="intro-body">
        <div class="container profile-container">
            <div class="row profile-row">
                <div class="col-md-12">

                    <?php
                        if ($general->logged_in() && $users->verify($user_id)) {

                            // see if we have a session
                            if (!isset($fb_session) ) {
                              // show login url
                              echo '<a href="' . $helper->getLoginUrl() . '" class="btn btn-primary">
                                        <i class="fa fa-facebook fa-fw"></i>&nbsp;Share on Facebook
                                    </a>';
                            }

                            if(isset($fb_session)) {
                              try {
                                $response = (new FacebookRequest(
                                  $fb_session, 'POST', '/me/feed', array(
                                    'link' => $this_url,
                                    'message' => $og_caption
                                  )
                                ))->execute()->getGraphObject();
                                echo '<div class="white-popup-block" style="max-width:600px;">
                                        <h2 class="popup-caption" style="margin: 7px 20px 20px;">
                                            Facebook Post Successful!
                                        </h2>
                                      </div>';
                              } catch(FacebookRequestException $e) {
                                echo "Exception occured, code: " . $e->getCode();
                                echo " with message: " . $e->getMessage();
                              }   
                            }

                        }

                    if (isset($_GET['item_id']) && $item_exists === true) {

                        $user_id = $items->fetch_info('user_id', 'item_id', $item_id);
                        $secured = $users->fetch_info('secured', 'user_id', $user_id);

                        $item_data = $items->get_one_item($item_id);

                        if ($secured == 0 || ($general->logged_in() === true && $friends->check_friends($_SESSION['id'], $user_id) === true) || ($general->logged_in() === true && $users->verify($user_id) === true))  {

                            $caption = $item_data['caption'];
                            $item_id = $item_data['item_id'];
                            $url = $item_data['url'];


                            if ($item_data['filter'] == 'video') { 
                                $container_width = ($item_data['video_width'] + 40);
                    ?>
                            <div id="custom-content-<?php echo $item_id;?>" class="white-popup-block" style="max-width:520px; padding: 40px 20px 0 20px;">
                                <?php
                                    if (!empty($caption)) {
                                ?>
                                <h2 class="popup-caption" style="margin: 0 0 10px;"><?php echo convertLinks($caption); ?></h2>
                                <?php
                                    } else {
                                        echo '<br><br>';
                                    }
                                ?>
                                <script type="text/javascript">
                                    if(window.innerWidth <= 550) {
                                        var video = document.getElementsByTagName("video")[0];
                                        video.height = 160;
                                        video.width = 240;
                                    }
                                </script>
                                <video width="<?php echo $item_data['video_width'];?>" height="<?php echo $item_data['video_height'];?>" controls="">
                                    <source id="mp4Video" src="<?php echo $item_data['video_url'];?>" type="<?php echo $item_data['video_mime'];?>" codecs="<?php echo $item_data['audio_codec'];?>, <?php echo $item_data['video_codec'];?>">
                                </video>
                                <br>
                                <p>
                                <?php 
                                    if ($general->logged_in() === true && $friends->check_friends($_SESSION['id'], $user_id) === true)  {
                                        $fav_count = $favorites->count_favorites($item_data['item_id']);
                                        $comment_count = $comments->count_comments($item_data['item_id']);
                                ?>
                                    <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                                    <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                                        <?php
                                            if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                                                echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                                            } else {
                                                echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                                            }
                                        ?>
                                        <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                                    </a>
                                <?php
                                    } elseif ($general->logged_in() === true) {
                                        $fav_count = $favorites->count_favorites($item_data['item_id']);
                                        $comment_count = $comments->count_comments($item_data['item_id']);
                                ?>
                                    <span id="fav-pop-up-<?php echo $item_data['item_id'];?>"></span>
                                    <a id="item_<?php echo $item_data['item_id'];?>" href="javascript:void(0);" class="favorite right">
                                        <?php
                                            if ($favorites->already_favorited($item_data['item_id'], $_SESSION['id']) === true) {
                                                echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-heart shadow"></i>';
                                            } else {
                                                echo '<i id="fav-icon-'.$item_data['item_id'].'" class="fa fa-paw shadow"></i>';
                                            }
                                        ?>
                                        <span id="fav-count-<?php echo $item_data['item_id'];?>" class="fav-count"><?php echo $fav_count;?></span>
                                    </a>
                                <?php
                                    } else {
                                        $fav_count = $favorites->count_favorites($item_data['item_id']);
                                        $comment_count = $comments->count_comments($item_data['item_id']);
                                ?>
                                    <span class="favorite left">
                                        <i class="fa fa-paw"></i><span class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                                    </span>
                                <?php
                                    }
                                ?>
                                </p>
                                <br><br>
                    <?php
                            } else { 
                    ?>
                            <div id="custom-content-<?php echo $item_id;?>" class="white-popup-block" style="max-width:600px;">
                                <?php
                                    if (!empty($caption)) {
                                ?>
                                <h2 class="popup-caption" style="margin: 7px 20px 20px;"><?php echo convertLinks($caption); ?></h2>
                                <?php
                                    } else {
                                        echo '<br><br>';
                                    }
                                ?>
                                <img class="img-responsive <?php echo $item_data['filter'];?>" src="<?php echo $url;?>" alt="<?php echo $item_data['name'];?>"  style="padding: 0 7px;">
                                <br>
                                <p class="comment-like-container">
                                <?php
                                    $fav_count = $favorites->count_favorites($item_data['item_id']);
                                    $comment_count = $comments->count_comments($item_data['item_id']);
                                    if ($fav_count == '') {
                                        $fav_count = 0;
                                    }
                                ?>
                                    <span class="popup-comment right logo">
                                        <i class="fa fa-heart"></i><span class="fav-count">&nbsp;<?php echo $fav_count;?></span>
                                    </span>
                                </p>
                                <br><br>
                                <div class="comment-like-container">
                                <?php 
                            }

                                    $fav_data = $favorites->get_favorites($item_id);
                                    $fav_count = count($fav_data);

                                    if ($fav_count == 1) {
                                        echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                                        echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                        echo ' digs this.</p>';

                                    }

                                    if ($fav_count == 2) {
                                        echo '<p class="popup-comment"><i class="fa fa-heart"></i>&nbsp;';

                                        echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                        echo ' and <a href="'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

                                        echo ' dig this.</p>';

                                    }

                                    if ($fav_count > 2) {
                                        $count = ($fav_count-2);
                                        echo '<p class="popup-comment two-favorites"><i class="fa fa-heart"></i>&nbsp;';

                                        echo '<a class="comment-link" href="'.$fav_data[0]['username'].'">'.$fav_data[0]['first_name'].' ' . $fav_data[0]['last_name'].'</a>';

                                        echo ', <a href="'.$fav_data[1]['username'].'" class="comment-link">'.$fav_data[1]['first_name'].' ' . $fav_data[1]['last_name'].'</a>';

                                        echo ' and <a href="javascript:void(0);" class="show-others comment-link">';

                                            if ($count == 1) {
                                                echo $count.' other</a>';
                                            } else {
                                                echo $count.' others</a>';
                                            }

                                        echo ' dig this.</p>';

                                        echo '<p class="popup-comment all-favorites hidden"><i class="fa fa-heart"></i>&nbsp;';

                                        $new_fav_count = ($fav_count-1); 
                                        for ($i=0; $i<$new_fav_count; $i++) {
                                            echo '<a class="comment-link" href="'.$fav_data[$i]['username'].'">'.$fav_data[$i]['first_name'].' ' . $fav_data[$i]['last_name'].'</a>, ';
                                        }
                                            echo 'and <a class="comment-link" href="'.$fav_data[$new_fav_count]['username'].'">'.$fav_data[$new_fav_count]['first_name'].' ' . $fav_data[$new_fav_count]['last_name'].'</a>';
                                            echo ' dig this.';

                                        echo '</p>';
                                    }

                                ?>
                                <br>
                                <div class="comment-data-container">
                                <?php

                                    $comm_data = $comments->get_comments($item_id);

                                    foreach ($comm_data as $comm) {
                                        
                                        $first = $users->fetch_info('first_name', 'user_id', $comm['user_id']);
                                        $last = $users->fetch_info('last_name', 'user_id', $comm['user_id']);
                                        $username = $users->fetch_info('username', 'user_id', $comm['user_id']);

                                        echo '<p class="comment-time">'.date('F jS', $comm['time']).' at '.date('g:i a', $comm['time']).'</p>';
                                        echo '<p class="popup-comment"><a class="comment-link" href="'.$username.'">'.$first.' '.$last.'</a>: '.$comm['comment'].'</p>';
                                        echo '<br>';

                                    }

                                ?>
                                </div>
                            </div>
                        </div>
                    <?php

                        } else { ?>

                            <div class="col-md-3"></div>
                            <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-fw fa-lock"></i>&nbsp;User profile is secured.</h1></div>
                    <?php
                        }

                    } else { ?>
                        <div class="col-md-3"></div>
                        <div class="col-md-6 profile-item"><h1 class="no-items-found"><i class="fa fa-frown-o"></i>&nbsp;Post not found.</h1></div>
                    <?php
                    }
                    ?>  
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once 'inc/yappy/yappy-footer.php';

?>
