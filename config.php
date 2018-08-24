<?php
include('Facebook/autoload.php');
$appId = '{APPID}'; //Facebook_APPID
$appSecret = '{AppSecretID}'; // Facebook App SecretID
$homeurl = '{write homeURL}';  //return to home
$fbPermissions = 'email';  //Required facebook permissions
$appdefault = 'v3.1';
$fb = new Facebook\Facebook(array(
  'app_id'  => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => $appdefault,
));
//$fbuser = $facebook->getUser();
?>
