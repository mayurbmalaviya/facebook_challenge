<?php
include('lib/Facebook/autoload.php');
$appId = '2160114084235812'; //Facebook App ID
$appSecret = 'feed0533310a45763fb8b1c15a1c6885'; // Facebook App Secret
$homeurl = 'https://bitscamp.com/Mayur/album.php';  //return to home
$fbPermissions = ['email,user_photos'];  //Required facebook permissions
$appdefault = 'v3.1';
$fb = new Facebook\Facebook(array(
  'app_id'  => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => $appdefault,
));
//$fbuser = $facebook->getUser();
?>