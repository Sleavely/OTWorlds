<?php
//This comment exists solely to test Bitbucket's post-push POST.
require_once('inc/user.php');

if($user){
	require_once('page_main.php');
}else{
	require_once('page_landing.php');
}
