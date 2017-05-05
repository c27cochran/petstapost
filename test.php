<?php

$result = $_POST['transloadit'];
if (ini_get('magic_quotes_gpc') === '1') {
  $result = stripslashes($result);
}
$response = json_decode($result, true);



if ($response) {
  echo '<h1>Assembly status:</h1>';
  echo '<pre>';
  $status = $response->data['message'];

  if ($status == 'The assembly was successfully completed.') {
    // $id = $response->data['uploads'][0]['id'];
    // $url = $response->data['uploads'][0]['url'];
    // $border_color = $response->data['uploads'][0]['meta']['average_color'];
    // echo $id . '<br><br>' . $url . '<br><br>border-color: '.$border_color.'<br><br>';
    print_r($response);
  } else {
  	$assembly_url = $response->data['assembly_url'];
  	echo $assembly_url.'<br><br>';
    print_r($response);
  }
  echo '</pre>';
  exit;
}
?>
<form class="video-upload" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
<h1>Pick a video</h1>
<input name="example_upload" type="file">
<input type="submit" value="Upload">
</form>


<!--
Including the jQuery plugin is as simple as adding jQuery and including the
JS snippet for the plugin. See http://transloadit.com/docs/jquery-plugin
-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//assets.transloadit.com/js/jquery.transloadit2-v2-latest.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
   // Tell the transloadit plugin to bind itself to our form
   $('.video-upload').transloadit({
      wait: true,
      triggerUploadOnFileSelection: true,

      params: {
        auth: { key: "confidential" },
        // template_id: "7ae79760ee8011e4a145b969e2410dd0",
        steps: {
          thumb: {
          use: ":original",
          robot: "/image/resize",
          width: 75,
          height: 75,
          resize_strategy: "pad",
          background: "#000000"
        }
          // iphone_video: {
          //   use: ":original",
          //   robot: "/video/encode",
          //   preset: "iphone"
          // },
          // extracted_thumbs: {
          //   use: "iphone_video",
          //   robot: "/video/thumbs",
          //   count: 1,
          //   width: 640,
          //   height: 640
          // }
        }
      }
    });
 });
</script>




