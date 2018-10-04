<?php
@session_start();
$username = $_SESSION['username'];
$uname = str_replace(' ','',$username);
$zipFileName = "facebook_".$uname."_albums.zip";

if(file_exists($zipFileName))
{
    unlink($zipFileName);
}
session_destroy();
header("location:index.php");
?>