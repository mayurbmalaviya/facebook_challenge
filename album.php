<?php
/**
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://mayurbmalaviya.000webhostapp.com/Facebook_App/index.php
 *
 *
 * Here we have to request facebook to generate token. 
facebook will provide token.*/
include("config.php");
@session_start();
$helper = $fb->getRedirectLoginHelper();

 if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}
try {
	
  $accessToken = $helper->getAccessToken();
  
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (!isset($accessToken)) {
    echo "access token set";
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId('318140122047320'); // Replace {app-id} with your app id
$tokenMetadata->validateExpiration();
if (! $accessToken->isLongLived()) 
  {
  try {
       
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    //accessToken will have all the data which we have requested.
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';

}
if(!isset( $_SESSION['fb_access_token'] ) ){
	$_SESSION['fb_access_token'] = (string) $accessToken;
}

header("location: https://mayurbmalaviya.000webhostapp.com/Facebook_App/profile.php");
exit;

?>