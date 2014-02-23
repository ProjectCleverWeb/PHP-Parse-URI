<?php
/**
 * Evaluate the script functions as expected
 */

require __DIR__.'./uri.lib.php';

$uri1 = new uri('http://jdoe:test123@example.com:700/path/to/file.ext?q=1#frag');
$uri2 = new uri('google.com');

$prints = array();

$prints['full-vars'] = $uri1;
$prints['basic-vars'] = $uri1;






print_r($prints);

?>