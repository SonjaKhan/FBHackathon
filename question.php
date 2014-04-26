<?php

require_once("php-sdk/facebook.php");

$config = array(
      'appId' => '649041091809661',
      'secret' => '8a01b28cb5e95f6dbf6c0871a53f73ac',
      'fileUpload' => false, // optional
      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
);

$facebook = new Facebook($config);

// Get User ID
$user = $facebook->getUser();

?>