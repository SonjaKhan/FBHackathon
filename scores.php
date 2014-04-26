<?php


	$file=fopen("scores.txt","r+");
	file_put_contents($file, "TEST STRING", FILE_APPEND);

?>