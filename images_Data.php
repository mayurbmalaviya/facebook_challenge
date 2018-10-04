<?php
/**
 * @version 1.0
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://bitscamp.com/Mayur/images_Data.php
 *
 * Here we can only see the particular images of particular albums.
 *
 **/
include("config.php");
@session_start();
if(!isset($_SESSION['fb_access_token']))
{
	header("location:index.php");
	exit;
}
$albumName = $_GET['albumname'];

/*if(!isset($_SESSION['images_of_all_albums'])){
    header("location:index.php");
}*/

$albums = $_SESSION['images_of_all_albums'];
$count=0;
//$albums = (json_decode($albums,true));
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Gallery - Facebook Album Gallary</title>
		
		<link rel="shortcut icon" href="images/favicon.png" />
		
		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<!-- fancybox css File -->
		<link rel="stylesheet" type="text/css" href="assets/source/jquery.fancybox.css" media="screen" />
		<!-- Main css File -->
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<!-- Mobile Responsive CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
		
	</head>
	<body>
	
	<!-- Header Logo & Menu Strat -->	
	
	<?php include("header.php"); ?>

	<!-- Header Logo & Menu End -->	

	<section id="galley-listWrap">
		<div class="section-padding">
			<div class="container">
				<div class="row">
					<?php
						foreach($albums as $key=>$value)
						{
							//$flag = false;
							if(!strcmp(str_replace(' ','',$albumName),str_replace(' ','',$key)))
							{
								//$flag = true;
								foreach($value[0] as $images)
								{
											
								?>
								
								<div class="col-md-3 col-sm-3 col-xs-12">
									<div class="image-box">
										<a rel="example_group" title="<?php echo $key; ?>" href="<?php echo $images['images'][0]['source']; ?>">
											<img src="<?php echo $images['images'][0]['source']; ?>" class="img img-responsive" style="height:200px;width:100%"/>
										</a>
									</div>
								</div>
								
								<?php
								}		
							}
							/*if($flag)
								break;*/
						}
						
						?>
					
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

	<!-- Footer section end -->

	
		<script type="text/javascript" src="assets/js/jquery-2.1.3.min.js"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="assets/source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="assets/source/jquery.mousewheel.pack.js"></script>
		<!-- Custom Script -->
		<script type="text/javascript" src="assets/js/scripts.js"></script>
		
	</body>
</html>