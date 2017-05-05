<?php
require __DIR__ .'/../core/connect/mysqli_connect.php';

// Define Output HTML Formating
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
$html .= '<span>nameString</span>';
$html .= '</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$search_string = $dbc->real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT first_name, last_name, username, profile_picture, profile_picture_filter FROM users WHERE first_name LIKE "%'.$search_string.'%" 
		OR (last_name LIKE "%'.$search_string.'%") OR (username LIKE "%'.$search_string.'%") OR (CONCAT(first_name, " ", last_name, " ") LIKE "%'.$search_string.'%") LIMIT 7';

	// Do Search
	$result = $dbc->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			$name = $result['first_name'] . ' ' . $result['last_name'];
			$handle = '<span class="handle">@'.$result['username'].'</span>';
			if ($result['profile_picture']) {
				$profile_pic = '<img src="'.$result['profile_picture'].'" class="'.$result['profile_picture_filter'].' search-img" width="40">';
			} else {
				$profile_pic = '<img src="../img/avatar-placeholder.png" class="search-img" width="40">';
			}
			$display_url = '../'.urlencode($result['username']);

			// Insert Name
			$output = str_replace('nameString', $profile_pic.'&nbsp;&nbsp;'.$name.'&nbsp;'.$handle, $html);

			// Insert URL
			$output = str_replace('urlString', $display_url, $output);

			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('nameString', '<b><i class="fa fa-frown-o"></i>&nbsp;No Results Found</b>', $output);

		// Output
		echo($output);
	}
}

