<?php
  require_once('php-sdk/facebook.php');

  $config = array(
    'appId' => '649041091809661',
    'secret' => '8a01b28cb5e95f6dbf6c0871a53f73ac',
    'fileUpload' => false, // optional
    'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
  );
  $facebook = new Facebook($config);

  // Get User ID
  $user_id = $facebook->getUser();

  $score = intval($_GET['score']);
  $access_token = $_GET['access_token'];
  echo "Score: {$score}";
  echo "Access token: {$access_token}";

  echo $facebook->api("/me/permissions");

  if ($user_id) {
    try {
      $ret_obj = $facebook->api('/me/feed', 'POST',
        array(
          'link' => 'https://apps.facebook.com/facebookfinal/',
          'message' => "I just got a score of {$score} out of 15 for Facebook Flashcards!!",
          'access_token' => $access_token
        )
      );
    } catch (FacebookApiException $e) {
      // If the user is logged out, you can have a 
      // user ID even though the access token is invalid.
      // In this case, we'll get an exception, so we'll
      // just ask the user to login again here.
      $login_url = $facebook->getLoginUrl(); 
      echo 'Please <a href="' . $login_url . '">login.</a>';
      echo $e->getType();
      echo $e->getMessage();
      error_log($e->getType());
      error_log($e->getMessage());
    }
  } else {
    // No user, print a link for the user to login
    $login_url = $facebook->getLoginUrl();
    echo 'Please <a href="' . $login_url . '">login.</a>';
  }
?>
