<?php

    require __DIR__ .'/../core/init.php';

    $item_group_count = $items->get_item_group_count($user_id);

    echo '<script type="text/javascript">
        $(document).ready(function() {
            var track_load = 0; //total loaded record group(s)
            var loading  = false; //to prevents multipal ajax loads
            var total_groups = '.$item_group_count.'; //total record group(s)
            
            $("#results").load("http://petstapost.com/mobile/scroll-load-items.php?group_no="+track_load+"&my_user='.$my_user_id.'&their_user='.$their_user_id.'&verified='.$verified.'", function() {track_load++;}); //load first group
            
            $(window).scroll(function() { //detect page scroll
                
                if($(window).scrollTop() + $(window).height() + 20 >= $(document).height())
                {
                    if (track_load < total_groups) {
                        $(".keep-scrolling").show();
                    }

                    if (track_load == total_groups) {
                        $(".keep-scrolling").hide();
                    }
                    
                    if(track_load < total_groups && loading==false) //there"s more data to load
                    {
                        loading = true; //prevent further ajax loading
                        $(".animation_image").show(); //show loading image
                        
                        //load data from the server using a HTTP POST request
                        $.post("http://petstapost.com/mobile/scroll-load-items.php?group_no="+track_load+"&my_user='.$my_user_id.'&their_user='.$their_user_id.'&verified='.$verified.'", function(data){
                                            
                            $("#results").append(data); //append received data into the element

                            //hide loading image
                            $(".animation_image").hide(); //hide loading image once data is received
                            
                            track_load++; //loaded group increment
                            loading = false; 
                        
                        }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
                            
                            $(".animation_image").hide(); //hide loading image
                            loading = false;
                        
                        });
                        
                    }
                }
            });
        });
    </script>

<div class="col-md-12 display-items-area">
    <div  id="results" class="row profile-row">
    
    </div>
    <div class="col-md-3 profile-item profile-item-no-margin animation_image" style="display:none">
        <img src="img/ajax-loader.gif">
    </div>
</div>';