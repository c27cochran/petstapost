<html>
<head>
<title>Open Graph Getting Started App - og.likes</title>
<style type="text/css">
div { padding: 10px; }
</style>
<meta charset="UTF-8">
</head>
<body>
<script type="text/javascript">
  // You probably don't want to use globals, but this is just example code
  var fbAppId = 'confidential';
  var objectToLike = 'http://petstapost.com/petstapost.php?item_id=142';

  // Additional JS functions here
  window.fbAsyncInit = function() {
    FB.init({
      appId      : fbAppId, // App ID
      status     : true,    // check login status
      cookie     : true,    // enable cookies to allow the
                            // server to access the session
      xfbml      : true,     // parse page for xfbml or html5
                            // social plugins like login button below
      version        : 'v2.0',  // Specify an API version
    });
  };

  // Load the SDK Asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function postLike() {
    FB.api(
       'https://graph.facebook.com/me/og.likes',
       'post',
       { object: objectToLike,
         privacy: {'value': 'SELF'} },
       function(response) {
         if (!response) {
           alert('Error occurred.');
         } else if (response.error) {
           document.getElementById('result').innerHTML =
             'Error: ' + response.error.message;
         } else {
           document.getElementById('result').innerHTML =
             '<a href=\"https://www.facebook.com/me/activity/' +
             response.id + '\">' +
             'Story created.  ID is ' +
             response.id + '</a>';
         }
       }
    );
  }
</script>

<!--
  Login Button

  https://developers.facebook.com/docs/reference/plugins/login

  This example needs the 'publish_actions' permission in
  order to publish an action.  The scope parameter below
  is what prompts the user for that permission.
-->

<div
  class="fb-login-button"
  data-show-faces="true"
  data-width="200"
  data-max-rows="1"
  data-scope="publish_actions">
</div>

<div>
<input
  type="button"
  value="Share on Facebook"
  onclick="postLike();">
</div>

<div id="result"></div>

</body>
</html>