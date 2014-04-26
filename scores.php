<?php

	$file = "scores.txt";
	$string = "TEST STRING";
	file_put_contents($file, $string, FILE_APPEND);
	echo $string;

?>