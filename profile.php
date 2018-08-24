<?php 
/**
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://mayurbmalaviya.000webhostapp.com/Facebook_App/index.php
 *
 * Here facebook login will fetch records by using token.
 * using that token we have to access album data.
 * then we can download single or multiple albums on server
 * then we can download the album on pc as well as upload on google drive
 * we can also see all the photos of respective albums click on that album.
 **/
@session_start();
include("config.php");
if(isset($_SESSION['fb_access_token'])){
  $token =  $_SESSION['fb_access_token'];
}
else
{
    header("location:index.php");
}

try{
	$responseAlbums = $fb->get('/me?fields=name,albums{id,name,count,photos.limit(100){id,picture.type(large)},picture}', $token);
} 
catch(Facebook\Exceptions\FacebookResponseException $e){
	echo 'Graph returned an error : '.$e->getMessage();
	exit;
} 
catch(Facebook\Exceptions\FacebookRSDKException $e){
	echo 'Facebook SDK returned an error : '.$e->getMessage();
	exit;
}

$albums = $responseAlbums->getGraphUser();
if(!isset( $_SESSION['Albums'] ) ){
	$_SESSION['Albums'] =$albums;
}


// cluster of function
$mainDirectory='';
	$filepath = '';
    $zipfilename = '';
    $arrayAlbum=array();
	
	if(isset($_REQUEST['btnDownload']))
	{
	    $mainDirectory = "facebook_".$albums['name']."_albums";
		$mainDirectory = str_replace(' ','',$mainDirectory);
		$path = $mainDirectory;

		//echo $mainDirectory;
	
		if (!is_dir($mainDirectory)) {
			//mkdir($mainDirectory,0777,true);
		}
		if(isset($_REQUEST['images']))
		{
			//it will take individual selected albums
			foreach($_REQUEST['images'] as $sel)
			{
				$counter=0;
				
				foreach($albums['albums'] as $album)
				{
					$id = $albums['albums'][$counter]['id'];
					$albumName = $albums['albums'][$counter]['name'];
					$mainpath = $path;
					if($id == $sel)
					{
						$albumName = str_replace(' ','',$albumName);
						$albumPath = $mainpath."/".$albumName;
						
						/* it will check the directory is available or not if not the create */
						if (!is_dir($albumPath)) {
							
							mkdir($albumPath,0777,true);
						}	
						/* code for download images */
						$imagePath = $albumPath."/";
				
    						foreach($album['photos'] as $item)
    						{
    							file_put_contents($imagePath.$item['id'].'.jpg', file_get_contents($item['picture']));
    							writeOnFile($id.'-'.$item['id'].'-'.$albumName);
    						}
					}
					$counter++;
				}
			}
		}
		
		$zipfilename=createZipFile($mainDirectory);
		
		$_SESSION["zipfName"] = $zipfilename;
		deleteDir($path);
		$filepath = $_SERVER['DOCUMENT_ROOT']."/Facebook_App/".$mainDirectory.".zip";
	//    echo $filepath;
		//file_put_contents("./", fopen($filepath, 'r'));
		   /* echo "<div class='downloadlink'>click here for Download Album : <a  href=".$zipfilename.">Click Here to Download</a><br/>";*/
		    $_SESSION["drivefilename"] = $path.".zip"; 
			$_SESSION['download_file']='done';
			//header('location:profile.php');
		  //  echo "Click here to upload on Google Drive : <a  href='googleDriveUpload.php'>Click here to upload!</a></div>";
	}
	
	function album_page_data($albums_first_page,$albums_next_page_link)
	{
       $url=$albums_next_page_link;       
       $header = array("Content-type: application/json");
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	   curl_setopt($ch, CURLOPT_URL,$url);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   $st = curl_exec($ch);
	   
	   $retval = json_decode($st,true);
       $total_next_page_image=count($retval['data']);
	   
       for($i=0;$i<$total_next_page_image;$i++)
       {
       	   array_push($albums_first_page,$retval['data'][$i]['picture']);
       }
       
        if($retval['paging']['next'] == NULL)
        {
        	 return $albums_first_page;
        }      
		$temp = $retval['paging']['next'];
        $album_first_page[] = album_page_data($albums_first_page,$temp);
	}

	function sessionExpired()
	{
		unset($_SESSION['download_file']);
	}
	function createZipFile($folderName)
	{
		//$folderName= "zipFolderDemo";
		$filepath =  $_SERVER['DOCUMENT_ROOT']."/Facebook_App/".$folderName;
		$rootPath = realpath($filepath);

		// Initialize archive object
		$zip = new ZipArchive();
		$zipfilename = $folderName.'.zip';
		$zip->open($zipfilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
	
		foreach ($files as $name => $file)
		{
			// Skip directories (they would be added automatically)
			if (!$file->isDir())
			{
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
		}

		// Zip archive will be created only after closing object
		$zip->close();
		
		return $zipfilename;
	}
	
	function writeOnFile($id)
	{
		$myFile = "./sampleFile.txt";
		$myFileLink = fopen($myFile, 'w+') or die("Can't open file.");
		$newContents = $id;
		fwrite($myFileLink, $newContents);
		fclose($myFileLink);
	}
	function readFromFile()
	{
		$myFile = "sampleFile.txt";
		$myFileLink = fopen($myFile, 'r');
		$myFileContents = fread($myFileLink, filesize($myFile));
		fclose($myFileLink);
		echo $myFileContents;
	}
	
	function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Facebook Album Downloader</title>
		
		<link rel="shortcut icon" href="assets/images/favicon.png" />
		
		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<!-- fancybox css File -->
		<link rel="stylesheet" type="text/css" href="assets/source/jquery.fancybox.css" media="screen" />
		<!-- Main css File -->
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<!-- Mobile Responsive CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
		<script type="text/javascript">
        function selectAllcheckboxes(source)
				{
					if(document.getElementById('selectsAllchk').checked == true)
					{
						checkboxes = document.getElementsByName('images[]');
						for(var i=0, n=checkboxes.length;i<n;i++) {
							checkboxes[i].checked = source.checked;
						}
					}	
					else
					{
						checkboxes = document.getElementsByName('images[]');
						for(var i=0, n=checkboxes.length;i<n;i++) {
							checkboxes[i].checked = false;
						}
					}
				}
		</script>
	</head>
	<body>
	<form method="post">
	<!-- Header Logo & Menu Strat -->	
	
	<section id="header" class="header-color">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo text-center">
						<h2><a href="index.html">Facebok Album Downloader</a></h2>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Header Logo & Menu End -->	


	<div class="container">
		<div class="row">
		<div class="box-content">
			<input type="checkbox" id="selectsAllchk" name="selectsAllchk" onClick='selectAllcheckboxes(this)'/>
			<span>Select all</span> &nbsp; &nbsp;
			<span><input type="submit" id="btnDownload" name="btnDownload" value="Download_Albums" /></span>
		</div>
		</div>
	</div>
	
	
	<div class="container downloadBTn">
		<div class="row">
		<?php 
			if(isset($_SESSION['download_file']))
			{
		?>
		<div class="col-md-6 col-sm-12">
			<div class="box-content1 text-center">
				<span><a href="<?php echo $_SESSION['zipfName'] ?>">Click here to download</a></span>
			</div> 
		</div>
		
		
		<div class="col-md-6 col-sm-12">
			<div class="box-content1 text-center">
				<span><a href="googleDriveUpload.php" onclick="<?php echo sessionExpired();?>">Click here to upload on Google drive</a></span>
			</div>
		</div>
		</div>
		<?php } ?>
	</div>

	
	<section id="galley-listWrap">
		<div class="section-padding2">
			<div class="container">
				<div class="row">
					<!--start coding-->
					
					<?php 
						$count=0;
						foreach($albums['albums'] as $album)
						{
							$name = $albums['albums'][$count]['name'];
							$id = $albums['albums'][$count]['id'];
			
					?>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="image-box">
								<a href="images_Data.php?albumname=<?php echo $name; ?>">
									<img src="<?php echo $albums['albums'][$count]['photos'][0]['picture']; ?>" id='<?php echo $id ;?>' alt="" style="width:200px;height:200px;"/>
								</a>
								<div class="box-content">
									<input type='checkbox' id="<?php $id;?>" name='images[]' value='<?php echo $id; ?>'/>
									<span><?php echo $albums['albums'][$count]['name'];?></span>
								</div>
							</div>
						</div>
					
					<?php $count++; } ?>

				</div>
			</div>
		</div>
	</section>
	
	<!-- Footer Section Strat -->	

	<section id="footer" class="footer-color">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="copyright">
						<p>2018 &copy; Mayur Malaviya. All Rights Reserved</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	</form>
	<!-- Footer section end -->
	
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="source/jquery.mousewheel.pack.js"></script>
		<!-- Custom Script -->
		<script type="text/javascript" src="js/scripts.js"></script>
	
	</body>
</html>
