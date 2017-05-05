<?php
    $item_group_count = $items->get_item_group_count($user_id);
?>

    <script type="text/javascript">
        $(document).ready(function() {
            var track_load = 0; //total loaded record group(s)
            var loading  = false; //to prevents multipal ajax loads
            var total_groups = <?php echo $item_group_count; ?>; //total record group(s)
            
            $('#results').load("util/scroll-load-items.php?group_no="+track_load+"&user=<?php echo $user_id;?>", function() {track_load++;}); //load first group
            
            $(window).scroll(function() { //detect page scroll
                
                if($(window).scrollTop() + $(window).height() + 20 >= $(document).height())
                {
                    if (track_load < total_groups) {
                        $('.keep-scrolling').show();
                    }

                    if (track_load == total_groups) {
                        $('.keep-scrolling').hide();
                    }
                    
                    if(track_load < total_groups && loading==false) //there's more data to load
                    {
                        loading = true; //prevent further ajax loading
                        $('.animation_image').show(); //show loading image
                        
                        //load data from the server using a HTTP POST request
                        $.post('util/scroll-load-items.php?group_no='+track_load+"&user=<?php echo $user_id;?>", function(data){
                                            
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

<div class="col-md-12 display-items-area">
    <div  id="results" class="row profile-row">
    
    </div>
    <div class="col-md-3 profile-item animation_image" style="display:none">
        <img src="img/ajax-loader.gif">
    </div>
</div>