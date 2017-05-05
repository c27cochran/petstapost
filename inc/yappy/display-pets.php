<div class="col-md-12 display-pets-area hidden">
    <div class="row profile-row">
    <?php
        $current_pet_data = $pets->get_current_pets($user_id);
        $past_pet_data = $pets->get_past_pets($user_id);
        $pet_count = $pets->count_pets($user_id);
        $current_pet_count = $pets->count_current_pets($user_id);
        $past_pet_count = $pets->count_past_pets($user_id);
        
        if ($current_pet_count >= 1) {
            echo '<div id="current-pets" class="col-md-9 profile-item"><h3 class="hashtag-name center">Current Pets</h3></div>';
            foreach ($current_pet_data as $result) { 
    ?>
            <div id="profile_pet_<?php echo $result['pet_id'];?>" class="col-md-9 profile-item">
                <?php 

                if ($general->logged_in() === true && $users->verify($user_id) === true) { 

                        $pet_name = $result['pet_name'];
                        $pet_name_quotes = "'".$result['pet_name']."'";
                        $profile_pic = $result['pet_avatar_url'];
                        $profile_filter = $result['pet_filter'];
                        $type = $result['type'];
                        $breed = $result['breed'];

                        echo '<span class="comment-link">
                                <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                              </span>
                              <div id="camera-pet-upload">
                                    <a id="pet-avatar-file" href="javascript:void(0);" data-toggle="modal" data-target="#pet-avatar-modal" onclick="getPetID('.$pet_name_quotes.')">
                                        <i class="fa fa-camera"></i>
                                    </a>
                              </div>
                              <br><br>
                                <h2 class="pet-name">'.$pet_name.'</h2>
                                <h4 class="pet-info">'.$breed.'</h4><br>';
                ?>

                <?php 
                } else if ($users->verify($user_id) === false) {

                    $pet_name = $result['pet_name'];
                    $profile_pic = $result['pet_avatar_url'];
                    $profile_filter = $result['pet_filter'];
                    $type = $result['type'];
                    $breed = $result['breed'];

                    if (!empty($pet_name)) {
                        echo '<span class="comment-link">
                                <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                              </span>
                              <br><br>
                                <h2 class="pet-name">'.$pet_name.'</h2>
                                <h4 class="pet-info">'.$breed.'</h4>';
                    }
                } 
              ?>

            </div>
    <?php
            }
        }

        if ($past_pet_count >= 1) {
            echo '<div id="past-pets" class="col-md-9 profile-item"><h3 class="hashtag-name center">Past Pets</h3></div>';
            foreach ($past_pet_data as $result) { 
    ?>
            <div id="profile_pet_<?php echo $result['pet_id'];?>" class="col-md-9 profile-item">
                <?php 

                if ($general->logged_in() === true && $users->verify($user_id) === true) { 

                        $pet_name = $result['pet_name'];
                        $pet_name_quotes = "'".$result['pet_name']."'";
                        $profile_pic = $result['pet_avatar_url'];
                        $profile_filter = $result['pet_filter'];
                        $type = $result['type'];
                        $breed = $result['breed'];

                        echo '<span class="comment-link">
                                <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                              </span>
                              <div id="camera-pet-upload">
                                    <a id="pet-avatar-file" href="javascript:void(0);" data-toggle="modal" data-target="#pet-avatar-modal" onclick="getPetID('.$pet_name_quotes.')">
                                        <i class="fa fa-camera"></i>
                                    </a>
                              </div>
                              <br><br>
                                <h2 class="pet-name">'.$pet_name.'</h2>
                                <h4 class="pet-info">'.$breed.'</h4><br>';
                ?>

                <?php 
                } else if ($users->verify($user_id) === false) {

                    $pet_name = $result['pet_name'];
                    $profile_pic = $result['pet_avatar_url'];
                    $profile_filter = $result['pet_filter'];
                    $type = $result['type'];
                    $breed = $result['breed'];

                    if (!empty($pet_name)) {
                        echo '<span class="comment-link">
                                <img src="'.$profile_pic.'" class="pet-img '.$profile_filter.'">
                              </span>
                              <br><br>
                                <h2 class="pet-name">'.$pet_name.'</h2>
                                <h4 class="pet-info">'.$breed.'</h4>';
                    }
                } 
              ?>

            </div>
    <?php
            }

        }
        
        if ($general->logged_in() === true && $users->verify($user_id) === true && $pet_count < 1) {
            echo '<div class="col-md-9 profile-item no-pets">
                    <h1 class="no-items-found">No Pets Yet</h1>
                    <h1 class="no-items-found"><i class="fa fa-arrow-down"></i></h1>
                    <p class="friend-container" style="margin-bottom: 30px;">
                        <button class="btn btn-item-upload add-pet" href="javascript:void(0);">
                            <i class="fa fa-plus-square"></i>&nbsp;Add Your Pet
                        </button>
                    </p>
                  </div>';
        } elseif ($users->verify($user_id) === false && $pet_count < 1) {
            echo '<div class="col-md-9 profile-item"><h1 class="no-items-found">No Pets, Just Hangin\'</h1></div>';
        }

        if ($general->logged_in() === true && $users->verify($user_id) === true && $pet_count >= 1) {
    ?>
        <div class="col-md-9 profile-item register-pet-container" style="display:none;">
            <div id="register-pet-container">
                <div id="register-pet-response"></div>
                <div class="register-pet-modal-content">
                    <form id="register-pet-form" class="register-pet-form" name="register-pet-form" method="POST">
                        <div class="form-group">
                            <select id="past_present" name="past_present" class="form-control">
                              <option value="Unknown">Current or Past Pet...</option>
                              <option value="1">Current Pet</option>
                              <option value="0">Past Pet</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" id="name_pet" name="name_pet" placeholder="Pet Name" value="" class="form-control">
                        </div>

                        <div class="form-group">
                            <select id="type_pet" name="type_pet" class="form-control">
                              <option value="Unknown">Pet Type...</option>
                              <option value="Dog">Dog</option>
                              <option value="Cat">Cat</option>
                              <option value="Horse">Horse</option>
                              <option value="Rabbit">Rabbit</option>
                              <option value="Ferret">Ferret</option>
                              <option value="Bird">Bird</option>
                              <option value="Fish">Fish</option>
                              <option value="Gerbal">Gerbal</option>
                              <option value="Hamster">Hamster</option>
                              <option value="Guinea Pig">Guinea Pig</option>
                              <option value="Snake">Snake</option>
                              <option value="Lizard">Lizard</option>
                              <option value="Turtle">Turtle</option>
                              <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" id="breed_pet" name="breed_pet" placeholder="Breed" value="" class="form-control">
                        </div>

                        <div class="form-group">
                            <input type="submit" id="submit_pet" class="btn btn-primary" value="Add Pet!">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="add-pet-div" class="col-md-9 profile-item">
            <button class="btn btn-item-upload add-pet" href="javascript:void(0);" style="margin-bottom:15px;">
                <i class="fa fa-plus-square"></i>&nbsp;Add Another Pet
            </button>
        </div>
    <?php
        }
    ?>

    </div>
</div>

<div class="container" id="crop-pet-avatar">

    <!-- Cropping modal -->
    <div class="modal fade" id="pet-avatar-modal" aria-hidden="true" aria-labelledby="pet-avatar-modal-label" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header picture-modal">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h2 class="modal-title" id="pet-avatar-modal-label">Change Pet Pic</h2>
                </div>
                <div class="modal-body modal-body-pet-avatar">
                <form class="pet-avatar-pic-upload hidden" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="file" name="my_file" class="hidden" multiple="multiple" />
                        <!-- Current avatar -->
                        <div class="pet-avatar-view">
                            <img src="" alt="Avatar Not Found">
                        </div>
                        <div class="row avatar-btns">
                            <div class="col-md-12"> 
                                <div class="btn-group-wrap">
                                    <div class="btn-group">
                                      <button id="bw-pet-avatar" class="btn btn-primary btn-filter left" type="button">Black &amp; White</button>
                                      <button id="chrome-pet-avatar" class="btn btn-primary btn-filter left" type="button">Chrome</button>
                                      <button id="bold-pet-avatar" class="btn btn-primary btn-filter left" type="button">Bold</button>
                                    </div>
                                </div>
                                <div class="btn-group-wrap">
                                    <div class="btn-group">
                                      <button id="fade-pet-avatar" class="btn btn-primary btn-filter left" type="button">Fade</button>
                                      <button id="color-blast-pet-avatar" class="btn btn-primary btn-filter left" type="button">Color Blast</button>
                                      <button id="antique-pet-avatar" class="btn btn-primary btn-filter left" type="button">Antique</button>
                                    </div>
                                </div>
                                <div class="btn-group-wrap">
                                    <div class="btn-group">
                                      <button id="brighten-pet-avatar" class="btn btn-primary btn-filter left" type="button">Brighten</button>
                                      <button id="enhance-pet-avatar" class="btn btn-primary btn-filter left" type="button">Enhance</button>
                                      <button id="original-pet-avatar" class="btn btn-primary btn-filter left" type="button">Original</button>
                                      <span id="pet-filter" class="hidden"></span>
                                      <span id="pet-username" class="hidden"><?php echo $_SESSION['username'];?></span>
                                      <span id="pet-id" class="hidden"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="close-modal-pet-avatar" class="btn btn-primary avatar-nope left" type="reset"><i class="fa fa-thumbs-o-down"></i>&nbsp;Nope</button>
                                <button class="btn btn-primary avatar-save right" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;I like it</button>
                            </div>
                        </div>
                    </form>
                    <div class="pet-avatar-body">
                        <form class="pet-avatar-form" action="crop-pet-avatar.php" enctype="multipart/form-data" method="post">
                            <div class="pet-avatar-upload fileUpload btn btn-primary">
                                <input class="pet-avatar-src" name="pet_avatar_src" type="hidden">
                                <input class="pet-avatar-data" name="pet_avatar_data" type="hidden">
                                <input class="username" name="username" type="hidden" value="<?php echo $_SESSION['username'];?>">
                                <i class="fi-photo"></i>&nbsp;
                                <span>Upload Photo</span>
                                <input id="pet-avatar-input" class="pet-avatar-input upload" name="pet_avatar_file" type="file" accept="image/*" />
                            </div>

                            <!-- Crop and preview -->
                            <div class="row crop-preview-pet-avatar hidden">
                                <div class="col-md-9">
                                    <div class="pet-avatar-wrapper"></div>
                                </div>
                                <div class="col-md-3 preview-div">
                                    <h3 class="preview">Preview</h3>
                                    <div class="pet-avatar-preview preview-lg"></div>
                                </div>
                            </div>

                            <div class="row avatar-btns">
                                <div class="col-md-3 right">
                                    <button class="btn btn-primary btn-block pet-avatar-save hidden" type="submit"><i class="fa fa-crop"></i>&nbsp;Crop</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
</div>