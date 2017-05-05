<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

define('FACEBOOK', '/core/classes/facebook/src/Facebook/');
require __DIR__ . '/core/classes/facebook/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSDKException;

FacebookSession::setDefaultApplication('confidential', 'confidential');


$helper = new FacebookRedirectLoginHelper('http://petstapost.com/facebook.php');

try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(Exception $ex) {
  // When validation fails or other local issues
}
 
// see if we have a session
if (!isset($session) ) {
  // show login url
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}

if(isset($session)) {
  try {
    $response = (new FacebookRequest(
      $session, 'POST', '/me/feed', array(
        'link' => 'www.example.com',
        'message' => 'User provided message'
      )
    ))->execute()->getGraphObject();
    echo "Posted with id: " . $response->getProperty('id');
  } catch(FacebookRequestException $e) {
    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();
  }   
}