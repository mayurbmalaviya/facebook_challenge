<?php

	function album_page_data(&$albums_first_page,$albums_next_page_link)
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
		   //array_push($albums_first_page,$retval['data'][$i]);	
			$albums_first_page[] = $retval['data'][$i];
	   }
          if(array_key_exists('next',$retval['paging']))
		{
			$temp = $retval['paging']['next'];
			album_page_data($albums_first_page,$temp);
		}
	}
	function createZipFile($folderName)
	{
		if(file_exists($folderName.'.zip'))
        {

            unlink($folderName.'.zip');
        }
		$filepath =  $_SERVER['DOCUMENT_ROOT']."/".$folderName;
		$rootPath = realpath($folderName);
                
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
	function storeAllAlbumsImages()
	{
		if(isset($_SESSION['txtStoreAllImages']))
		{
			$filename = $_SESSION['txtStoreAllImages'];
			$filename = str_replace(' ','',$filename);
			if(file_exists($filename))
			{
				$my_file = $filename;
				unlink($my_file);
			}
			foreach($images_of_all_albums as $key=>$val)
			{
				foreach($val[0] as $images)
				{
					$my_file = $filename;
					$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
					$data = $images['images'][0]['source']." ".str_replace(' ','',$key)."\n";
					fwrite($handle, $data);
					fclose($handle);
				}
			}
		}
	}
	/* start read a file line by line */
	function retrieveAllAlbumsImages()
	{
		if(isset($_SESSION['txtStoreAllImages']))
		{
			$file = fopen($_SESSION['txtStoreAllImages'], "r") or exit("Unable to open file!");
			while(!feof($file))
			{
				$line = fgets($file). "<br>";
				$word_arr = explode(" ", $line);
				$count = 1;
				foreach($word_arr as $word){
					
					if($count==2)
							continue;
					echo $word."\n"; // required output
					$count++;
				}
			}
			fclose($file);
		}
	}
	/* complete a file line by line */
?>