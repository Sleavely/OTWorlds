<?php

require_once('inc/user.php');

if($user){
	require_once('page_main.php');
}else{
	require_once('page_landing.php');
}
