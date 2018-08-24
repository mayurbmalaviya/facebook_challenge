<?php
/**
 * @version 1.0
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://mayurbmalaviya.000webhostapp.com/Facebook_App/index.php
 *
 * Here we can only see the particular images of particular albums.
 *
 **/
include("config.php");
@session_start();
$albumName = $_GET['albumname'];
if(!isset($_SESSION['Albums'])){

    header("location:index.php");
}

$albums = $_SESSION['Albums'];
$count=0;
$albums = (json_decode($albums,true));
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
	
	<section id="header" class="header-color">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo text-center">
						<h2><a href="index.html">Facebook album</a></h2>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Header Logo & Menu End -->	

	<section id="galley-listWrap">
		<div class="section-padding">
			<div class="container">
				<div class="row">
					<?php
						foreach($albums['albums'] as $key=>$value)
						{
							$flag = false;
							//echo $value['name']."<br/>";
							if($albumName == $value['name'])
							{
								$flag = true;
								foreach($value['photos'] as $item)
								{
											
								?>
								
								<div class="col-md-3 col-sm-3 col-xs-12">
									<div class="image-box">
										<a rel="example_group" title="hello" href="<?php echo $item['picture']; ?>">
											<img src="<?php echo $item['picture']; ?>"  id="<?php echo $item['id']; ?>" style="width:200px;height:200px;"/>
										</a>
									</div>
								</div>
								
								<?php
									$count++;
								}		
							}
							if($flag)
								break;
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

	
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="source/jquery.mousewheel.pack.js"></script>
		<!-- Custom Script -->
		<script type="text/javascript" src="js/scripts.js"></script>
		
	</body>
</html>