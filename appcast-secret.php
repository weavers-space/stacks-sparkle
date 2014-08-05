<?php
// Set these lines to FALSE if you do not want to log update attempts.
// log_updates => logs all good update attempts from Sparkle.
// log_hacks => logs all attempts that are made outside of Sparkle (potential pirates)
$log_updates = TRUE;
$log_hacks = TRUE;
$log_debug = FALSE;

# The Stacks API Version
$stacks_api =  isset($_GET['StackAPIVersion']) ? $_GET['StackAPIVersion'] : 2;

function log_line($text) {
	// You can change the logfile name here
	$logfile = 'appcast-'. date("Y-m") .'.log';
	$log_line = date("D M j Y G:i:s T").' '.$text."\n";
 	$fh = fopen($logfile, 'a');
 	fwrite($fh, $log_line);
 	fclose($fh);
}
function log_connection($prepend) {
	global $stacks_api;
	log_line($prepend.' '. $_SERVER['REMOTE_ADDR'] .' USER_AGENT:'. $_SERVER['HTTP_USER_AGENT'] .' StackAPIVersion: '. strval($stacks_api));
}

// Test to see if this request is coming from Sparkle
if ( preg_match("/sparkle/i", $_SERVER['HTTP_USER_AGENT']) ) {

	# Check the StackAPIVersion option that Ships with Stacks v2.x
	# This will be used to determine the base url for your appcast
	$base_url = 'http://domain.com/appcasts'.$stacks_api;

	// This line parses out the name of the product supplied by Sparkle
	// Example: DispatchMobileMe/1.0.2 Sparkle/1.5
	preg_match('/^(\S+)\/\d/', $_SERVER['HTTP_USER_AGENT'], $matches);
	$product_name = $matches[1];

	// This is a section that adds the appearance of a random string to the url.
	// By using this clever users that figure out your URL scheme wont be able to get all your stacks for free.
	$key = 'my-s3cr3t-k3y';
	$secret = md5($product_name . $key);

	// The product name will be the actual name of the app
	// Examples: MyApp.app => MyApp, Dispatch.stack => Dispatch
	$appcast_url = $base_url .'/'.$product_name.'_'.$secret.'/appcast.xml';

	// Redirect to the appcast url
	header("Location: ".$appcast_url);

	if ($log_updates == TRUE){
		log_connection('UPDATE');
	}
	if ($log_debug == TRUE){
		log_line('*DEBUG PRODUCT_NAME: '. $product_name .' APPCAST_URL: '. $appcast_url);
	}
} else {
	// You can customize the HTML output here...
    echo "<h1>No updates for you SUCKA!!!!! Get outta here!!!!</h1>";
	if ($log_hacks == TRUE){
	    echo "<h3>Your IP has been logged... ".$_SERVER['REMOTE_ADDR']."</h3>";
		log_connection('PIRATE');
	}
}
?>