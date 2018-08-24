<?php
@session_start();
include_once 'gmail/src/Google_Client.php';
include_once 'gmail/src/contrib/Google_Oauth2Service.php';
require_once 'gmail/src/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('{clientId}');
$client->setClientSecret('ClientSecretID');
$client->setRedirectUri('https://mayurbmalaviya.000webhostapp.com/Facebook_App/googleDriveUpload.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));


if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();		
    } else
        $client->setAccessToken($_SESSION['access_token']);
	//var_dump($client);
	
	
    //Insert a file
 
    //$fileName="facebook_SurajKumarSingh_albums.zip";
    $fileName=$_SESSION["drivefilename"];
	$file = new Google_DriveFile();
    $file->setTitle($fileName);
    $file->setMimeType('application/zip');
    $file->setDescription('A User Details is uploading in zip format');
	//print_r($file);
    //exit;
   
    $createdFile = $service->files->insert($file, array(
          'data' =>file_get_contents($fileName),
          'mimeType' => 'application/zip',
		  'uploadType'=>'multipart'
        ));
	if(file_exists($fileName)){
    	unlink($fileName);
	}
	header("Location:profile.php");
	exit;
	//print_r($createdFile);

} else {
    $authUrl = $client->createAuthUrl();
    header('Location:'.$authUrl);
    //header("Location:profile.php");
    exit();
}

?>
