<?php

    require __DIR__ .'/../core/init.php';

    if (isset($_POST['user'])) {
        $username = $_POST['user'];
    }

    if ($users->user_exists($username)) {

        $profile_data   = array();
        $user_id        = $users->fetch_info('user_id', 'username', $username);
        $profile_data   = $users->userdata($user_id);

        $kibble_group_count = $items->get_kibble_group_count($user_id);

        $name = $profile_data['first_name'].' '.$profile_data['last_name'];

        if (isset($_POST['user']))  {

            echo '<span id="user-id" class="hidden">'.$user_id.'</span>';
            echo '<span id="my-name" class="hidden">'.$name.'</span>';

            echo '
                <script type="text/javascript">
                    $(document).ready(function() {
                        var track_load = 0; 
                        var loading  = false; 
                        var total_groups = '.$kibble_group_count.';
                        
                        $("#results").load("http://petstapost.com/mobile/kibble-scroll-load.php?group_no="+track_load+"&user='.$user_id.'", function() {track_load++;}); //load first group
                        
                        $(window).scroll(function() {
                            
                            if($(window).scrollTop() + $(window).height() + 20 >= $(document).height())
                            {
                                if (track_load < total_groups) {
                                    $(".keep-scrolling").show();
                                    $(".placeholder").hide();
                                }

                                if (track_load == total_groups) {
                                    $(".placeholder").show();
                                    $(".keep-scrolling").hide();
                                }

                                if(track_load < total_groups && loading==false)
                                {
                                    loading = true;
                                    $(".animation_image").show();

                                    $.post("http://petstapost.com/mobile/kibble-scroll-load.php?group_no="+track_load+"&user='.$user_id.'", function(data){
                                                        
                                        $("#results").append(data);
                                        $(".animation_image").hide();
                                        
                                        track_load++;
                                        loading = false; 

                                    }).fail(function(xhr, ajaxOptions, thrownError) {
                                        alert(thrownError);
                                        $(".animation_image").hide();
                                        loading = false;
                                    }); 
                                }
                            }
                        });
                    });
                </script>
            ';
        }

    } else {
        require_once 'http://petstapost.com/kibble/not-found.php';
    } 
?>