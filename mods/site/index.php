<?php
$rootPath = '';

if(preg_match("/blog/", $_SERVER['PHP_SELF'])){
	$rootPath = '../';
	
}
echo($rootPath);
?>


