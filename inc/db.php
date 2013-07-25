<?php

//Connect to DB
$db = new mysqli(
	'localhost',
	'root',
	'password',
	'otworlds',
	3306
);
if ($db->connect_errno) {
	error_log("Failed to connect to database! (" . $db->connect_errno . ") " . $db->connect_error);
    throw new Exception('We seem to be having database issues. Please try again in a bit or contact @ otworlds.com to get someone to look at the problem.');
}

if (!$db->set_charset("utf8")) {
    throw new Exception(sprintf("Error loading character set utf8: %s\n", $db->error));
}
