<?php

	require __DIR__ .'/../core/init.php';
	require __DIR__ .'/../core/connect/mysqli_connect.php';

    if (!empty($_GET['query'])) {
        // Define Output HTML Formating
        $html = '<div class="col-md-4"></div>';
        $html .= '<div class="col-md-4 search-item profile-item-no-margin">';
        $html .= '<a href="urlString">';
        $html .= '<span>nameString</span>';
        $html .= '</a>';
        $html .= '</div>';

        // Get Search
        $search_string = $_GET['query'];
        $search_string = $dbc->real_escape_string($search_string);

        // Check Length More Than One Character
        if (strlen($search_string) >= 1 && $search_string !== ' ') {
            // Build Query
            $query = 'SELECT first_name, last_name, username, profile_picture, profile_picture_filter FROM users WHERE first_name LIKE "%'.$search_string.'%" 
                OR (last_name LIKE "%'.$search_string.'%") OR (username LIKE "%'.$search_string.'%") OR (CONCAT(first_name, " ", last_name, " ") LIKE "%'.$search_string.'%") LIMIT 50';

            // Do Search
            $result = $dbc->query($query);
            while($results = $result->fetch_array()) {
                $result_array[] = $results;
            }

            // Check If We Have Results
            if (isset($result_array)) {
                foreach ($result_array as $result) {

                    // Format Output Strings And Hightlight Matches
                    $name = $result['first_name'] . ' ' . $result['last_name'];
                    $handle = '@'.$result['username'];
                    if ($result['profile_picture']) {
                        $profile_pic = '<img src="'.$result['profile_picture'].'" class="'.$result['profile_picture_filter'].' search-page-img">';
                    } else {
                        $profile_pic = '<img src="img/avatar-placeholder.png" class="search-page-img" width="220">';
                    }
                    $display_url = urlencode($result['username']);

                    // Insert Name
                    $output = str_replace('nameString', $profile_pic.'&nbsp;&nbsp;<h3 class="comment-link" style="text-transform: none; margin-bottom: 5px;">'.$name.'</h3><h5 class="handle-search">'.$handle.'</h5>', $html);

                    // Insert URL
                    $output = str_replace('urlString', 'yappy.html?user='.$display_url, $output);

                    // Output
                    echo($output);
                }
            }else{

                // Format No Results Output
                $output = str_replace('urlString', 'javascript:void(0);', $html);
                $output = str_replace('nameString', '<h3 style="color:#5e5e5e; margin: 0 0 15px;"><i class="fa fa-frown-o"></i>&nbsp;No Results Found</h3>', $output);

                // Output
                echo($output);
            }
        }
    } 