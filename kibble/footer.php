
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
                                <form id="login-form" class="login-form" name="login-form" method="GET">
                                    <div class="form-group">
                                        <input type="text" id="username-login" name="username-login" placeholder="Username or Email" value="" class="form-control login-field">
                                        <i class="fa fa-user login-field-icon"></i>
                                    </div>
                    
                                    <div class="form-group">
                                        <input type="password" id="password-login" name="password-login" placeholder="Password" value="" class="form-control login-field">
                                        <i class="fa fa-key login-field-icon"></i>
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
                            <form id="register-form" class="register-form" name="register-form" method="GET">
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
                        <input type="file" name="my_file" class="hidden" multiple="multiple" />
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
                            <form class="item-form" action="../crop-item.php" enctype="multipart/form-data" method="post">
                                <div class="item-upload hidden">
                                    <input class="item-src" name="item_src" type="hidden">
                                    <input class="item-data" name="item_data" type="hidden">
                                    <input class="username" name="username" type="hidden" value="<?php echo $_SESSION['username'];?>">
                                    <!-- <i class="fi-photo"></i>&nbsp; -->
                                    <!-- <span>Upload Photo</span> -->
                                    <input type="file" name="my_file" class="hidden" multiple="multiple" />
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
    <script src="../js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../js/jquery.easing.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../js/kibble.js"></script>

    <script src="../js/jquery-browser.js"></script>

    <script src="../js/jstz.min.js"></script>

    <script src="../js/magnific-popup.min.js"></script>

    <script src="../js/cropper.min.js"></script>

    <script src="../js/crop-item-kibble.js"></script>

    <script src="../js/transloadit.min.js"></script>

    <div id="script-container"></div>

    <script language="javascript">
      $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "../util/tz",
                data: 'timezone=' + jstz.determine().name(),
                success: function(data){
                }
            });
        
        });
    </script>

</body>

</html>