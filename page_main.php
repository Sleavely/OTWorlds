<?php

try {
	// Proceed knowing you have a logged in user who's authenticated.
	$user_profile = $facebook->api('/me');
	
} catch (FacebookApiException $e) {
	echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
	$user = null;
}

echo '<pre>';
print_r($user);
echo '<br /><br />';
print_r($user_profile);
