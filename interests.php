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
    # getStatusQuestion($facebook);
    # getBirthdayQuestion($facebook);
    getInterestsQuestion($facebook);
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
                        'query' => 'SELECT name, uid FROM user WHERE uid in (SELECT uid1 FROM friend WHERE uid2 = me())',
                        ));

  $answerNames = array();
  $answerUIDs = array();

  while (count($answerNames) < 4) {
    $i = rand(0, count($friends) - 1);
    $uid = $friends[$i]['uid'];
    if (!in_array($uid, $answersUIDs)) {
      array_push($answerUIDs, $uid);
      array_push($answerNames, htmlentities($friends[$i]['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8'));
    }
  }

  $answerUID = $answerUIDs[0];
  $statuses = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT message, like_info.like_count FROM status WHERE uid = ' . $answerUID,
                        ));

  $question = "";
  $likeCount = 0;
  foreach ($statuses as $status) {
    $newCount = $status['like_info']['like_count'];
    if ($newCount > $likeCount) {
      $likeCount = $newCount;
      $question = $status['message'];
    }
  }
  echo $answerNames[0];
  echo "<br />";
  echo $question;

}

# generates a question about a friend's birthday month
function getBirthdayQuestion($facebook) {
  $birthdays = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT name, uid, birthday_date FROM user WHERE birthday_date AND uid in (SELECT uid1 FROM friend WHERE uid2 = me())'
                        ));
  $i = rand(0, count($birthdays) - 1);
  $questionName = $birthdays[$i]['name'];
  $questionUID = $birthdays[$i]['uid'];
  $questionMonth = numToMonth(substr($birthdays[$i]['birthday_date'], 0, 2));

  $answersNames = array($questionName);
  $answersUIDs = array($questionUID);

  while (count($answersUIDs) < 4) {
    $i = rand(0, count($birthdays) - 1);
    $newMonth = numToMonth(substr($birthdays[$i]['birthday_date'], 0, 2));
    if ($questionMonth !== $newMonth) {
      $newUID  = $birthdays[$i]['uid'];
      if (!in_array($newUID, $answersUIDs)) {
        array_push($answersUIDs, $newUID);
        array_push($answersNames, htmlentities($birthdays[$i]['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8'));
      }
    }
  }
  $question = "Which of these friends was born in " . $questionMonth . "?";
  $questionArr = array("question" => $question, "answersNames" => $answersNames, "answersUIDs" => $answersUIDs);
  toJSON($questionArr);
}

# given a two character string representing a month, returns a string with the name of the month
function numToMonth($month) {
  switch ($month) {
      case "01":
        return "January";
      case "02":
        return "February";
      case "03":
        return "March";
      case "04":
        return "April";
      case "05":
        return "May";
      case "06":
        return "June";
      case "07":
        return "July";
      case "08":
        return "August";
      case "00":
        return "September";
      case "10":
        return "October";
      case "11":
        return "November";
      case "12":
        return "December";
  }
}

function getInterestsQuestion($facebook) {
  $interests = $facebook->api(array(
                        'method' => 'fql.query',
                        'query' => 'SELECT name, uid, interests FROM user WHERE music AND uid in (SELECT uid1 FROM friend WHERE uid2 = me())'
                        ));
  $i = rand(0, count($interests) - 1);
  $questionName = $interests[$i]['name'];
  $questionUID = $interests[$i]['uid'];
  $questionInterest = $interests[$i]['interests'];
  $questionInterest = explode(', ', $newInterests);
   $j = rand(0, count($questionInterest) - 1);
  $questionInterest = $questionInterest[j];

  $answersNames = array($questionName);
  $answersUIDs = array($questionUID);

  $newInterests = $interests[0]['interests'];
  echo $newInterests . "<br>";
  $newInterests = explode(', ', $newInterests);
  print_r($newInterests);
  /*
  while (count($answersUIDs) < 4) {
    $i = rand(0, count($interests) - 1);
    $newInterests = $interests[$i]['interests'];
    $newInterests = explode(', ', $newInterests);
    print_r($newInterests);
    if (!in_array($questionInterest, $newInterests)) {
      $newUID  = $interests[$i]['uid'];
      if (!in_array($newUID, $answersUIDs)) {
        array_push($answersUIDs, $newUID);
        array_push($answersNames, htmlentities($interests[$i]['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8'));
      }
    }
  }
  */
  $question = "Who is interested in " . $questionInterest . "?";
  $questionArr = array("question" => $question, "answersNames" => $answersNames, "answersUIDs" => $answersUIDs);
  toJSON($questionArr);
}

# prints JSON from Array
function toJSON($questionArr) {
  $question['question']['question_text'] = $questionArr['question'];
  $question['question']['answers']['names'] = $questionArr['answersNames'];
  $question['question']['answers']['uids'] = $questionArr['answersUIDs'];
  echo json_encode($question);
}

?>
