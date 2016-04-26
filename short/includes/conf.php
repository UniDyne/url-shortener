<?php
// URL protocols allowed
// DO NOT EVER add "data:" or "javascript:" to this list
// or Bad Things will happen. You have been warned.
$allowed_protocols = ["http:","https:","ftp:","mailto:"];

// Include known redirect domains
// Be sure to add the domain this is installed on
// this is to prevent multiple redirects, redirect loops and other hijinx
$redirectors = ["bit.ly","goo.gl","ow.ly","t.co","tr.im","tinyurl.com","ur1.ca","nol.ag"];

// ***** Site Settings *****

// page title
define('PAGE_TITLE', 'URL Shortener');

// provide urls in rewrite mode
// if this is false, you will get parametric urls instead
define('REWRITE', true);

// plaintext crypto key for obfuscation
define('OBS_KEY', 'iMfR0m5p4C3');


// **** Database Settings ****

define('URL_TABLE', 'urls');

define('MYSQL_WRITE_HOST', 'localhost');
define('MYSQL_READ_HOST', 'localhost');
define('MYSQL_DB', 'shortener');
define('MYSQL_USER', 'shortener');
define('MYSQL_PASS', 'shortener');

?>
