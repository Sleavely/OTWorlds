<?php

try{
	require_once('facebook-php-sdk/facebook.php');
	
	$facebook = new Facebook(array(
	  'appId'  => '391055467680449',
	  'secret' => '828dd5806edaba916664ced4c3edd830',
	));
	
	// Get User ID
	$user = $facebook->getUser();
	
}catch(Exception $e){
	$user = 0;
}
