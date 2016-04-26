<?php
/*==============================================================================

URL Shortener & Obfuscator

Based on source available at ur1.ca

==============================================================================*/

require_once 'includes/conf.php'; // site-specific settings
require_once 'includes/lilurl.php'; // lilURL class file
require_once 'includes/xorcrypt.php'; // xorcrypt class file

// Requires DNSBL pear package
require_once 'Net/DNSBL/SURBL.php'; // URL blacklisting
require_once 'Net/DNSBL.php'; // URL blacklisting


$msg = '';


if ( isset($_POST['longurl']) ) { // form submitted
	// This is a write transaction, use the master database
	$lilurl = new lilURL( READ_WRITE );
	
	// escape bad characters from the user's url
	$longurl = trim(mysql_escape_string($_POST['longurl']));
	
	// set the protocol to not ok by default
	$protocol_ok = false;
	
	// if there's a list of allowed protocols, 
	// check to make sure that the user's url uses one of them
	if ( count($allowed_protocols) ) {
		foreach ( $allowed_protocols as $ap ) {
			if ( strtolower(substr($longurl, 0, strlen($ap))) == strtolower($ap) ) {
				$protocol_ok = true;
				break;
			}
		}
	} else { // if there's no protocol list, allow whatever
		$protocol_ok = true;
	}
	
	$hostname = strtolower(parse_url($longurl, PHP_URL_HOST));
	
	$surbl = new Net_DNSBL_SURBL();
	
	// Check the user's IP address against SpamHaus
	$dnsbl = new Net_DNSBL();
	if ($dnsbl->isListed($_SERVER['REMOTE_ADDR'])) {
		$msg = '<div class="alert alert-danger" role="alert">Your computer is blacklisted; cannot make ur1s!</div>';
	} elseif (in_array($hostname, $redirectors)) {
		$msg = '<div class="alert alert-warning" role="alert">Already shortened!</div>';
	} elseif ($surbl->isListed($longurl)) {
		$msg = '<div class="alert alert-danger" role="alert">Blacklisted URL!</div>';
	} elseif ($dnsbl->isListed($hostname)) {
		$msg = '<div class="alert alert-danger" role="alert">Blacklisted Host!</div>';
    } elseif ( !$protocol_ok ) {
		$msg = '<div class="alert alert-warning" role="alert">Invalid protocol!</div>';
	} elseif ( $lilurl->add_url($longurl) ) { // add the url to the database
		$urlid = $lilurl->get_id($longurl);
		
		// get obfuscated version
		$xorc = new xorCrypt();
		$xorc->set_key(OBS_KEY);
		$obsid = $xorc->encrypt($urlid);
		
		if ( REWRITE ) { // mod_rewrite style link
			$url = 'http://'.$_SERVER['SERVER_NAME'].'/!'.$urlid;
			$obsurl = 'http://'.$_SERVER['SERVER_NAME'].'/$'.$obsid;
		} else { // regular GET style link
			$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?id='.$urlid;
			$obsurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?oid='.$obsid;
		}

		$msg = '<div class="alert alert-success" role="alert">Your short url is: <a href="'.$url.'">'.$url.'</a></div>';
		$msg .= '<div class="alert alert-success" role="alert">Your obfuscated url is: <a href="'.$obsurl.'">'.$obsurl.'</a></div>';
	} else {
		$msg = '<div class="alert alert-danger" role="alert">Creation of your url failed for some reason.</div>';
	}
} else { // if the form hasn't been submitted, look for an id to redirect to

	// This is a read transaction, use the slave database
	$lilurl = new lilURL( READ_ONLY );

	if ( isSet($_GET['id']) ) { // check GET first
		$id = mysql_escape_string($_GET['id']);
	} elseif ( isSet($_GET['oid']) ) { // handle obfuscated id
		$xorc = new xorCrypt();
		$xorc->set_key(OBS_KEY);
		$id = mysql_escape_string( $xorc->decrypt($_GET['oid']) );
	} elseif ( REWRITE ) { // check the URI if we're using mod_rewrite
		$explodo = explode('/', $_SERVER['REQUEST_URI']);
		$id = mysql_escape_string($explodo[count($explodo)-1]);
	} else {// otherwise, just make it empty
		$id = '';
	}
	
	// if the id isn't empty and it's not this file, redirect to it's url
	if ( $id != '' && $id != basename($_SERVER['PHP_SELF']) ) {
		$location = $lilurl->get_url($id);
		
		if ( $location != -1 ) {
			// check for blacklist
			$surbl = new Net_DNSBL_SURBL();
		    $dnsbl = new Net_DNSBL();
		    
		    if ($surbl->isListed($location) || $dnsbl->isListed(parse_url($location, PHP_URL_HOST))) {
		        // 410 Gone
				// XXX: cache this result
				header($_SERVER["SERVER_PROTOCOL"]." 410 Gone");
		        $msg = '<div class="alert alert-danger" role="alert">Blacklisted URL!</div>';
		        
		    } else {
		    	// 301 redirect
		    	header('Location: '.$location, true, 301);
		    }
		} else {
			$msg = '<div class="alert alert-warning" role="alert">Sorry, but that url isn\'t in our database.</div>';
		}
	}
}
?>
<?php include ('includes/form.php'); ?>
