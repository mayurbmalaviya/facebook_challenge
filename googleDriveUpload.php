<?php
    include('lib/google/vendor/autoload.php');
    include("config.php");
    @session_start();
	
	if(!isset($_SESSION['username']))
	{
		header("Location:index.php");
		exit;
	}
	
	if(isset($_REQUEST['arrayValue'])){
		$chk_values = $_REQUEST['arrayValue'];
		$chk_array = explode(" ", trim($chk_values));
		$_SESSION['selectedAlbum'] = $chk_array ;
	}
	
	$google_redirect_url = "https://bitscamp.com/Mayur/googleDriveUpload.php";
	$client = new Google_Client();
	
	$client->setClientId('294766078257-uct39genvd5trbn9jej4b3233hp0mq2v.apps.googleusercontent.com');
    $client->setClientSecret('JKvVXcwRsYagzE32AogtHJh8');
	$client->setRedirectUri($google_redirect_url);
	$client->addScope(Google_Service_Drive::DRIVE);
    
	function moveToDrive($drive,$root,$folder,$data)
	{
	    
		$fileMetadata = new Google_Service_Drive_DriveFile(array(
			'name' => $folder,
			'mimeType' => 'application/vnd.google-apps.folder',
			'parents' => array($root)
		));
		
		$curFolder = $drive->files->create($fileMetadata, array('fields' => 'id'));
		$curFolderId = $curFolder->id;

        
		foreach ($data[0] as $item) {
			$url = $item['images'][0]['source'];
			
			//echo $url;
			//exit;
			
			$fileMeta = new Google_Service_Drive_DriveFile(array(
				'name' => uniqid().'.jpg',
				'parents' => array($curFolderId)
			));
			
			$content = file_get_contents($url);
	
			$file = $drive->files->create($fileMeta, array(
				'data' => $content,
				'mimeType' => 'image/jpeg',
				'uploadType' => 'multipart',
				'fields' => 'id'));
		}
	}


	if (isset($_GET['code']) || (isset($_SESSION['access_token']))) 
	{
		$drive = new Google_Service_Drive($client);
		
		if (isset($_GET['code'])) {
			$client->authenticate($_GET['code']);
			$_SESSION['access_token'] = $client->getAccessToken();		
		} else
			$client->setAccessToken($_SESSION['access_token']);
		
		$rootName = 'facebook_'.str_replace(' ','',$_SESSION['username']).'_album';
		$fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' =>$rootName ,
        'mimeType' => 'application/vnd.google-apps.folder'));
		$rootDir = $drive->files->create($fileMetadata, array('fields' => 'id'));
		$rooTfolderId = $rootDir->id;
		
		$albums = $_SESSION['images_of_all_albums'] ; 
		$selectedAlbum = $_SESSION['selectedAlbum'];

		foreach($albums as $key => $album)
		{
			$singleAlbumName = trim(' '.str_replace(' ','',$key));
		    foreach($selectedAlbum as $selectedSingleAlbum)
		    {
				$selectedSingleAlbum = trim($selectedSingleAlbum);
		        if(!strcmp($singleAlbumName,$selectedSingleAlbum))
		        {
					moveToDrive($drive,$rooTfolderId,$singleAlbumName,$album);
					break;
		        }
		    }	    
		}
		
		header("Location:profile.php");
		exit;
	} else {
		$authUrl = $client->createAuthUrl();
		header('Location:'.$authUrl);
		exit();
	}
	
?>