<?php

date_default_timezone_set('Asia/Jakarta');
while (true) {  
	list($usec, $sec) = explode(' ', microtime());	
	$usec = $usec * 1000000;	
	echo date('Y-m-d H:i:s', $sec) .':'.$usec."\n";
}

?>