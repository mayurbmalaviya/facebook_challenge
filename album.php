<?php
/**
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://bitscamp.com/Mayur/album.php
 *
 *
 * Here we have to request facebook to generate token. 
facebook will provide token.*/
ob_start();
include("config.php");
include("functions.php");
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
$tokenMetadata->validateAppId('2160114084235812'); 
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
$token =  $_SESSION['fb_access_token'];
	try{
		$url = "https://graph.facebook.com/v3.1/me?fields=name%2Calbums%7Bid%2Cname%2Ccount%2Cphotos.limit(100)%7Bid%2Cimages%7D%7D&access_token=".$token;
		$header = array("Content-type: application/json");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$st = curl_exec($ch);
		$retval = json_decode($st,true);
		ob_start();
		$_SESSION['retrieveData'] = $retval;
		$_SESSION['username'] = $retval['name'];
		
		//total number of albums
		$total_album = count($retval['albums']['data']);
		
		$images_of_all_album = array();
		for($album_index = 0;$album_index < $total_album;$album_index++)
		{
			$album_name = $retval['albums']['data'][$album_index]['name'];
			//total images inside respective album
			$total_images = $retval['albums']['data'][$album_index]['count'];
			//if album is empty than it will continue to next album
			if($total_images == 0)
			{
				continue;
			}
			//if album images are less than 100
			else if($total_images < 100)
			{
				$images_of_album = $retval['albums']['data'][$album_index]['photos']['data'];
			}
			//if album images are more than 100
			else
			{
				//it will store the first page album into the array
				$images_of_album = $retval['albums']['data'][$album_index]['photos']['data'];	
				$next_page_link = $retval['albums']['data'][$album_index]['photos']['paging']['next'];
				album_page_data($images_of_album,$next_page_link);
			}
			$images_of_all_album[$album_name][] = $images_of_album;	
		}
		
		$_SESSION['images_of_all_albums'] = $images_of_all_album;
	}
	catch(Exception $e)
	{
		header("location:index.php");
	}


ob_clean();
header("location:profile.php");
exit;

?>