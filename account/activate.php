<?php
    require_once '../inc/account/account-header.php';
?>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <?php

                if (isset($_GET['user']) && isset($_GET['code'])) {
                    $username   = trim($_GET['user']);
                    $code       = trim($_GET['code']); 
                    
                    if ($users->email_confirmed($username) == 1) {
                        $errors[] = 'We have already confirmed your account';
                    } else if ($users->user_exists($username) === false) {
                        $errors[] = 'Sorry, we couldn\'t find that username';
                    } else if ($users->activate($username, $code) === false) {
                        $errors[] = 'Sorry, we have failed to activate your account';
                    }
                    
                    if(empty($errors) === false) {
                    
                        echo '<div class="alert-box">
                                <p class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    &nbsp;' . implode('</p><p class="alert alert-danger">', $errors) . 
                                '</p>
                              </div>';
                
                    } else {
                        $users->activate($username, $code);

                        echo '<div class="col-md-10">
                                    <div class="success-box">
                                        <p class="alert alert-success" role="alert">
                                            <i class="fa fa-thumbs-o-up"></i>&nbsp;
                                            Thank you - your account is activated!
                                        </p>
                                    </div>
                                    <br><br>
                                    <button class="btn btn-primary btn-back-to-profile" onclick="goToProfile()"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;Go to Profile</button>
                                </div>';

                        // $profile_data   = array();
                        // $user_id        = $users->fetch_info('user_id', 'username', $username); // Getting the user's id from the username in the Url.
                        // $profile_data   = $users->userdata($user_id);

                        // $password = $profile_data['password'];

                        // $login = $users->login($username, $password);

                        // if ($login === false) {
                        //     $errors[] = 'Sorry, that password is invalid';
                        // }
                                   
                        // session_regenerate_id(true); // destroying the old session id and creating a new one             
                        // $_SESSION['id'] =  $login;

                        // header('Location: ../'.$username);
                        // exit();

                    }
                
                } else {
                    if ($domain == "localhost") {
                        header('Location: /petstapost');
                        exit();
                    } else if ($domain == "petstapost.com") {
                        header('Location: /');
                        exit();
                    }
                }
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 title-div">

                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        function goToProfile() {
            window.location.replace('http://petstapost.com/<?php echo $username;?>');
        }
    </script>

<?php
    require_once '../inc/account/account-footer.php';
?>