<?php
include("config.php");
@session_start();

$albumName = $_GET['albumname'];
if(!isset($_SESSION['Albums'])){

    header("location:index.php");
}
    $albums = $_SESSION['Albums'];

echo "<pre>";
//print_r($albums);
$count=0;
$albums = (json_decode($albums,true));
//echo "<pre/>";
//echo "Count album records : ".count($albums['albums'][0]['photos']);
//var_dump($albums);

  

    foreach($albums['albums'] as $key=>$value)
	{
		if($albumName == $value['name'])
		{
			echo "<div class=row>";
			foreach($value['photos'] as $item)
			{
			    echo "<div class=column>";
				echo "<img id=".$item['id']." src=".$item['picture']." height=200px width=200px/>";
						$count++;
				echo "</div>";
			}
			echo "</div>";
				
		}
	}
?>
<html>
    <head>
        <script src="javascript/selectAllcheckbox.js"></script>
        <style>
            body {
              font-family: Verdana, sans-serif;
              margin: 0;
            }
            
            * {
              box-sizing: border-box;
            }
            .row > .column {
                padding: 20 60px;
            }
            .row:after {
              content: "";
              display: table;
              clear: both;
            }
            .column {
              float: left;
              width: 25%;
            }
            img {
                  margin-bottom: -4px;
            }
            img.hover-shadow {
              transition: 0.3s;
            }
            .hover-shadow:hover {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }
        </style>
    </head>
</html>