<?php 
/**
 * @version 1.0
 * @package  Facebook Album downloader
 * @category PHP
 * @author   Mayurkumar Malaviya <mayurbmalaviya@gmail.com>
 * @since    24-08-2018
 * @link     https://bitscamp.com/Mayur/index.php
 *
 * Here facebook login will be allow to users.
 *
 **/

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
		
	</head>
	<body>
	
	<!-- Header Logo & Menu Strat -->	
	
	<section id="header" class="header-color">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo text-center">
						<h2><a href="index.html">Facebook Album Download</a></h2>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Header Logo & Menu End -->	
	<?php 
    	ob_start();
		include("config.php");
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email','user_photos']; // Optional permissions
	    ob_clean();
		$loginUrl = $helper->getLoginUrl('https://bitscamp.com/Mayur/album.php', $permissions);

//echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>
	
	<div class="section-padding1">
		<div class="container">
			<div class="row">
				<div class="fbLogin">
					<a href="<?php echo htmlspecialchars($loginUrl); ?>"><img src="assets/images/fb.png" alt="" class="img img-responsive centerImg"></a>
				</div>
			</div>
		</div>
	</div>
	
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