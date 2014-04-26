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

if ($user_id) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me','GET');
    $namePrint = "Name: " . $user_profile['name'];
    $friends = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT uid1 FROM friend WHERE uid2=me()',
                        ));

    # getHometownQuestion($facebook);
    getStatusQuestion($facebook);
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

# Generates a question about hometowns
function getHometownQuestion($facebook) {
  $hometowns = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT name, uid, hometown_location.city, hometown_location.country FROM user WHERE hometown_location AND uid in (SELECT uid1 FROM friend WHERE uid2 = me())',
                        ));

  $i = rand(0, count($hometowns) - 1);
  $questionCity = $hometowns[$i]['hometown_location']['city'];
  $questionCountry = $hometowns[$i]['hometown_location']['country'];
  $questionName = $hometowns[$i]['name'];
  $questionUID  = $hometowns[$i]['uid'];

  $answersNames = array($questionName);
  $answersUIDs = array($questionUID);

  while (count($answersUIDs) < 4) {
    $i = rand(0, count($hometowns) - 1);
    $newCity = $hometowns[$i]['hometown_location']['city'];
    if ($questionCity !== $newCity) {
      $newUID  = $hometowns[$i]['uid'];
      if (!in_array($newUID, $answersUIDs)) {
        array_push($answersUIDs, $newUID);
        array_push($answersNames, htmlentities($hometowns[$i]['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8'));
      }
    }
  }

  $questionHometown = $questionCity . " (" . $questionCountry . ")";
  $question = "Who is from " . $questionHometown . "?";

  $questionArr = array("question" => $question, "answersNames" => $answersNames, "answersUIDs" => $answersUIDs);
  toJSON($questionArr);
}

# Generates a status question
function getStatusQuestion($facebook) {
  $friends = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT uid2 FROM friend WHERE uid1 = me()',
                        ));
  $i = rand(0, count($friends) - 1);
  $uid = $friends[$i]['uid2'];
  echo $uid;
}

# prints JSON from Array
function toJSON($questionArr) {
  $question['question']['question_text'] = $questionArr['question'];
  $question['question']['answers']['names'] = $questionArr['answersNames'];
  $question['question']['answers']['uids'] = $questionArr['answersUIDs'];
  echo json_encode($question);
}

?>
