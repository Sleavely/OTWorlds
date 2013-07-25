<?php

try{
	require_once('inc/db.php');
	require_once('inc/user.php');
}catch(Exception $e){
	$exception = $e->getMessage();
}

if($user && !isset($exception)){
	require_once('page_main.php');
}else{
	require_once('page_landing.php');
}
