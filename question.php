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
echo $user_id . "\r\n";

if ($user_id) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me','GET');
    $namePrint = "Name: " . $user_profile['name'];
    $friends = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT uid1 FROM friend WHERE uid2=me()',
                        ));
    $hometown = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT name, hometown_location.city FROM user WHERE hometown_location AND uid in (SELECT uid1 FROM friend WHERE uid2 = me())',
                        ));
    /*print_r($friends);
    foreach ($friends as $friend) {
      print_r($friend['uid1']);
      /*$nameOfFriend = $facebook->api(array(
                         'method' => 'fql.query',
                         'query' => "SELECT name FROM user WHERE uid=" . $friend['uid1'] . "",
                     ));
      print_r($nameOfFriend);
    }*/
    print_r($hometown);
    $i = rand(0, count($hometown) - 1);
    echo "   " . $i . "   ";
    $hometown_keys = array_keys($hometown);
    print_r($hometown_keys[$i]);
    print_r($hometown[$i]);
    //$hometown_keys['hometown_location'].city;
    /*foreach ($hometown as $person) {
      
      echo $person['name'];
      print_r($person['hometown_location']);
      $i++;
      if (i == 4) {
        break;
      }
    } */
  } catch (FacebookApiException $e) {
    // If the user is logged out, you can have a 
    // user ID even though the access token is invalid.
    // In this case, we'll get an exception, so we'll
    // just ask the user to login again here.
    $login_url = $facebook->getLoginUrl(); 
    echo 'Please <a href="' . $login_url . '">login.</a>';
    error_log($e->getType());
    error_log($e->getMessage());
  }
} else {
	// No user, print a link for the user to login
	$login_url = $facebook->getLoginUrl();
  echo 'Please <a href="' . $login_url . '">login.</a>';
}


?>
<!DOCTYPE HTML>
<head>
<title>TEST</title>
</head>
<body>
	<p>The user's name is: <?php echo $namePrint?></p>
</body>
</html>