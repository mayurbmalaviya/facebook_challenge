<?php
include('Facebook/autoload.php');
$appId = '318140122047320'; //Facebook App ID
$appSecret = '6231966ffb45d854e27b298bf5bad17f'; // Facebook App Secret
$homeurl = 'https://mayurbmalaviya.000webhostapp.com/Facebook_App/album.php';  //return to home
$fbPermissions = 'email';  //Required facebook permissions
$appdefault = 'v3.1';
$fb = new Facebook\Facebook(array(
  'app_id'  => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => $appdefault,
));
//$fbuser = $facebook->getUser();
?>