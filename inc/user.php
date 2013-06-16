<?php

require_once('facebook-php-sdk/facebook.php');

$facebook = new Facebook(array(
  'appId'  => '391055467680449',
  'secret' => '828dd5806edaba916664ced4c3edd830',
));

// Get User ID
$user = $facebook->getUser();

if($user > 0){
	$logtext = '['.date('d-M-Y H:i:s').'] '.$user;
	file_put_contents('facebook.log', $logtext, FILE_APPEND);
}
