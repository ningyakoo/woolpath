<?php
	/**
	 * More info about this script on: 
	 * http://stackoverflow.com/questions/11511511/how-to-save-a-png-image-server-side-from-a-base64-data-string
	 */

	$data = $_REQUEST['base64data']; 
	$data2 = $_REQUEST['rand']; 
	
	echo $data;

	$image = explode('base64,',$data); 

	file_put_contents('img_'.$data2.'.png', base64_decode($image[1]));


?>
